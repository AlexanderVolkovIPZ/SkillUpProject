import { useContext, useState } from "react";
import { useCreateTaskUserMutation } from "../../../store/taskUserApi";
import PopupState, { bindMenu, bindTrigger } from "material-ui-popup-state";
import { AuthContext } from "../../../context/authContext";
import {
  Box,
  Button,
  ButtonGroup,
  FormControl,
  Input,
  InputAdornment,
  InputLabel,
  Menu,
  MenuItem,
  Modal,
  Typography
} from "@mui/material";
import AttachFileIcon from "@mui/icons-material/AttachFile";
import LinkIcon from "@mui/icons-material/Link";
import SendIcon from "@mui/icons-material/Send";
import s from "./FormAttachTask.module.css";

const style = {
  position: "absolute",
  top: "50%",
  left: "50%",
  transform: "translate(-50%, -50%)",
  width: 400,
  bgcolor: "background.paper",
  border: "2px solid #000",
  boxShadow: 24,
  p: 4
};

export default function FormAttachTask ({
  setFileName,
  setLink,
  register,
  handleSubmit,
  taskId,
  isSubmittedFile,
  isSubmittedLink
}) {
  const [open, setOpen] = useState(false);
  const currentUserInfo = useContext(AuthContext);
  const [createTaskUser] = useCreateTaskUserMutation();

  const onSubmit = async (data) => {
    if (data && currentUserInfo?.userInfo && taskId) {
      const formData = new FormData();
      if (data?.file) {
        Array.from(data?.file)?.forEach((file, index) => {
          formData.append(`file`, file);
        });
      }
      formData.append("link", data?.link);
      formData.append("user", currentUserInfo?.userInfo?.id);
      formData.append("task", taskId);
      await createTaskUser(formData);
    }

  };
  const handleOpen = () => setOpen(true);
  const handleClose = () => setOpen(false);
  const handleClear = () => {
    document.getElementById("standard-adornment-amount").value = "";
  };

  return (
    <form
      action=""
      className={s.formAttachTask}
      encType="multipart/form-data"
      onSubmit={handleSubmit(onSubmit)}
    >
      <PopupState variant="popover" popupId="select-type-hw-popup-menu" className={s.popupMenu}>
        {(popupState) => (
          <>
            <Button fullWidth variant="outlined" disabled={isSubmittedFile || isSubmittedLink} {...bindTrigger(popupState)}>
              Add or create
            </Button>
            <Menu {...bindMenu(popupState)}>
              <label htmlFor="raised-button-file">
                <MenuItem>
                  <AttachFileIcon />
                  File
                </MenuItem>
              </label>
              <input
                accept="*"
                hidden
                id="raised-button-file"
                type="file"
                name="file"
                {...register("file")}
                onInput={(event) => {
                  popupState.close();
                  setFileName(event?.target?.value?.split("\\")?.pop());
                }}
              />
              <MenuItem
                onClick={() => {
                  handleOpen();
                }}
              >
                <LinkIcon />
                Link
              </MenuItem>

              <Modal
                open={open}
                onClose={handleClose}
                aria-labelledby="modal-modal-title"
                aria-describedby="modal-modal-description"
              >
                <Box sx={style}>
                  <Typography id="modal-modal-title" variant="h6" component="h2">
                    Add link
                  </Typography>
                  <FormControl fullWidth sx={{ m: 1 }} variant="standard">
                    <InputLabel htmlFor="standard-adornment-amount">Link</InputLabel>
                    <Input
                      id="standard-adornment-amount"
                      startAdornment={
                        <InputAdornment position="start">
                          <LinkIcon />
                        </InputAdornment>
                      }
                      inputProps={register("link")}
                      onBlur={(event) => {
                        setLink(event?.target?.value);
                      }}
                    />

                    <ButtonGroup
                      className={s.buttonGroup}
                      disableElevation
                      variant="contained"
                      aria-label="Disabled elevation buttons"
                      fullWidth
                    >
                      <Button onClick={handleClear}>Clear</Button>
                      <Button
                        onClick={() => {
                          popupState.close();
                          handleClose();
                        }}
                      >
                        Confirm
                      </Button>
                    </ButtonGroup>
                  </FormControl>
                </Box>
              </Modal>
            </Menu>
          </>
        )}
      </PopupState>
      <Button
        fullWidth variant="outlined" type="submit" endIcon={<SendIcon />} disabled={isSubmittedFile || isSubmittedLink}
      >
        Send
      </Button>
    </form>
  );
}
