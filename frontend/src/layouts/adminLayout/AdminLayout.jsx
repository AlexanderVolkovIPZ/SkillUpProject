import { Outlet } from "react-router-dom";
import s from "./AdmineLayout.module.css";
import Header from "../../components/header/admin/Header";
import Footer from "../../components/footer/common/Footer";

export default function AdminLayout () {
  return <div className={s.container}>
    <Header/>
    <div className={s.outlet}>
      <Outlet />
    </div>
    <Footer/>
  </div>;
}