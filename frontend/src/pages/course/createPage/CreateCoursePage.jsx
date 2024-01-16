import CreateCourseForm from "../../../components/createCourseForm/user/CreateCourseForm";
import { Helmet } from "react-helmet";
import s from "./CreateCoursePage.module.css"

export default function CreateCoursePage(){

  return <>
    <Helmet>
      <title>Create course</title>
    </Helmet>
    <CreateCourseForm />
  </>
}