import { useEffect, useState } from "react";
import { Helmet } from "react-helmet";
import MarkTable from "../../../components/markTable/user/MarkTable";
import { Alert } from "@mui/material";
import CheckIcon from "@mui/icons-material/Check";
import s from "./MarkSubPage.module.css";

export default function MarkSubPage ({ setValueTab }) {
  const [isSuccessfulUpdateMark, setIsSuccessfulUpdateMark] = useState(false);

  useEffect(() => {
    setValueTab("4");
  }, []);

  return <>
    <Helmet>
      <title>MarkSubPage</title>
    </Helmet>
    {isSuccessfulUpdateMark &&
      <Alert icon={<CheckIcon fontSize="inherit" />} severity="success" className={s.successfullyUpdatedMark}>
        Mark update successfully.
      </Alert>}
    <MarkTable setIsSuccessfulUpdateMark={setIsSuccessfulUpdateMark} />
  </>;
}