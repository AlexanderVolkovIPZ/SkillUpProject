import { configureStore } from "@reduxjs/toolkit";
import { userApi } from "./userApi";
import { courseApi } from "./courseApi";
import { courseUserApi } from "./courseUserApi";
import { taskApi } from "./taksApi";

export const store = configureStore({
  reducer: {
    [userApi.reducerPath]: userApi.reducer,
    [courseApi.reducerPath]: courseApi.reducer,
    [courseUserApi.reducerPath]: courseUserApi.reducer,
    [taskApi.reducerPath]: taskApi.reducer
  },
  middleware: (getDefaultMiddleware) => getDefaultMiddleware().concat(userApi.middleware).concat(courseApi.middleware).concat(courseUserApi.middleware).concat(taskApi.middleware)
});