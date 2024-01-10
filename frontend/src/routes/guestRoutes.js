import React, { lazy } from "react";

const RegisterPage = lazy(() => import("../pages/registerPage/RegisterPage"));
const LoginPage = lazy(() => import("../pages/loginPage/LoginPage"));
const Error = lazy(() => import("../components/error/Error"));

export const guestRoutes = [{
  path: "/login",
  element: <LoginPage />
}, {
  path: "/registration",
  element: <RegisterPage />
}, {
  path: "*",
  element: <Error message={"Page not found!"} number={404} />
}];
