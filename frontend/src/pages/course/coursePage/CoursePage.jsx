import { useState } from "react";
import { Link, Route, Routes, useParams } from "react-router-dom";
import StripeSubPage from "../stripeSubPage/StripeSubPage";
import TaskSubPage from "../taskSubPage/TaskSubPage";
import UserSubPage from "../userSubPage/UserSubPage";
import MarkSubPage from "../marksSubPage/MarkSubPage";
import { Box, Tab, Tabs } from "@mui/material";
import s from "./CoursePage.module.css";
import WithCourseOwnershipCheckRoutes from "../../../components/HOC/WithCourseOwnershipCheckRoutes";
import WithCourseOwnershipCheck from "../../../components/HOC/WithCourseOwnershipCheck";

export default function CoursePage () {
  const { id } = useParams();
  const [value, setValue] = useState("1");

  const handleChange = (event, newValue) => {
    setValue(newValue);
  };

  return <div className={s.coursePageWrapper}>
    <Box sx={{ width: "100%" }} className={s.tabBox}>
      <Tabs
        value={value}
        onChange={handleChange}
        textColor="primary"
        indicatorColor="primary"
        aria-label="primary tabs"
      >
        <Tab value="1" label="Stripe" component={Link} to={`/course/${id}`} />
        <WithCourseOwnershipCheck>
          <Tab value="2" label="Tasks" component={Link} to={`/course/${id}/tasks`} />
          <Tab value="3" label="Users" component={Link} to={`/course/${id}/users`} />
          <Tab value="4" label="Marks" component={Link} to={`/course/${id}/marks`} />
        </WithCourseOwnershipCheck>
      </Tabs>
    </Box>
    <div className={s.contentBox}>
      <Routes path={`/courses/${id}`}>
        <Route index element={<StripeSubPage setValueTab={setValue} />} />
        <Route path={""} element={<WithCourseOwnershipCheckRoutes />}>
          <Route path={"tasks"} element={<TaskSubPage setValueTab={setValue} />} />
          <Route path={"users"} element={<UserSubPage setValueTab={setValue} />} />
          <Route path={"marks"} element={<MarkSubPage setValueTab={setValue} />} />
        </Route>
      </Routes>
    </div>
  </div>;
}

