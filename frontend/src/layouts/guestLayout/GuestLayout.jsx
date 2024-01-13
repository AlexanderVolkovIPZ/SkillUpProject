import s from "./GuestLayout.module.css"
import { Outlet } from "react-router-dom";
import Header from "../../components/header/guest/Header";
import Footer from "../../components/footer/common/Footer";

export default function GuestLayout(){
  return <div className={s.container}>
    <Header/>
    <div className={s.outlet}>
      <Outlet/>
    </div>
    <Footer/>
  </div>
}