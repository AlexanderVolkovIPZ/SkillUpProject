import "./App.css";
import { Route, Routes } from "react-router-dom";
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


function App () {
  const [authenticated, setAuthenticated] = useState(localStorage.getItem("token"));

  const routeRender = () => {

    if (!authenticated) {
      console.log("Not auth!");
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
    <AuthContext.Provider value={{ setAuthenticated }}>
      <Suspense fallback={<CircularProgressModal/>}>
        <Routes>
          {routeRender()}
        </Routes>
      </Suspense>
    </AuthContext.Provider>
  );
}

export default App;
