import { Helmet } from "react-helmet";
import ConnectCourseForm from "../../../components/connectCourseForm/user/ConnectCourseForm";
import { Typography } from "@mui/material";
import s from "./ConnectCoursePage.module.css";

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