import React from "react";
import { Box, Typography, Link } from "@mui/material";
import s from "./Footer.module.css";

const Footer = () => {
  return (
    <Box
      component="footer"
      sx={{
        backgroundColor: "#f8f8f8",
        padding: "20px",
        marginTop: "auto",
        textAlign: "center"
      }}
      className={s.footer}
    >
      <Typography variant="body2" color="text.secondary">
        © {new Date().getFullYear()} Your Website Name
      </Typography>
      <Typography variant="body2" color="text.secondary" mt={2}>
        <Link color="inherit" href="#">
          Privacy Policy
        </Link>{" "}
        |{" "}
        <Link color="inherit" href="#">
          Terms of Service
        </Link>{" "}
        |{" "}
        <Link color="inherit" href="#">
          Contact Us
        </Link>
      </Typography>
    </Box>
  );
};

export default Footer;
