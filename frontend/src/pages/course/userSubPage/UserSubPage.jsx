import { Helmet } from "react-helmet";
import { useEffect, useState } from "react";
import { useParams } from "react-router-dom";
import { useGetUserInfoByCourseQuery } from "../../../store/userApi";
import { useDeleteTaskUserMutation } from "../../../store/taskUserApi";
import { useDeleteCourseUserMutation } from "../../../store/courseUserApi";
import DeleteUserDialog from "../../../components/deleteUserCourseDialog/user/DeleteUserDialog";
import { Alert, Avatar, Grid, ListItemAvatar } from "@mui/material";
import List from "@mui/material/List";
import ListItem from "@mui/material/ListItem";
import ListItemText from "@mui/material/ListItemText";
import { styled } from "@mui/material/styles";
import { deepOrange } from "@mui/material/colors";
import CheckIcon from "@mui/icons-material/Check";
import s from "./UserSubPage.module.css";

const Demo = styled("div")(({ theme }) => ({
  backgroundColor: theme.palette.background.paper
}));

export default function UserSubPage ({ setValueTab }) {
  const [dense, setDense] = useState(false);
  const [secondary, setSecondary] = useState(false);
  const [isUserDeletedSuccessfully, setIsUserDeletedSuccessfully] = useState(false);
  const { id } = useParams();
  const { data: dataUserInfo, isLoading: isUserInfoLoading, refetch } = useGetUserInfoByCourseQuery(id);
  const [deleteTaskUser, { isSuccess: isSuccessDeleteTaskUser }] = useDeleteTaskUserMutation();
  const [deleteCourseUser] = useDeleteCourseUserMutation();
  useEffect(() => {
    setValueTab("3");
  }, []);

  const handleRemoveUser = async (email) => {
    console.log(dataUserInfo?.filter((item) => email === item.email && item.taskUserId !== null));
    try {
      dataUserInfo?.filter((item) => email === item.email && item.taskUserId !== null)?.map(async ({ taskUserId }) => {
        await deleteTaskUser(taskUserId);
      });

    } catch (err1) {
      console.log(err1);
    }

    try {
      dataUserInfo?.filter((item, index, self) => self.findIndex(o => o.email === item.email) === index)?.map(async ({ courseUserId }) => {
        await deleteCourseUser(courseUserId);
      });
      setIsUserDeletedSuccessfully(true);
      setTimeout(() => {
        setIsUserDeletedSuccessfully(false);
      }, 3000);
      refetch(id);
    } catch (err2) {
      console.log(err2);
    }
  };

  return <div>
    <Helmet>
      <title>UserSubPage</title>
    </Helmet>
    {isUserDeletedSuccessfully &&
      <Alert className={s.userDeleteSuccessfullyAlert} icon={<CheckIcon fontSize="inherit" />} severity="success">
        User deleted successfully!
      </Alert>}
    <Grid className={s.contentWrapper}>
      <Demo>
        <List dense={dense}>
          {dataUserInfo?.filter((obj, index, self) => self.findIndex(o => o.email === obj.email) === index)?.map(({
            firstName,
            lastName,
            email
          }) => <ListItem
            secondaryAction={
              <DeleteUserDialog handleAgreeDelete={handleRemoveUser} userFullName={`${firstName} ${lastName}`} email={email} />
            }
            className={s.listItem}
          >
            <ListItemAvatar>
              <Avatar sx={{ bgcolor: deepOrange[500] }}>
                {lastName[0]}
              </Avatar>
            </ListItemAvatar>
            <ListItemText
              primary={`${firstName} ${lastName}`}
              secondary={secondary ? "Secondary text" : null}
            />
          </ListItem>)}
        </List>
      </Demo>
    </Grid>

  </div>;
}