import { useContext, useEffect, useState } from "react";
import { useForm } from "react-hook-form";
import { zodResolver } from "@hookform/resolvers/zod";
import { updateTaskSchema } from "../../../schemesValidation/updateTaskSchema";
import { useUpdateTaskMutation } from "../../../store/taskApi";
import { TaskDataContext } from "../../../context/taskDataContext";
import dayjs from "dayjs";
import { Button, TextField, Typography } from "@mui/material";
import { CKEditor } from "@ckeditor/ckeditor5-react";
import ClassicEditor from "@ckeditor/ckeditor5-build-classic";
import { DateTimePicker } from "@mui/x-date-pickers/DateTimePicker";
import s from "./UpdateTaskForm.module.css";

export default function UpdateTaskForm ({ setIsUpdateDataSuccessfully }) {
  const { taskData } = useContext(TaskDataContext);
  const [characterCount, setCharacterCount] = useState(+taskData?.description.length);
  const [editor, setEditor] = useState(null);
  const [updateTask] = useUpdateTaskMutation();
  const { register, handleSubmit, formState: { errors }, setValue, reset } = useForm({
    resolver: zodResolver(updateTaskSchema)
  });

  useEffect(() => {
    if (taskData && register) {
      setValue("description", taskData?.description || "");
      setValue("date", new Date(dayjs(taskData?.dueDate)));
    }
  }, [taskData]);

  const onSubmit = async (data) => {
    try {
      const formData = new FormData();
      Array.from(data.file).forEach((file, index) => {
        formData.append(`file_${index}`, file);
      });
      formData.append("name", data.name);
      formData.append("mark", data.mark);
      formData.append("description", data.description);
      console.log(data.date);
      formData.append("date", dayjs(data.date).format("YYYY-MM-DDTHH:mm:ssZ"));
      await updateTask({ id: taskData?.id, body: formData });
      setIsUpdateDataSuccessfully(true);
      setTimeout(() => {
        setIsUpdateDataSuccessfully(false);
      }, 2000);
    } catch (error) {
      console.log(error);
    } finally {
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
      defaultValue={taskData?.name}
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
        data={taskData?.description}
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
      defaultValue={dayjs(taskData?.dueDate)}
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
      defaultValue={+taskData?.maxMark}
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
      Update
    </Button>
  </form>;
}


