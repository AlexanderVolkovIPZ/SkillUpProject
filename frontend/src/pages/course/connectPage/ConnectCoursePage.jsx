import { Helmet } from "react-helmet";
import ConnectCourseForm from "../../../components/connectCourseForm/user/ConnectCourseForm";
import s from "./ConnectCoursePage.module.css";
import { Typography } from "@mui/material";

export default function ConnectCoursePage () {

  return <div className={s.connectCoursePage}>
    <Helmet>
      <title>Connect course</title>
    </Helmet>
    <div className={s.labelsWrapper}>
      <Typography variant="h5" gutterBottom>
        Course code
      </Typography>
      <Typography variant="subtitle1" gutterBottom>
        Enter the course code (you can find it out from the teacher).
      </Typography>
    </div>
    <ConnectCourseForm />
  </div>
}