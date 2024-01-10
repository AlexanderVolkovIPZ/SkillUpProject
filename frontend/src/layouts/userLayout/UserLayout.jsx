import { Outlet } from "react-router-dom";
import s from "./UserLayout.module.css";

export default function UserLayout () {
  return <div className={s.container}>
    <div>
      HEADER
    </div>
    <div className={s.outlet}>
      <Outlet />
    </div>
    <div>
      FOOTER
    </div>
  </div>;
}