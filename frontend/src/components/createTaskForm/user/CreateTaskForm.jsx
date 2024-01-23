import { useEffect, useState } from "react";
import { useForm } from "react-hook-form";
import { useCreateMutation } from "../../../store/taskApi";
import { zodResolver } from "@hookform/resolvers/zod";
import { createTaskSchema } from "../../../schemesValidation/createTaskSchema";

import { Button, TextField, Typography } from "@mui/material";
import { CKEditor } from "@ckeditor/ckeditor5-react";
import ClassicEditor from "@ckeditor/ckeditor5-build-classic";
import { DateTimePicker } from "@mui/x-date-pickers/DateTimePicker";
import s from "./CreateTaskForm.module.css";

export default function CreateTaskForm ({ courseId, setLoading }) {
  const [characterCount, setCharacterCount] = useState(0);
  const [editor, setEditor] = useState(null);
  const [create, { data, isSuccess, isError, error }] = useCreateMutation();
  const { register, handleSubmit, formState: { errors }, setValue, reset } = useForm({
    resolver: zodResolver(createTaskSchema)
  });

  const onSubmit = async (data) => {
    try {
      setLoading(true);
      const formData = new FormData();
      formData.append("courseId", courseId);
      Array.from(data.file).forEach((file, index) => {
        formData.append(`file_${index}`, file);
      });
      formData.append("name", data.name);
      formData.append("mark", data.mark);
      formData.append("description", data.description);
      formData.append("date", data.date);
      await create(formData);
      reset();
      editor?.setData("");
      setCharacterCount(0);
    } catch (error) {
      console.log(error);
    } finally {
      setLoading(false);
    }
  };

  const handleEditorChange = (event, editor) => {
    setEditor(editor);
    setValue("description", editor.getData());
    setCharacterCount(editor.getData().length);
  };

  return <form action="" method="get" className={s.form} onSubmit={handleSubmit(onSubmit)} encType="multipart/form-data">
    <TextField
      fullWidth
      id="outlined-basic"
      label="Name"
      variant="outlined"
      name="name"
      required={true}
      inputProps={register("name")}
      error={!!errors.name}
      helperText={errors?.name?.message}
    />
    <div>
      <CKEditor
        editor={ClassicEditor}
        id="description"
        name="description"
        config={{
          placeholder: "Description",
          isRequired: false,
          initialData: "",
          ...register("description")
        }}
        onChange={handleEditorChange}
      />
      <div className={s.CKEditorInfo}>
        {errors?.description ? (
          <Typography variant="caption" display="block" gutterBottom className={`${s.error} ${s.editorError}`}>
            {errors?.description?.message}
          </Typography>
        ) : <div></div>}
        <Typography variant="caption" display="block" gutterBottom>
          Character Count: {characterCount}/10000
        </Typography>
      </div>
    </div>
    <DateTimePicker
      label="Task finish date"
      name="date"
      inputProps={register("date")}
      onChange={(value) => {
        const dayOfYearDate = new Date();
        dayOfYearDate.setFullYear(value["$y"]);
        dayOfYearDate.setMonth(value["$M"]);
        dayOfYearDate.setDate(value["$D"]);
        dayOfYearDate.setHours(value["$H"]);
        dayOfYearDate.setMinutes(value["$m"]);
        dayOfYearDate.setSeconds(value["$s"]);
        return setValue("date", dayOfYearDate);
      }}
      slotProps={{
        textField: {
          error: !!errors.date,
          helperText: errors?.date?.message
        }
      }}
    />
    <TextField
      fullWidth
      id="outlined-basic"
      label="Max mark"
      variant="outlined"
      name="mark"
      type="number"
      required={true}
      inputProps={register("mark", { valueAsNumber: true })}
      error={!!errors.mark}
      helperText={errors?.mark?.message}
    />
    <input
      accept="*"
      hidden
      id="raised-button-file"
      type="file"
      name="file"
      {...register("file")}
    />
    <label htmlFor="raised-button-file">
      <Button variant="contained" color={"success"} component="span">
        Upload task file
      </Button>
    </label>
    <div className={s.error}>
      {errors?.file?.message}
    </div>
    <Button variant="contained" type="submit">
      Create
    </Button>
  </form>;
}


