import { useContext, useState } from "react";
import { useForm } from "react-hook-form";
import { AuthContext } from "../../../context/authContext";
import { useCreateMutation } from "../../../store/courseUserApi";

import { connectCourseSchema } from "../../../schemesValidation/connectCourseSchema";
import { zodResolver } from "@hookform/resolvers/zod";

import CircularProgressModal from "../../circularProgressModal/CircularProgressModal";
import { Button, TextField } from "@mui/material";
import s from "./ConnectCourseForm.module.css";

export default function ConnectCourseForm () {
  const { register, handleSubmit, formState: { errors }, setValue, reset } = useForm({
    resolver: zodResolver(connectCourseSchema)
  });
  const [createUserCourse] = useCreateMutation();
  const { userInfo } = useContext(AuthContext);
  const [loading, setLoading] = useState(false);

  const onSubmit = async (data) => {
    setLoading(true);
    try {
      await createUserCourse({
        user: `/api/users/${userInfo.id}`,
        course: `/api/courses/${data?.name}`,
        isCreator: false
      });
      reset();
    } catch (error) {
      console.log(error);
    } finally {
      setLoading(false);
    }
  };

  return <>
    {loading ? <CircularProgressModal /> : <form className={s.form} method="POST" onSubmit={handleSubmit(onSubmit)}>
      <TextField
        fullWidth
        id="outlined-basic"
        label="Course code"
        variant="outlined"
        name="name"
        inputProps={register("name")}
        error={!!errors.name}
        helperText={errors?.name?.message}
        required={true}
      />
      <Button variant="contained" type="submit">
        Connect
      </Button>
    </form>}
  </>;
}