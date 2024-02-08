import { forwardRef, useState } from "react";
import CreateTaskForm from "../../createTaskForm/user/CreateTaskForm";
import CircularProgressModal from "../../circularProgressModal/CircularProgressModal";
import { Alert, AppBar, Box, Button, Dialog, IconButton, Slide, Toolbar, Typography } from "@mui/material";
import CloseIcon from "@mui/icons-material/Close";
import s from "./CreateTaskDialog.module.css";
import CheckIcon from "@mui/icons-material/Check";

const Transition = forwardRef(function Transition (props, ref) {
  return <Slide direction="up" ref={ref} {...props} />;
});

export default function CreateTaskDialog ({ openDialog, setOpenDialog, courseId }) {
  const [loading, setLoading] = useState(false);
  const [isTaskSuccessfullyCreated, setIsTaskSuccessfullyCreated] = useState(false);
  const handleClose = () => {
    setOpenDialog(false);
  };

  console.log(isTaskSuccessfullyCreated)

  return <>
    {loading ? <CircularProgressModal /> : null}
    <Dialog
      fullScreen
      open={openDialog}
      onClose={handleClose}
      TransitionComponent={Transition}
    >
      <AppBar className={s.appBar}>
        <Toolbar>
          <IconButton
            edge="start"
            color="inherit"
            onClick={handleClose}
            aria-label="close"
          >
            <CloseIcon />
          </IconButton>
          <Typography sx={{ ml: 2, flex: 1 }} variant="h6" component="div">
            Task
          </Typography>
        </Toolbar>
      </AppBar>

      {isTaskSuccessfullyCreated && <Alert
        className={s.taskCreatedSuccessfullyAlert} icon={<CheckIcon fontSize="inherit" />} severity="success"
      >
        New task created successfully!
      </Alert>}
      <Box className={s.contentBox}>
        <CreateTaskForm courseId={courseId} setLoading={setLoading} setIsTaskSuccessfullyCreated={setIsTaskSuccessfullyCreated} />
      </Box>
    </Dialog>
  </>;
}