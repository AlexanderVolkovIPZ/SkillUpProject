import { Helmet } from "react-helmet";
import CourseList from "../../../components/courseList/user/CourseList";

export default function MainPage(){
  return <>
    <Helmet>
      <title>Skill Up</title>
    </Helmet>
    <CourseList/>
  </>
}