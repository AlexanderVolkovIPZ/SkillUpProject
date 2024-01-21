import React from "react";
import { AppBar, Button, Dialog, IconButton, Slide, Toolbar, Typography } from "@mui/material";
import List from "@mui/material/List";
import ListItemButton from "@mui/material/ListItemButton";
import ListItemText from "@mui/material/ListItemText";
import Divider from "@mui/material/Divider";
import CloseIcon from "@mui/icons-material/Close";

const Transition = React.forwardRef(function Transition (props, ref) {
  return <Slide direction="up" ref={ref} {...props} />;
});

export default function CreateTaskDialog ({ openDialog, setOpenDialog }) {

  const handleClose = () => {
    setOpenDialog(false);
  };
  return <Dialog
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
        <Button autoFocus color="inherit" onClick={handleClose}>
          save
        </Button>
      </Toolbar>
    </AppBar>
    <List>
      <ListItemButton>
        <ListItemText primary="Phone ringtone" secondary="Titania" />
      </ListItemButton>
      <Divider />
      <ListItemButton>
        <ListItemText
          primary="Default notification ringtone"
          secondary="Tethys"
        />
      </ListItemButton>
    </List>
  </Dialog>;
}