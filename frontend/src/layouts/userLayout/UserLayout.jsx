import { Outlet } from "react-router-dom";
import s from "./UserLayout.module.css";
import Header from "../../components/header/user/Header";
import Footer from "../../components/footer/common/Footer";

export default function UserLayout () {
  return <div className={s.container}>
    <Header />
    <div className={s.outlet}>
      <Outlet />
    </div>
    <Footer />
  </div>;
}