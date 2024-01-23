import "./App.css";
import { Route, Routes, useLocation } from "react-router-dom";
import { Suspense, useState } from "react";
import { userRoutes } from "./routes/userRoutes";
import { adminRoutes } from "./routes/adminRoutes";
import { guestRoutes } from "./routes/guestRoutes";
import GuestLayout from "./layouts/guestLayout/GuestLayout";
import UserLayout from "./layouts/userLayout/UserLayout";
import AdminLayout from "./layouts/adminLayout/AdminLayout";
import { getJWTInfo } from "./utils/getJWTInfo";
import { AuthContext } from "./context/authContext";
import CircularProgressModal from "./components/circularProgressModal/CircularProgressModal";
import { LocalizationProvider } from "@mui/x-date-pickers";
import { AdapterDayjs } from "@mui/x-date-pickers/AdapterDayjs";
import dayjs from "dayjs";
import utc from "dayjs/plugin/utc";
import timezone from "dayjs/plugin/timezone";

dayjs.extend(utc);
dayjs.extend(timezone);

function App () {
  dayjs.tz.setDefault("Europe/Kiev");
  const [authenticated, setAuthenticated] = useState(localStorage.getItem("token"));

  const routeRender = () => {
    if (!authenticated) {
      return <Route path="/" element={<GuestLayout />}>
        {guestRoutes.map(({ element, path }, index) => (
          <Route key={path} path={path} element={element} />
        ))}
      </Route>;
    }

    const getJwtInfo = getJWTInfo();
    switch (getJwtInfo?.roles[0]) {
      case "ROLE_USER":
        console.log("ROLE_USER");
        return <Route path="/" element={<UserLayout />}>
          {userRoutes.map(({ element, path }, index) => (
            <Route key={path} path={path} element={element} />
          ))}
        </Route>;
      case "ROLE_ADMIN":
        console.log("ROLE_ADMIN");
        return <Route path="/" element={<AdminLayout />}>
          {adminRoutes.map(({ element, path }, index) => (
            <Route key={path} path={path} element={element} />
          ))}
        </Route>;
      default:
        console.log("Виконується дефолт блок");
        return <Route path="/" element={<GuestLayout />}>
          {guestRoutes.map(({ element, path }, index) => (
            <Route key={path} path={path} element={element} />
          ))}
        </Route>;
    }
  };

  return (
    <LocalizationProvider dateAdapter={AdapterDayjs}>
      <AuthContext.Provider value={{ setAuthenticated, userInfo: getJWTInfo() }}>
        <Suspense fallback={<CircularProgressModal />}>
          <Routes>
            {routeRender()}
          </Routes>
        </Suspense>
      </AuthContext.Provider>
    </LocalizationProvider>
  );
}

export default App;
