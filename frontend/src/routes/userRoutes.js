import { guestRoutes } from "./guestRoutes";
import { lazy } from "react";
const CreateCoursePage = lazy(()=>import("../pages/course/createPage/CreateCoursePage"))

export const userRoutes = [
  {
    path: "/course/create",
    element: <CreateCoursePage/>
  }
].concat(guestRoutes);
