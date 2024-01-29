import { useEffect, useState } from "react";
import { useParams } from "react-router-dom";
import { Helmet } from "react-helmet";

import { useGetTasksByCourseQuery } from "../../../store/taskApi";
import { useGetTaskUserQuery } from "../../../store/taskUserApi";
import { useGetCourseUsersQuery } from "../../../store/courseUserApi";

import {
  Accordion,
  AccordionDetails,
  AccordionSummary, Avatar, Box, Button,
  Card,
  CardContent,
  Typography
} from "@mui/material";
import CreateTaskDialog from "../../../components/createTaskDialog/user/CreateTaskDialog";
import CircularProgressModal from "../../../components/circularProgressModal/CircularProgressModal";
import AddIcon from "@mui/icons-material/Add";
import ExpandMoreIcon from "@mui/icons-material/ExpandMore";
import AssignmentIcon from "@mui/icons-material/Assignment";
import { deepOrange } from "@mui/material/colors";
import s from "./TaskSubPage.module.css";

export default function TaskSubPage ({ setValueTab }) {
  const { id } = useParams();
  const { data: taskData, isLoading: isLoadingTask } = useGetTasksByCourseQuery(id);
  const { data: taskUserData, isLoading: isLoadingUserData } = useGetTaskUserQuery();
  const { data: courseUserData, isLoading: isLoadingCourseUserData } = useGetCourseUsersQuery();

  const [taskStatistic, setTaskStatistic] = useState({});
  const [openDialog, setOpenDialog] = useState(false);

  useEffect(() => {
    if (courseUserData) {
      const taskCounts = taskUserData?.["hydra:member"].reduce((counts, task) => {
        const isDone = task?.mark;
        const taskId = task.task.split("/").pop();

        if (!counts[taskId]) {
          counts[taskId] = { done: 0, all: 0 };
        }

        if (isDone !== null) {
          counts[taskId].done += 1;
        }
        return counts;
      }, {});

      taskCounts.all = courseUserData["hydra:member"].reduce((counter, item) => {
        if (item.course === `/api/courses/${id}` && !item.isCreator) {
          counter += 1;
        }
        return counter;
      }, 0);

      setTaskStatistic(taskCounts);
    }

  }, [taskUserData, courseUserData]);

  useEffect(() => {
    setValueTab("2");
  }, []);

  return <>
    <Helmet>
      <title>TaskSubPage</title>
    </Helmet>
    {isLoadingTask || isLoadingUserData ? <CircularProgressModal /> : <Card className={s.card}>
      <CardContent className={s.cardContent}>
        <Button
          variant="contained" color="primary" startIcon={<AddIcon />} className={s.button} onClick={() => {
          setOpenDialog(true);
        }}
        >
          Create
        </Button>
        {taskData?.slice()?.sort((a, b) => {
          const dateA = new Date(a?.dueDate?.date);
          const dateB = new Date(b?.dueDate?.date);
          return dateB - dateA;
        })?.map(({ name, createdAt, id, dueDate }) => {
          return <Accordion className={s.accordion} key={name}>
            <AccordionSummary
              expandIcon={<ExpandMoreIcon />}
              aria-controls="panel1-content"
              id="panel1-header"
              className={s.accordionSummary}
            >
              <Box className={s.accordionSummaryContent}>
                <Box className={s.iconNameBoxWrapper}>
                  <Avatar sx={{ bgcolor: deepOrange[500] }}>
                    <AssignmentIcon />
                  </Avatar>
                  <Typography variant="subtitle1" className={s.name}>
                    {name}
                  </Typography>
                </Box>

                <Typography variant="overline" className={s.date}>
                  {createdAt?.date.split(".")[0]}
                </Typography>
              </Box>

            </AccordionSummary>

            <AccordionDetails className={s.accordionDetails}>
              <Box>
                <Typography>
                  Term: {dueDate ? dueDate?.date.split(".")[0] : "no term"}
                </Typography>
              </Box>
              <Box className={s.taskStatusesWrapper}>
                <div>
                  <Typography className={s.countSubmittedTasks} variant="h4">{taskStatistic[id] ? taskStatistic[id]?.done : 0}</Typography>
                  <Typography variant="title1">Submitted</Typography>
                </div>
                <div>
                  <Typography className={s.countAppointedTasks} variant="h4">{taskStatistic?.all ? taskStatistic?.all : 0}</Typography>
                  <Typography variant="title1">Appointed</Typography>
                </div>
              </Box>
            </AccordionDetails>
          </Accordion>;
        })}
      </CardContent>
    </Card>}
    <CreateTaskDialog openDialog={openDialog} setOpenDialog={setOpenDialog} courseId={id} />
  </>;
}