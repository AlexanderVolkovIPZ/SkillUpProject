import { createApi, fetchBaseQuery } from "@reduxjs/toolkit/query/react";
import { BASE_URL } from "../utils/consts";

export const taskUserApi = createApi({
  reducerPath: "taskUserApi",
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
    getTaskUser: builder.query({
      query: () => ({
        url: `/task_users`,
        method: "get"
      })
    })
  })
});

export const { useGetTaskUserQuery } = taskUserApi;