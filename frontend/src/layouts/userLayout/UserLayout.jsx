import { Outlet } from "react-router-dom";
import s from "./UserLayout.module.css";
import Header from "../../components/header/user/Header";
import Footer from "../../components/footer/common/Footer";
import TemporaryDrawer from "../../components/drawer/user/Drawer";


export default function UserLayout () {
  return <div className={s.container}>
    <Header />
    <div className={s.wrapper}>
      <TemporaryDrawer/>
      <div className={s.outlet}>
        <Outlet />
      </div>
    </div>
    <Footer />
  </div>;
}