import { Outlet } from "react-router-dom";
import s from "./AdmineLayout.module.css";

export default function AdminLayout () {
  return <div className={s.container}>
    <div>HEADER</div>
    <div className={s.outlet}>
      <Outlet />
    </div>
    <footer>FOOTER</footer>
  </div>;
}