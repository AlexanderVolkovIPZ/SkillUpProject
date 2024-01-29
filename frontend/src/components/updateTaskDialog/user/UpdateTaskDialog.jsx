import { forwardRef, useState } from "react";
import CircularProgressModal from "../../circularProgressModal/CircularProgressModal";
import UpdateTaskForm from "../../updateTaskForm/user/UpdateTaskForm";
import { Alert, AppBar, Box, Button, Dialog, IconButton, Slide, Toolbar, Typography } from "@mui/material";
import CloseIcon from "@mui/icons-material/Close";
import UpdateIcon from "@mui/icons-material/Update";
import CheckIcon from "@mui/icons-material/Check";
import s from "./UpdateTaskDialog.module.css";

const Transition = forwardRef(function Transition (props, ref) {
  return <Slide direction="up" ref={ref} {...props} />;
});

export default function UpdateTaskDialog () {
  const [loading, setLoading] = useState(false);
  const [open, setOpen] = useState(false);
  const [isUpdateDataSuccessfully, setIsUpdateDataSuccessfully] = useState(false);

  const handleClickOpen = () => {
    setOpen(true);
  };

  const handleClose = () => {
    setOpen(false);
  };

  return <>
    {loading ? <CircularProgressModal /> : null}
    <Button fullWidth variant="outlined" endIcon={<UpdateIcon />} onClick={handleClickOpen}>
      Update task
    </Button>
    <Dialog
      fullScreen
      open={open}
      onClose={handleClose}
      TransitionComponent={Transition}
    >
      <AppBar sx={{ position: "relative" }} className={s.dialogHeaderTaskUpdate}>
        {isUpdateDataSuccessfully &&
          <Alert className={s.taskUpdateSuccessfullyAlert} icon={<CheckIcon fontSize="inherit" />} severity="success">
            Task updated successfully!
          </Alert>}
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
            Update task
          </Typography>
        </Toolbar>
      </AppBar>
      <Box className={s.contentBox}>
        <UpdateTaskForm setIsUpdateDataSuccessfully={setIsUpdateDataSuccessfully} />
      </Box>
    </Dialog>
  </>;
}