import { forwardRef, useState } from "react";
import CreateTaskForm from "../../createTaskForm/user/CreateTaskForm";
import CircularProgressModal from "../../circularProgressModal/CircularProgressModal";
import { AppBar, Box, Button, Dialog, IconButton, Slide, Toolbar, Typography } from "@mui/material";
import CloseIcon from "@mui/icons-material/Close";
import s from "./CreateTaskDialog.module.css";

const Transition = forwardRef(function Transition (props, ref) {
  return <Slide direction="up" ref={ref} {...props} />;
});

export default function CreateTaskDialog ({ openDialog, setOpenDialog, courseId }) {
  const [loading, setLoading] = useState(false);
  const handleClose = () => {
    setOpenDialog(false);
  };

  return <>
    {loading ? <CircularProgressModal /> : <></>}
    <Dialog
      fullScreen
      open={openDialog}
      onClose={handleClose}
      TransitionComponent={Transition}
    >
      <AppBar sx={{ position: "relative" }}>
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
      <Box className={s.contentBox}>
        <CreateTaskForm courseId={courseId} setLoading={setLoading} />
      </Box>
    </Dialog>
  </>;
}