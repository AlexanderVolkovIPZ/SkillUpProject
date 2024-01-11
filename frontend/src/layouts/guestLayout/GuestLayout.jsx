import s from "./GuestLayout.module.css"
import { Outlet } from "react-router-dom";

export default function GuestLayout(){
  return <div className={s.container}>
    <div>HEADER</div>
    <div className={s.outlet}>
      <Outlet/>
    </div>
    <div>FOOTER</div>
  </div>
}