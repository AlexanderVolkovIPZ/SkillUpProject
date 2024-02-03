import { Helmet } from "react-helmet";
import ConnectCourseForm from "../../../components/connectCourseForm/user/ConnectCourseForm";
import { Alert, Typography } from "@mui/material";
import s from "./ConnectCoursePage.module.css";
import { useState } from "react";
import CheckIcon from "@mui/icons-material/Check";

export default function ConnectCoursePage () {

  const [isCourseSuccessfullyConnected, setIsCourseSuccessfullyConnected] = useState(false);

  return <div className={s.connectCoursePage}>
    <Helmet>
      <title>Connect course</title>
    </Helmet>
    {isCourseSuccessfullyConnected && <Alert
      className={s.taskCourseConnectSuccessfullyAlert} icon={<CheckIcon fontSize="inherit" />} severity="success"
    >
      You connected successfully!
    </Alert>}
    <div className={s.labelsWrapper}>
      <Typography variant="h5" gutterBottom>
        Course code
      </Typography>
      <Typography variant="subtitle1" gutterBottom>
        Enter the course code (you can find it out from the teacher).
      </Typography>
    </div>
    <ConnectCourseForm setIsCourseSuccessfullyConnected={setIsCourseSuccessfullyConnected} />
  </div>;
}