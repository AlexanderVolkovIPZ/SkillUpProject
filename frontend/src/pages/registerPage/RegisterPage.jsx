import React from "react";
import { Helmet } from "react-helmet";

import RegisterForm from "../../components/registerForm/RegisterForm";

import { Box, Typography } from "@mui/material";
import s from "./RegisterPage.module.css";

export default function RegisterPage () {

  return <Box className={s.container}>
    <Helmet>
      <title>Register</title>
    </Helmet>
    <div>
      <Typography variant="h3">Sign up</Typography>
      <RegisterForm />
    </div>
  </Box>;
}
