import { useEffect, useState } from "react";
import { Link, useLocation } from "react-router-dom";

import {
  AppBar,
  Box,
  IconButton,
  Toolbar,
  Typography, Button
} from "@mui/material";

import MenuIcon from "@mui/icons-material/Menu";
import s from "./Header.module.css";

export default function Header () {

  const [signIn, setSignIn] = useState(false);
  const [signUp, setSignUp] = useState(false);
  const location = useLocation();

  useEffect(() => {
    switch (location.pathname) {
      case "/login":
        setSignIn(false);
        setSignUp(true);
        break;
      case "/registration":
        setSignIn(true);
        setSignUp(false);
        break;
      default:
        setSignIn(true);
        setSignUp(true);
    }
  }, [location.pathname]);

  return (<Box>
      <AppBar position="static">
        <div className={s.toolbarWrapper}>
          <Toolbar>
            <IconButton
              size="large"
              edge="start"
              color="inherit"
              aria-label="menu"
              sx={{ mr: 2 }}
            >
              <MenuIcon />
            </IconButton>
            <Typography
              className={s.siteName}
              component={Link}
              to={"/"}
              variant="h6"
              sx={{ flexGrow: 1 }}
            >
              GUEST
            </Typography>
            {signIn && signUp ? <>
              <Button component={Link} to={"login"} color="inherit">
                {"Sign In"}
              </Button>
              <Button component={Link} to={"registration"} color="inherit">
                {"Sign Up"}
              </Button>
            </> : signIn ? <Button component={Link} to={"login"} color="inherit">
              {"Sign In"}
            </Button> : <Button component={Link} to={"registration"} color="inherit">
              {"Sign Up"}
            </Button>
            }
          </Toolbar>
        </div>
      </AppBar>
    </Box>
  );
}