import React from "react";
import { Alert } from "@mui/material";

export default function Error({message, number}){
  return <Alert severity="error">Error {number}! {message}</Alert>
}