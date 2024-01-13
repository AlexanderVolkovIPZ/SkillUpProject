import { Helmet } from "react-helmet";

import LoginForm from "../../components/loginForm/LoginForm";
import { Box, Typography } from "@mui/material";
import s from "./LoginPage.module.css";

export default function LoginPage () {

  return <Box className={s.container}>
    <Helmet>
      <title>Login</title>
    </Helmet>
    <div className={s.contentWrapper}>
      <div className={s.imgWrapper}>
        <img src="/images/man-with-pc.png" alt="" className={s.loginImg} />
      </div>
      <div className={s.loginFormWrapper}>
        <Typography variant="h3">Sign in</Typography>
        <LoginForm />
      </div>
    </div>
  </Box>;
}
