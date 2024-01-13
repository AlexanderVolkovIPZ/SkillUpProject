import React from "react";
import { useState } from "react";
import { useForm } from "react-hook-form";

import {
  Button,
  FormControl,
  IconButton,
  Input,
  InputAdornment,
  InputLabel,
  TextField
} from "@mui/material";
import VisibilityOff from "@mui/icons-material/VisibilityOff";
import Visibility from "@mui/icons-material/Visibility";

import s from "./RegisterForm.module.css";

import { useRegisterUserMutation } from "../../store/userApi";
import { zodResolver } from "@hookform/resolvers/zod";
import { registerScheme } from "../../schemesValidation/registerScheme";

export default function RegisterForm () {
  const { register, handleSubmit, formState: { errors }, setValue, reset } = useForm({
    resolver: zodResolver(registerScheme)
  });
  const [registerUser, { data, isSuccess, isError, error }] = useRegisterUserMutation();
  const [showPassword1, setShowPassword1] = useState(false);
  const [showPassword2, setShowPassword2] = useState(false);

  const onSubmit = async (data) => {
    try {
      await registerUser(data);
      reset();
    } catch (error) {
      console.log(error);
    }
  };

  const handleClickShowPassword1 = () => setShowPassword1((show) => !show);
  const handleClickShowPassword2 = () => setShowPassword2((show) => !show);

  const handleMouseDownPassword1 = (event) => {
    event.preventDefault();
  };
  const handleMouseDownPassword2 = (event) => {
    event.preventDefault();
  };

  return <form action="" className={s.formRegister} onSubmit={handleSubmit(onSubmit)}>
    <TextField
      id="firstName"
      label="Name"
      variant="standard"
      name="firstName"
      inputProps={register("firstName")}
      autoComplete={"off"}
      error={!!errors.firstName}
      helperText={errors?.firstName?.message}
      required={true}
    />

    <TextField
      id="lastNameName"
      label="Surname"
      variant="standard"
      name="lastNameName"
      inputProps={register("lastName")}
      autoComplete={"off"}
      error={!!errors.lastName}
      helperText={errors?.lastName?.message}
      required={true}
    />

    <TextField
      id="email"
      label="Email"
      variant="standard"
      name="email"
      type="email"
      required={true}
      inputProps={register("email")}
      error={!!errors.email}
      helperText={errors?.email?.message}
    />

    <FormControl variant="standard">
      <InputLabel htmlFor="password">Password</InputLabel>
      <Input
        id="password"
        type={showPassword1 ? "text" : "password"}
        name="password"
        {...register("password")}
        autoComplete={"off"}
        required={true}
        endAdornment={
          <InputAdornment position="end">
            <IconButton
              aria-label="toggle password visibility"
              onClick={handleClickShowPassword1}
              onMouseDown={handleMouseDownPassword1}
            >
              {showPassword1 ? <VisibilityOff /> : <Visibility />}
            </IconButton>
          </InputAdornment>
        }
        label="Password"
        error={!!errors.password}
        helperText={errors?.password?.message}
      />
    </FormControl>

    <FormControl variant="standart">
      <InputLabel htmlFor="confirmPassword">Password (confirm)</InputLabel>
      <Input
        id="confirmPassword"
        type={showPassword2 ? "text" : "password"}
        name="confirmPassword"
        {...register("confirmPassword")}
        autoComplete={"off"}
        required={true}
        endAdornment={
          <InputAdornment position="end">
            <IconButton
              aria-label="toggle password visibility"
              onClick={handleClickShowPassword2}
              onMouseDown={handleMouseDownPassword2}
            >
              {showPassword2 ? <VisibilityOff /> : <Visibility />}
            </IconButton>
          </InputAdornment>
        }
        label="Password (confirm)"
        error={!!errors?.confirmPassword}
        helperText={errors?.confirmPassword?.message}
      />
    </FormControl>

    <Button type="submit" variant="contained" size="large">
      Register
    </Button>
  </form>;
}