import { createApi, fetchBaseQuery } from "@reduxjs/toolkit/query/react";
import { BASE_URL } from "../utils/consts";

export const taskApi = createApi({
  reducerPath: "taskApi",
  baseQuery: fetchBaseQuery({
    baseUrl: BASE_URL,
    prepareHeaders: (headers, { getState }) => {
      const token = localStorage.getItem("token");
      if (token) {
        headers.set("Authorization", `Bearer ${token}`);
      }
      return headers;
    }
  }),
  endpoints: (builder) => ({
    getTasksByCourse: builder.query({
      query: (id) => ({
        url: `/tasks-by-course/${id}`,
        method: "get"
      })
    })
  })
});

export const { useGetTasksByCourseQuery } = taskApi;