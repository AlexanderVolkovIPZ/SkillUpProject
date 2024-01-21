import { Link } from "react-router-dom";
import { Typography } from "@mui/material";
import ListItemButton from "@mui/material/ListItemButton";
import ListItem from "@mui/material/ListItem";
import s from "./TaskItem.module.css";

export default function TaskItem ({ name, createdAt, id, handleListItemClick, index, selectedIndex }) {

  return (
    <ListItemButton
      selected={selectedIndex === index}
      onClick={(event) => handleListItemClick(event, index)}
      className={s.listItemButton}
      component={Link}
      to={`/task/${id}`}
    >
      <ListItem className={s.listItem}>
        <div>
          <Typography variant="title1" display="block" className={s.name}>
            {name}
          </Typography>
        </div>
        <div>
          <Typography variant="overline" display="block" className={s.date}>
            {createdAt?.date.split(".")[0]}
          </Typography>
        </div>
      </ListItem>
    </ListItemButton>
  );
}
