import { guestRoutes } from "./guestRoutes";
import { lazy } from "react";

const CreateCoursePage = lazy(() => import("../pages/course/createPage/CreateCoursePage"));
const ConnectCoursePage = lazy(() => import("../pages/course/connectPage/ConnectCoursePage"));

export const userRoutes = [
  {
    path: "/course/create",
    element: <CreateCoursePage />
  },
  {
    path: "/course/connect",
    element: <ConnectCoursePage />
  }
].concat(guestRoutes);
