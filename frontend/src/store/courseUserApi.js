import { createApi, fetchBaseQuery } from "@reduxjs/toolkit/query/react";
import { BASE_URL } from "../utils/consts";

export const courseUserApi = createApi({
  reducerPath: "courseUserApi",
  baseQuery: fetchBaseQuery({
    baseUrl: BASE_URL,
    prepareHeaders: (headers, { getState }) => {
      const token = localStorage.getItem("token")
      if (token) {
        headers.set("Authorization", `Bearer ${token}`);
      }
      return headers;
    }
  }),
  endpoints: (builder) => ({
    create: builder.mutation({
      query: (body) => {
        return {
          url: "/course_users",
          method: "post",
          body
        };
      }
    }),
    getCourseUsers: builder.query({
      query: () => ({
        url: `/course_users`,
        method: "get"
      })
    })
  })
});

export const { useCreateMutation, useGetCourseUsersQuery } = courseUserApi;