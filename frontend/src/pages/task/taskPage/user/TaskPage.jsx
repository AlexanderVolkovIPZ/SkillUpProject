import { useContext, useEffect, useState } from "react";
import { Link, useParams } from "react-router-dom";
import { useForm } from "react-hook-form";
import { zodResolver } from "@hookform/resolvers/zod";
import { useGetTaskQuery } from "../../../../store/taskApi";
import { attachTaskSolutionSchema } from "../../../../schemesValidation/attachTaskSolution";
import { AuthContext } from "../../../../context/authContext";
import { useDeleteTaskUserMutation, useGetTaskUserQuery } from "../../../../store/taskUserApi";
import { useIsCurrentUserCourseCreatorQuery } from "../../../../store/courseApi";
import {
  Avatar,
  Box, Button,
  IconButton,
  Typography
} from "@mui/material";

import CircularProgressModal from "../../../../components/circularProgressModal/CircularProgressModal";
import FileViewer from "../../../../components/fileViewer/FileViewer";
import FormAttachTask from "../../../../components/modalAttachTask/user/FormAttachTask";
import Divider from "@mui/material/Divider";
import AssignmentIcon from "@mui/icons-material/Assignment";
import List from "@mui/material/List";
import TextSnippetIcon from "@mui/icons-material/TextSnippet";
import ClearIcon from "@mui/icons-material/Clear";
import LinkIcon from "@mui/icons-material/Link";
import FolderIcon from "@mui/icons-material/Folder";
import NotesIcon from "@mui/icons-material/Notes";
import DeleteForeverIcon from "@mui/icons-material/DeleteForever";
import CancelScheduleSendIcon from "@mui/icons-material/CancelScheduleSend";
import UpdateIcon from "@mui/icons-material/Update";
import { deepOrange } from "@mui/material/colors";
import s from "./TaskPage.module.css";

