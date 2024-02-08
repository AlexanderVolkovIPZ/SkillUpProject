import CreateCourseForm from "../../../components/createCourseForm/user/CreateCourseForm";
import { Helmet } from "react-helmet";
import s from "./CreateCoursePage.module.css";
import { useState } from "react";
import { Alert } from "@mui/material";
import CheckIcon from "@mui/icons-material/Check";

export default function CreateCoursePage () {

  const [isCourseSuccessfullyCreated, setIsCourseSuccessfullyCreated] = useState(false);

  return <>
    <Helmet>
      <title>Create course</title>
    </Helmet>
    {isCourseSuccessfullyCreated && <Alert
      className={s.taskCourseCreatedSuccessfullyAlert} icon={<CheckIcon fontSize="inherit" />} severity="success"
    >
      New course created successfully!
    </Alert>}
    <CreateCourseForm setIsCourseSuccessfullyCreated={setIsCourseSuccessfullyCreated} />
  </>
}