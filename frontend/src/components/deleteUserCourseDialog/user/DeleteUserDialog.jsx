import { useState } from "react";
import Button from "@mui/material/Button";
import Dialog from "@mui/material/Dialog";
import DialogActions from "@mui/material/DialogActions";
import DialogContent from "@mui/material/DialogContent";
import DialogContentText from "@mui/material/DialogContentText";
import DialogTitle from "@mui/material/DialogTitle";
import { IconButton } from "@mui/material";
import DeleteIcon from "@mui/icons-material/Delete";

export default function DeleteUserDialog ({ handleAgreeDelete, userFullName, email }) {
  const [open, setOpen] = useState(false);

  const handleClickOpen = () => {
    setOpen(true);
  };

  const handleClose = async () => {
    setOpen(false);
  };

  return <>
    <IconButton edge="end" aria-label="delete" onClick={handleClickOpen}>
      <DeleteIcon />
    </IconButton>
    <Dialog
      open={open}
      onClose={handleClose}
      aria-labelledby="alert-dialog-title"
      aria-describedby="alert-dialog-description"
    >
      <DialogTitle id="alert-dialog-title">
        Are you really wish to delete <strong>{userFullName}</strong> from current course?
      </DialogTitle>
      <DialogContent>
        <DialogContentText id="alert-dialog-description">
          Deleting a user from this course means, that all his tasks will also be deleted.
        </DialogContentText>
      </DialogContent>
      <DialogActions>
        <Button onClick={handleClose} autoFocus>Disagree</Button>
        <Button
          onClick={() => {
            handleAgreeDelete(email);
            handleClose();
          }}
        >
          Agree
        </Button>
      </DialogActions>
    </Dialog>
  </>;
}