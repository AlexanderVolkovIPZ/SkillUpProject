import { useContext, useState } from "react";
import { useForm } from "react-hook-form";
import { AuthContext } from "../../../context/authContext";
import { useCreateMutation } from "../../../store/courseUserApi";
import { useConnectCourseMutation } from "../../../store/courseApi";

import { connectCourseSchema } from "../../../schemesValidation/connectCourseSchema";
import { zodResolver } from "@hookform/resolvers/zod";

import CircularProgressModal from "../../circularProgressModal/CircularProgressModal";
import { Button, TextField } from "@mui/material";
import s from "./ConnectCourseForm.module.css";

export default function ConnectCourseForm ({ setIsCourseSuccessfullyConnected }) {
  const { register, handleSubmit, formState: { errors }, setValue, reset } = useForm({
    resolver: zodResolver(connectCourseSchema)
  });
  const [createUserCourse] = useCreateMutation();
  const [connectCourse, { isError }] = useConnectCourseMutation();
  const { userInfo } = useContext(AuthContext);
  const [loading, setLoading] = useState(false);

  const onSubmit = async (data) => {
    try {
      await connectCourse({
        code: data?.name,
        body: []
      });

      if (!isError) {
        setIsCourseSuccessfullyConnected(true);
        reset();
        setTimeout(() => {
          setIsCourseSuccessfullyConnected(false);
        }, 3000);
      }

    } catch (error) {
      console.log(error);
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