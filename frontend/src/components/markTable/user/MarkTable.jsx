import { useParams } from "react-router-dom";
import { useForm } from "react-hook-form";
import { zodResolver } from "@hookform/resolvers/zod";
import { useGetTaskUsersByCourseIdQuery, useUpdateTaskUserMutation } from "../../../store/taskUserApi";
import { markTableSchema } from "../../../schemesValidation/markTableSchema";
import axios from "axios";
import { IconButton, Table, TableBody, TableContainer, TableHead, TableRow, TextField } from "@mui/material";
import CircularProgressModal from "../../circularProgressModal/CircularProgressModal";
import TableCell, { tableCellClasses } from "@mui/material/TableCell";
import Paper from "@mui/material/Paper";
import { styled } from "@mui/material/styles";
import CheckIcon from "@mui/icons-material/Check";
import s from "./MarkTable.module.css";

const StyledTableCell = styled(TableCell)(({ theme }) => ({
  [`&.${tableCellClasses.head}`]: {
    backgroundColor: theme.palette.common.white,
    color: theme.palette.common.black
  },
  [`&.${tableCellClasses.body}`]: {
    fontSize: 14
  }
}));

const StyledTableRow = styled(TableRow)(({ theme }) => ({
  "&:nth-of-type(odd)": {
    backgroundColor: theme.palette.action.hover
  },
  // hide last border
  "&:last-child td, &:last-child th": {
    border: 0
  }
}));

export default function MarkTable ({ setIsSuccessfulUpdateMark }) {
  const { id } = useParams();
  const {
    data: taskUserData,
    isLoading: isLoadingTaskUser,
    refetch: refetchTaskUsersByCourseId
  } = useGetTaskUsersByCourseIdQuery(id);
  const [updateTaskUser] = useUpdateTaskUserMutation();
  const { register, handleSubmit, formState: { errors }, setValue, reset } = useForm({
    resolver: zodResolver(markTableSchema)
  });

  const onSubmit = async (data) => {
    try {
      const response = await axios.patch(`https://localhost/api/task_users/${data?.taskUserId}`, {
        "mark": `${data?.mark}`
      }, {
        headers: {
          "Content-Type": "application/merge-patch+json",
          "Authorization": `Bearer ${localStorage.getItem("token")}`
        }
      });

      if (response.status === 200) {
        setIsSuccessfulUpdateMark(true);
        setTimeout(() => {
          setIsSuccessfulUpdateMark(false);
        }, 3000);
        await refetchTaskUsersByCourseId();
      }
    } catch (error) {
      console.log(error);
    }

    /*    try {
          console.log("Request Payload:", { id: data?.taskUserId, mark: data?.mark });
          await updateTaskUser({ id: data?.taskUserId, mark: data?.mark });
        } catch (error) {
          console.log(error);
        }*/
  };

  const handleDownload = (file) => {
    const userConfirmed = window.confirm("Are you sure you want to download this file?");
    if (userConfirmed) {
      window.open(`/api/task-user-file/${encodeURIComponent(file)}`, "_blank");
    }
  };

  return (<>
      {isLoadingTaskUser ? <CircularProgressModal /> : (
        <TableContainer component={Paper}>
          <Table sx={{ minWidth: 1200 }} aria-label="customized table">
            <TableHead>
              <TableRow>
                <StyledTableCell align="center">First name</StyledTableCell>
                <StyledTableCell align="center">Last name</StyledTableCell>
                <StyledTableCell align="center">Email</StyledTableCell>
                <StyledTableCell align="center">Task name</StyledTableCell>
                <StyledTableCell align="center">Submission date</StyledTableCell>
                <StyledTableCell align="center">Due date</StyledTableCell>
                <StyledTableCell align="center">File</StyledTableCell>
                <StyledTableCell align="center">Link</StyledTableCell>
                <StyledTableCell align="center">Mark</StyledTableCell>
              </TableRow>
            </TableHead>
            <TableBody>
              {taskUserData?.map(({
                firstName,
                lastName,
                email,
                name,
                date,
                dueDate,
                solvedTaskFileName: file,
                linkSolvedTask: link,
                mark,
                id
              }, index) => (
                <StyledTableRow key={email}>
                  <TableCell align="center">{firstName}</TableCell>
                  <TableCell align="center">{lastName}</TableCell>
                  <TableCell align="center">{email}</TableCell>
                  <TableCell align="center">{name}</TableCell>
                  <TableCell align="center">{date.date}</TableCell>
                  <TableCell align="center">{dueDate.date}</TableCell>
                  <TableCell align="center" className={s.fileName} onClick={() => handleDownload(file)}>{file ? file : "----"}</TableCell>
                  <TableCell align="center">{link ? <a href={link}>{link}</a> : "----"}</TableCell>
                  <TableCell align="center">
                    <form action="" method="get" onSubmit={handleSubmit(onSubmit)} id={`markForm-${index}`}>
                      <TextField id="standard-basic" type="number" name="mark" defaultValue={mark ? mark : null} variant="standard" inputProps={register("mark", { valueAsNumber: true })} error={!!errors.mark} />
                      <input type="hidden" name="taskUserId" value={id} {...register("taskUserId")} />
                    </form>
                  </TableCell>
                  <TableCell align="center">
                    <IconButton color="secondary" type="submit" aria-label="add an alarm" form={`markForm-${index}`}>
                      <CheckIcon />
                    </IconButton>
                  </TableCell>
                </StyledTableRow>
              ))}
            </TableBody>
          </Table>
        </TableContainer>
      )}
    </>
  );
}
