import { useState } from "react";
import { useNavigate } from "react-router-dom";
import { useDeleteTaskMutation } from "../../../store/taskApi";
import Button from "@mui/material/Button";
import Dialog from "@mui/material/Dialog";
import DialogActions from "@mui/material/DialogActions";
import DialogContent from "@mui/material/DialogContent";
import DialogContentText from "@mui/material/DialogContentText";
import DialogTitle from "@mui/material/DialogTitle";
import DeleteForeverIcon from "@mui/icons-material/DeleteForever";

export default function DeleteTaskDialog ({ taskId, courseId }) {
  const navigate = useNavigate();
  const [open, setOpen] = useState(false);
  const [deleteTask] = useDeleteTaskMutation();

  const handleClickOpen = () => {
    setOpen(true);
  };

  const handleAgreeDelete = async () => {
    try {
      await deleteTask(taskId);
      navigate(`/course/${courseId}`);

    } catch (error) {
      console.log(error);
    }

    setOpen(false);
  };

  const handleClose = async () => {
    setOpen(false);
  };

  return <>
    <Button
      fullWidth
      variant="outlined"
      endIcon={<DeleteForeverIcon />}
      onClick={handleClickOpen}
    >
      Delete task
    </Button>

    <Dialog
      open={open}
      onClose={handleClose}
      aria-labelledby="alert-dialog-title"
      aria-describedby="alert-dialog-description"
    >
      <DialogTitle id="alert-dialog-title">
        {"Are you really wish to delete task?"}
      </DialogTitle>
      <DialogContent>
        <DialogContentText id="alert-dialog-description">
          Deleting a task means that it will not be possible to restore it.
        </DialogContentText>
      </DialogContent>
      <DialogActions>
        <Button onClick={handleClose}>Disagree</Button>
        <Button onClick={handleAgreeDelete} autoFocus>
          Agree
        </Button>
      </DialogActions>
    </Dialog>
  </>;
}