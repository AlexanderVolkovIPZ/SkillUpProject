import React, { useContext, useEffect, useState } from "react";
import { useForm } from "react-hook-form";

import { useCreateMutation } from "../../../store/courseApi";
import { useCreateMutation as useCreateUserMutation } from "../../../store/courseUserApi";
import { createCourseSchema } from "../../../schemesValidation/createCourseSchema";

import { CKEditor } from "@ckeditor/ckeditor5-react";
import ClassicEditor from "@ckeditor/ckeditor5-build-classic";
import { Button, TextField, Typography } from "@mui/material";
import s from "./CreateCourseForm.module.css";

import { zodResolver } from "@hookform/resolvers/zod";
import { AuthContext } from "../../../context/authContext";
import CircularProgressModal from "../../circularProgressModal/CircularProgressModal";

export default function CreateCourseForm ({ setIsCourseSuccessfullyCreated }) {
  const { register, handleSubmit, formState: { errors }, setValue, reset } = useForm({
    resolver: zodResolver(createCourseSchema)
  });
  const [create, { isSuccess, isError, error }] = useCreateMutation();
  const [createUserCourse] = useCreateUserMutation();
  const { userInfo } = useContext(AuthContext);
  const [characterCount, setCharacterCount] = useState(0);
  const [editor, setEditor] = useState(null);
  const [loading, setLoading] = useState(false);

  const handleEditorChange = (event, editor) => {
    setEditor(editor);
    const content = editor.getData();
    setValue("description", content);
    setCharacterCount(content.length);
  };

  const onSubmit = async (data) => {
    setLoading(true);
    try {
      const response = await create(data);
      const courseId = response?.data["@id"]?.split("/")?.pop();
      try {
        await createUserCourse({
          user: `/api/users/${userInfo.id}`,
          course: `/api/courses/${courseId}`,
          isCreator: true
        });
        if (!isError) {
          setIsCourseSuccessfullyCreated(true);
          reset();
          editor?.setData("");
          setCharacterCount(0);
          setTimeout(() => {
            setIsCourseSuccessfullyCreated(false);
          }, 3000);
        }
      } catch (e) {
        console.error("Error submitting form:", error);
      } finally {
        setLoading(false);
      }
    } catch (error) {
      console.error("Error submitting form:", error);
    } finally {
      setLoading(false);
    }
  };

  return (<>
    {loading ? <CircularProgressModal /> :
      <form action="" method="get" className={s.form} onSubmit={handleSubmit(onSubmit)}>
        <TextField
          fullWidth
          id="outlined-basic"
          label="Name"
          variant="outlined"
          name="name"
          inputProps={register("name")}
          error={!!errors.name}
          helperText={errors?.name?.message}
          required={true}
        />
        <TextField
          fullWidth
          id="outlined-basic"
          label="Title"
          variant="outlined"
          name="title"
          inputProps={register("title")}
          error={!!errors.title}
          helperText={errors?.title?.message}
        />

        <div>
          <CKEditor
            editor={ClassicEditor}
            id="description"
            name="description"
            config={{
              placeholder: "Description",
              isRequired: false,
              initialData: " "
            }}
            onChange={handleEditorChange}
          />
          <div className={s.CKEditorInfo}>
            {errors.description ? (
              <Typography variant="caption" display="block" gutterBottom className={s.error}>
                {errors.description.message}
              </Typography>
            ) : <div></div>}
            <Typography variant="caption" display="block" gutterBottom>
              Character Count: {characterCount}/2000
            </Typography>
          </div>
        </div>

        <Button variant="contained" type="submit">
          Create
        </Button>
      </form>}
  </>);
}
