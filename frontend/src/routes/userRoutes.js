import { guestRoutes } from "./guestRoutes";
import { lazy } from "react";

const CreateCoursePage = lazy(() => import("../pages/course/createPage/CreateCoursePage"));
const ConnectCoursePage = lazy(() => import("../pages/course/connectPage/ConnectCoursePage"));
const MainPage = lazy(() => import("../pages/mainPage/user/MainPage"));
const CoursePage = lazy(() => import("../pages/course/coursePage/CoursePage"));

export const userRoutes = [
  {
    path: "/course/create",
    element: <CreateCoursePage />
  },
  {
    path: "/course/connect",
    element: <ConnectCoursePage />
  },
  {
    path: "/",
    element: <MainPage />
  },
  {
    path: "/course/:id/*",
    element: <CoursePage />
  }
].concat(guestRoutes);
