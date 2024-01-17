import { useContext, useState } from "react";
import { Link, useNavigate } from "react-router-dom";
import { AuthContext } from "../../../context/authContext";

import {
  AppBar,
  Badge,
  Box,
  IconButton,
  MenuItem,
  Menu,
  Toolbar,
  Typography
} from "@mui/material";

import MenuIcon from "@mui/icons-material/Menu";
import MailIcon from "@mui/icons-material/Mail";
import NotificationsIcon from "@mui/icons-material/Notifications";
import MoreIcon from "@mui/icons-material/MoreVert";
import AccountCircle from "@mui/icons-material/AccountCircle";
import AddIcon from "@mui/icons-material/Add";
import s from "./Header.module.css";

export default function Header () {

  const { setAuthenticated } = useContext(AuthContext);
  const [anchorEl1, setAnchorEl1] = useState(null);
  const [anchorEl2, setAnchorEl2] = useState(null);
  const [mobileMoreAnchorEl, setMobileMoreAnchorEl] = useState(null);
  const navigate = useNavigate();

  const onHandleExit = () => {
    localStorage.removeItem("token");
    setAuthenticated(false);
    navigate("/login");
  };

  const isMenuOpen1 = Boolean(anchorEl1);
  const isMenuOpen2 = Boolean(anchorEl2);
  const isMobileMenuOpen = Boolean(mobileMoreAnchorEl);

  const handleProfileMenuOpen1 = (event) => {
    setAnchorEl1(event.currentTarget);
  };

  const handleProfileMenuOpen2 = (event) => {
    setAnchorEl2(event.currentTarget);
  };

  const handleMobileMenuClose = () => {
    setMobileMoreAnchorEl(null);
  };
  const handleMenuClose1 = () => {
    setAnchorEl1(null);
    handleMobileMenuClose();
  };
  const handleMenuClose2 = () => {
    setAnchorEl2(null);
    handleMobileMenuClose();
  };

  const handleMobileMenuOpen = (event) => {
    setMobileMoreAnchorEl(event.currentTarget);
  };

  const menuId1 = "primary-search-actions-menu";
  const renderMenu1 = (
    <Menu
      anchorEl={anchorEl1}
      anchorOrigin={{
        vertical: "top",
        horizontal: "right"
      }}
      id={menuId1}
      keepMounted
      transformOrigin={{
        vertical: "top",
        horizontal: "right"
      }}
      open={isMenuOpen1}
      onClose={handleMenuClose1}
    >
      <Link to="/course/create" className={s.link}>
        <MenuItem onClick={handleMenuClose1}>
          Create course
        </MenuItem>
      </Link>

      <Link to="/course/connect" className={s.link}>
        <MenuItem onClick={handleMenuClose1}>
          Connect to course
        </MenuItem>
      </Link>
    </Menu>
  );

  const menuId2 = "primary-search-account-menu";
  const renderMenu2 = (
    <Menu
      anchorEl={anchorEl2}
      anchorOrigin={{
        vertical: "top",
        horizontal: "right"
      }}
      id={menuId2}
      keepMounted
      transformOrigin={{
        vertical: "top",
        horizontal: "right"
      }}
      open={isMenuOpen2}
      onClose={handleMenuClose2}
    >
      <MenuItem onClick={handleMenuClose2}>Profile</MenuItem>
      <MenuItem onClick={handleMenuClose2}>My account</MenuItem>
      <MenuItem
        onClick={() => {
          handleMenuClose2();
          onHandleExit();
        }}
      >Exit</MenuItem>
    </Menu>
  );

  const mobileMenuId = "primary-search-account-menu-mobile";
  const renderMobileMenu = (
    <Menu
      anchorEl={mobileMoreAnchorEl}
      anchorOrigin={{
        vertical: "top",
        horizontal: "right"
      }}
      id={mobileMenuId}
      keepMounted
      transformOrigin={{
        vertical: "top",
        horizontal: "right"
      }}
      open={isMobileMenuOpen}
      onClose={handleMobileMenuClose}
    >
      <MenuItem>
        <IconButton size="large" aria-label="show 4 new mails" color="inherit">
          <Badge badgeContent={4} color="error">
            <MailIcon />
          </Badge>
        </IconButton>
        <p>Messages</p>
      </MenuItem>
      <MenuItem>
        <IconButton
          size="large"
          aria-label="show 17 new notifications"
          color="inherit"
        >
          <Badge badgeContent={17} color="error">
            <NotificationsIcon />
          </Badge>
        </IconButton>
        <p>Notifications</p>
      </MenuItem>

      <MenuItem onClick={handleProfileMenuOpen1}>
        <IconButton
          size="large"
          aria-label="actions current user"
          aria-controls={menuId1}
          aria-haspopup="true"
          color="inherit"
        >
          <AddIcon />
        </IconButton>
        <p>Actions</p>
      </MenuItem>

      <MenuItem onClick={handleProfileMenuOpen2}>
        <IconButton
          size="large"
          aria-label="account of current user"
          aria-controls={menuId2}
          aria-haspopup="true"
          color="inherit"
        >
          <AccountCircle />
        </IconButton>
        <p>Profile</p>
      </MenuItem>
    </Menu>
  );

  return (
    <Box style={{ position: "sticky", top: 0, zIndex: 1 }}>
      <AppBar position="static">
        <div className={s.toolbarWrapper}>
          <Toolbar>
            <IconButton
              size="large"
              edge="start"
              color="inherit"
              aria-label="open drawer"
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
              USER
            </Typography>

            <Box sx={{ flexGrow: 1 }} />
            <Box sx={{ display: { xs: "none", md: "flex" } }}>
              <IconButton size="large" aria-label="show 4 new mails" color="inherit">
                <Badge badgeContent={4} color="error">
                  <MailIcon />
                </Badge>
              </IconButton>

              <IconButton
                size="large"
                aria-label="show 17 new notifications"
                color="inherit"
              >
                <Badge badgeContent={17} color="error">
                  <NotificationsIcon />
                </Badge>
              </IconButton>

              <IconButton
                size="large"
                edge="end"
                aria-label="actions of current user"
                aria-controls={menuId1}
                aria-haspopup="true"
                onClick={handleProfileMenuOpen1}
                color="inherit"
              >
                <AddIcon />
              </IconButton>

              <IconButton
                size="large"
                edge="end"
                aria-label="account of current user"
                aria-controls={menuId2}
                aria-haspopup="true"
                onClick={handleProfileMenuOpen2}
                color="inherit"
              >
                <AccountCircle />
              </IconButton>
            </Box>

            <Box sx={{ display: { xs: "flex", md: "none" } }}>
              <IconButton
                size="large"
                aria-label="show more"
                aria-controls={mobileMenuId}
                aria-haspopup="true"
                onClick={handleMobileMenuOpen}
                color="inherit"
              >
                <MoreIcon />
              </IconButton>
            </Box>
          </Toolbar>
        </div>
      </AppBar>
      {renderMobileMenu}
      {renderMenu1}
      {renderMenu2}
    </Box>
  );
}
