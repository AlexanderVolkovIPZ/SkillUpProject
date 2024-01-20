import { useState } from "react";
import { useParams } from "react-router-dom";
import { Helmet } from "react-helmet";

import { useCourseQuery, useUpdateCourseMutation } from "../../../store/courseApi";
import { useGetTasksByCourseQuery } from "../../../store/taksApi";

import {
  Box,
  Card,
  CardContent,
  CardMedia,
  IconButton,
  Menu,
  MenuItem,
  Typography
} from "@mui/material";
import ListItemText from "@mui/material/ListItemText";
import Divider from "@mui/material/Divider";
import MoreVertIcon from "@mui/icons-material/MoreVert";
import ReplayIcon from "@mui/icons-material/Replay";
import List from "@mui/material/List";
import TaskItem from "../../../components/taskItem/user/TaskItem";
import CircularProgressModal from "../../../components/circularProgressModal/CircularProgressModal";
import ListItemIcon from "@mui/material/ListItemIcon";
import { Cloud, ContentCopy, ContentPaste } from "@mui/icons-material";
import s from "./StripeSubPage.module.css";

export default function StripeSubPage () {
  const { id } = useParams();
  const [anchorEl, setAnchorEl] = useState(null);
  const [selectedIndex, setSelectedIndex] = useState(0);
  const { data: courseData, isLoading: isLoadingCourse, refetch } = useCourseQuery(id);
  const { data: taskData, isLoading: isLoadingTask } = useGetTasksByCourseQuery(id);
  const [updateCourse] = useUpdateCourseMutation();
  const open = Boolean(anchorEl);

  const onCopy = () => {
    if (courseData?.code) {
      navigator.clipboard.writeText(courseData?.code);
    }
  };

  const onRegenerate = async () => {
    try {
      const fullUuid = crypto.randomUUID();
      const shortUuid = fullUuid.split("-").join("").substr(0, 16);
      await updateCourse({ id, code: shortUuid });
      await refetch(id);
    } catch (err) {
      console.error("Error updating data:", err);
    }
  };

  const handleClick = (event) => {
    setAnchorEl(event.currentTarget);
  };

  const handleClose = () => {
    setAnchorEl(null);
  };

  const handleListItemClick = (event, index) => {
    setSelectedIndex(index);
  };

  return <>
    {(isLoadingCourse || isLoadingTask) ? <CircularProgressModal /> :
      <div>
        <Helmet>
          <title>Stripe</title>
        </Helmet>
        <Card className={s.card}>
          <CardMedia
            component="img"
            alt="Paella dish"
            src={`https://picsum.photos/900/200`}
            className={s.cardMedia}
          />
          <CardContent className={s.cardContent}>
            <Box className={s.leftSide}>
              <div className={s.leftSideTop}>
                <Typography variant="subtitle1">
                  Course code
                </Typography>
                <div className={s.menu}>
                  <IconButton
                    aria-label="more"
                    id="long-button"
                    aria-controls={open ? "long-menu" : undefined}
                    aria-expanded={open ? "true" : undefined}
                    aria-haspopup="true"
                    onClick={handleClick}
                  >
                    <MoreVertIcon />
                  </IconButton>
                  <Menu
                    id="basic-menu"
                    anchorEl={anchorEl}
                    open={open}
                    onClose={handleClose}
                    MenuListProps={{
                      "aria-labelledby": "basic-button"
                    }}
                  >
                    <MenuItem
                      onClick={
                        () => {
                          handleClose();
                          onCopy();
                        }
                      }
                    >
                      <ListItemIcon>
                        <ContentCopy fontSize="small" />
                      </ListItemIcon>
                      <ListItemText>Copy course code</ListItemText>
                    </MenuItem>
                    <MenuItem
                      onClick={
                        () => {
                          handleClose();
                          onRegenerate();
                        }
                      }
                    >
                      <ListItemIcon>
                        <ReplayIcon fontSize="small" />
                      </ListItemIcon>
                      <ListItemText>Regenerate</ListItemText>
                    </MenuItem>
                  </Menu>
                </div>
              </div>
              <Typography className={s.leftSideBottom}>
                {courseData?.code}
              </Typography>
            </Box>
            <Box className={s.rightSide}>
              <List component="nav" aria-label="main mailbox folders" className={s.listTask}>
                {taskData?.map(({ name, createdAt, id }, index) => {
                  return <TaskItem
                    name={name}
                    createdAt={createdAt}
                    id={id} index={index}
                    handleListItemClick={handleListItemClick}
                    selectedIndex={selectedIndex}
                  />;
                })}
              </List>
            </Box>
          </CardContent>
        </Card>
      </div>
    }
  </>;
}