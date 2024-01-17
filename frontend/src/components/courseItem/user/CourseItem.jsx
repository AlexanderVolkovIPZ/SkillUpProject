import { Button, Card, CardActions, CardContent, CardMedia, Typography } from "@mui/material";
import s from "./CourseItem.module.css";

export default function CourseItem ({ id, name, title, description }) {

  return <Card className={s.card}>
    <div className={s.wrapper}>
      <CardMedia
        component="img"
        sx={{ height: 140 }}
        src={`https://picsum.photos/345/160?random=${Math.random()}`}
        className={s.img}
      />
      <Typography gutterBottom variant="h5" component="div" className={s.name}>
        {name}
      </Typography>
      <Typography variant="subtitle1" gutterBottom className={s.title}>
        {title}
      </Typography>
    </div>
    <div className={s.wrapperWhiteContent}>
      <Typography variant="body2" color="text.secondary" className={s.typographyContent} dangerouslySetInnerHTML={{ __html: description }} />
      <CardActions>
        <Button size="small" href={`/course/${id}`}>Learn More</Button>
      </CardActions>
    </div>
  </Card>;
}