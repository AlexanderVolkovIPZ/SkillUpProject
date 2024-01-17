import { useUserCoursesQuery } from "../../../store/courseApi";
import CourseItem from "../../courseItem/user/CourseItem";
import CircularProgressModal from "../../circularProgressModal/CircularProgressModal";
import s from "./CourseList.module.css";

export default function CourseList () {
  const { data, isLoading } = useUserCoursesQuery();

  return <div className={s.wrapper}>
    {isLoading ? <CircularProgressModal /> :
      data?.map(({ id, name, title, description }) => {
        return <CourseItem id={id} name={name} title={title} description={description} key={name} />;
      })
    }
  </div>;
}