export default function TaskPage () {
  const { taskId } = useParams();
  const currentUserInfo = useContext(AuthContext);
  const [status, setStatus] = useState("Appointed");
  const [fileName, setFileName] = useState(null);
  const [link, setLink] = useState(null);
  const [fileNameSubmitted, setFileNameSubmitted] = useState(null);
  const [linkSubmitted, setLinkSubmitted] = useState(null);
  const [submittedTask, setSubmittedTask] = useState(null);
  const { data: taskData, isLoading: isLoadingTask } = useGetTaskQuery(taskId);
  const { data: taskUserData, isLoading: isLoadingTaskUser } = useGetTaskUserQuery();
  const {
    data: userCreatorData,
    isLoading: isUserCreatorData
  } = useIsCurrentUserCourseCreatorQuery(taskData?.course?.split("/")?.pop());
  const [deleteTaskUser] = useDeleteTaskUserMutation();
  const {
    register,
    handleSubmit,
    formState: { errors },
    setValue,
    reset,
    getValues
  } = useForm({
    resolver: zodResolver(attachTaskSolutionSchema)
  });

  useEffect(() => {
    if (taskUserData && currentUserInfo) {
      const task = taskUserData["hydra:member"].find((item) => {
        return item.task === `/api/tasks/${taskId}` && item.user === `/api/users/${currentUserInfo?.userInfo?.id}`;
      });

      if (task) {
        setSubmittedTask(task);
        task?.mark ? setStatus("Evaluated") : setStatus("Submitted");
      }

      if (task?.solvedTaskFileName) {
        setFileNameSubmitted(task.solvedTaskFileName);
      }

      if (task?.linkSolvedTask) {
        setLinkSubmitted(task.linkSolvedTask);
      }
    }
  }, [taskUserData, currentUserInfo]);

  const handleRemoveFile = () => {
    setFileName(null);
    setValue("file", null);
  };

  const handleRemoveLink = () => {
    setValue("link", "");
    setLink(null);
  };

  const handleDeleteSubmit = async () => {
    const taskUserId = submittedTask["@id"]?.split("/")?.pop();
    try {
      await deleteTaskUser(taskUserId);
      setFileNameSubmitted(null);
      setLinkSubmitted(null);
      setFileName(null);
      setLink(null);
      reset();
      setStatus("Appointed");
    } catch (error) {
      console.log(error);
    }
  };

  return (
    <>
      {isLoadingTask || isLoadingTaskUser || isUserCreatorData ? (
        <CircularProgressModal />
      ) : (
        <Box className={s.taskBox}>
          <Box className={s.leftSide}>
            <Avatar sx={{ bgcolor: deepOrange[500] }}>
              <AssignmentIcon />
            </Avatar>
            <Box className={s.boxInfo}>
              <Typography variant="h4">{taskData?.name}</Typography>
              <Typography variant="overline" className={s.date}>{taskData?.createdAt}</Typography>
              <Divider color="warning" />
              {taskData?.description ? <Box className={s.boxDescription}>
                <Box className={s.descriptionBoxIconLabel}>
                  <NotesIcon color="primary" />
                  <Typography variant="h5" className={s.descriptionLbl}>Description</Typography>
                </Box>
                <Typography variant="body1" dangerouslySetInnerHTML={{ __html: taskData?.description }}></Typography>
              </Box> : null}
              {taskData?.fileNameTask ? <Box className={s.boxFileContent}>
                <Box className={s.filesBoxIconLabel}>
                  <FolderIcon color="primary" />
                  <Typography variant="h5">Files</Typography>
                </Box>
                <List>
                  <FileViewer fileName={taskData?.fileNameTask} />
                </List>
              </Box> : null}
            </Box>
          </Box>
          <Box className={s.rightSide}>
            <Box className={s.yourWork}>
              {!userCreatorData?.isUserCourseCreator ? (
                <>
                  <Box className={s.titleAttachTask}>
                    <Typography variant="h6">Your task</Typography>
                    <Typography variant="overline" className={s.statusLabel}>{status}</Typography>
                  </Box>
                  {fileNameSubmitted && (
                    <Box className={`${s.fileNameBoxAttachTask} ${s.itemTaskSubmitted}`}>
                      <Box className={s.fileNameWithIcon}>
                        <TextSnippetIcon />
                        <Typography className={s.fileName}>{fileNameSubmitted}</Typography>
                      </Box>
                    </Box>
                  )}
                  {linkSubmitted && (
                    <Box className={`${s.linkBoxAttachTask} ${s.itemTaskSubmitted}`}>
                      <Box className={s.linkWithIcon}>
                        <LinkIcon />
                        <Typography className={s.linkName}>{linkSubmitted}</Typography>
                      </Box>
                    </Box>
                  )}
                  {fileName && !fileNameSubmitted && (
                    <Box className={s.fileNameBoxAttachTask}>
                      <Box className={s.fileNameWithIcon}>
                        <TextSnippetIcon />
                        <Typography className={s.fileName}>{fileName}</Typography>
                      </Box>
                      <IconButton aria-label="delete" size="large" onClick={handleRemoveFile}>
                        <ClearIcon />
                      </IconButton>
                    </Box>
                  )}
                  {link && !linkSubmitted && (
                    <Box className={s.linkBoxAttachTask}>
                      <Box className={s.linkWithIcon}>
                        <LinkIcon />
                        <Typography className={s.linkName}>{link}</Typography>
                      </Box>
                      <IconButton aria-label="delete" size="large" onClick={handleRemoveLink}>
                        <ClearIcon />
                      </IconButton>
                    </Box>
                  )}
                  <FormAttachTask setFileName={setFileName} setLink={setLink} register={register} handleSubmit={handleSubmit} taskId={taskId} isSubmittedFile={fileNameSubmitted !== null} isSubmittedLink={linkSubmitted !== null} />
                  {(fileNameSubmitted || linkSubmitted) && (
                    <Button
                      fullWidth variant="outlined" endIcon={<CancelScheduleSendIcon />} onClick={handleDeleteSubmit}
                    >
                      Cancel sending
                    </Button>
                  )}
                </>
              ) : (
                <>
                  <Button
                    fullWidth
                    variant="outlined"
                    endIcon={<UpdateIcon />}
                    component={Link}
                    to="update"
                  >
                    Update task
                  </Button>
                  <Button
                    fullWidth
                    variant="outlined"
                    endIcon={<DeleteForeverIcon />}
                    component={Link}
                    to="delete"
                  >
                    Delete task
                  </Button>
                </>
              )}
            </Box>
          </Box>
        </Box>
      )}
    </>
  );

}