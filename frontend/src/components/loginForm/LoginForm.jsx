import React, { useContext, useEffect, useState } from "react";
import { useNavigate } from "react-router-dom";
import { useForm } from "react-hook-form";

import { Button, FormControl, IconButton, Input, InputAdornment, InputLabel, TextField } from "@mui/material";
import Visibility from "@mui/icons-material/Visibility";
import VisibilityOff from "@mui/icons-material/VisibilityOff";

import { zodResolver } from "@hookform/resolvers/zod";
import { loginScheme } from "../../schemesValidation/loginScheme";
import { useLoginMutation } from "../../store/userApi";

import s from "./LoginForm.module.css";
import { responseStatus } from "../../utils/consts";
import { AuthContext } from "../../context/authContext";

export default function LoginForm () {

  const { register, handleSubmit, formState: { errors }, setValue, reset } = useForm({
    resolver: zodResolver(loginScheme)
  });
  const [login, { data, isSuccess, isError, error }] = useLoginMutation();
  const [showPassword, setShowPassword] = useState(false);
  const navigate = useNavigate();
  const [loading, setLoading] = useState(false);
  const { setAuthenticated } = useContext(AuthContext);

  const handleClickShowPassword = () => setShowPassword((show) => !show);
  const handleMouseDownPassword = (event) => {
    event.preventDefault();
  };

  const onSubmit = async ({ username, password }) => {
    try {
      setLoading(true);
      const { data } = await login({ username, password });
      if (data?.meta?.response?.status === responseStatus.HTTP_OK) {
        setAuthenticated(true);
        navigate("/");
      }
    } catch (error) {
      console.log(error);
    } finally {
      setLoading(false);
    }
  };

  return <form action="" className={s.formLogin} onSubmit={handleSubmit(onSubmit)}>
    <TextField
      id="username"
      label="Email"
      variant="standard"
      name="username"
      type="email"
      required={true}
      inputProps={register("username")}
      error={!!errors.email}
      helperText={errors?.email?.message}
    />
    <FormControl variant="standard">
      <InputLabel htmlFor="password">Password</InputLabel>
      <Input
        id="password"
        type={showPassword ? "text" : "password"}
        name="password"
        {...register("password")}
        autoComplete={"off"}
        required={true}
        endAdornment={<InputAdornment position="end">
          <IconButton
            aria-label="toggle password visibility"
            onClick={handleClickShowPassword}
            onMouseDown={handleMouseDownPassword}
            edge="end"
          >
            {showPassword ? <VisibilityOff /> : <Visibility />}
          </IconButton>
        </InputAdornment>}
        label="Password"
        error={!!errors.password}
        helperText={errors?.password?.message}
      />
    </FormControl>
    <Button type="submit" variant="contained" size="large">
      Sign in
    </Button>
  </form>;
}