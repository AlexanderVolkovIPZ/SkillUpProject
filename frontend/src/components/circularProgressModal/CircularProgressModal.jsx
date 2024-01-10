import React from "react";
import { CircularProgress } from "@mui/material";
import s from "./CircularProgressModal.module.css"

export default function CircularProgressModal(){

  return (
    <div className={s.container}>
      <CircularProgress  size={100}/>
    </div>
  );
}