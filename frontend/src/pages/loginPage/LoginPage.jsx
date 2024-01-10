import React from "react";
import { Helmet } from "react-helmet";

import LoginForm from "../../components/loginForm/LoginForm";

import { Box } from "@mui/material";

import s from "./LoginPage.module.css";

export default function LoginPage () {

  return <Box className={s.container}>
    <Helmet>
      <title>Login</title>
    </Helmet>
    <div className={s.loginFormWrapper}>
      <LoginForm />
    </div>
  </Box>;
}
