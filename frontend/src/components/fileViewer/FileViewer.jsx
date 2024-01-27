import ListItem from "@mui/material/ListItem";
import ListItemIcon from "@mui/material/ListItemIcon";
import ListItemText from "@mui/material/ListItemText";
import FileDownloadIcon from '@mui/icons-material/FileDownload';
import s from "./FileViewer.module.css";

const FileViewer = ({ fileName }) => {
  const fileUrl = `/api/task-file/${encodeURIComponent(fileName)}`;

  const handleDownload = () => {
    const userConfirmed = window.confirm("Are you sure you want to download this file?");
    if (userConfirmed) {
      window.open(fileUrl, "_blank");
    }
  };

  return (
    <div>
      {fileName && (
        <ListItem onClick={handleDownload} className={s.listItem}>
          <ListItemIcon>
            <FileDownloadIcon/>
          </ListItemIcon>
          <ListItemText
            primary={fileName}
          />
        </ListItem>
      )}
    </div>
  );
};

export default FileViewer;
