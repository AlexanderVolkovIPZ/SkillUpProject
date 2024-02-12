import { createApi, fetchBaseQuery } from "@reduxjs/toolkit/query/react";
import { BASE_URL } from "../utils/consts";

export const userApi = createApi({
  reducerPath: "userApi",
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
    login: builder.mutation({
      query: (body) => {
        return {
          url: "/login",
          method: "post",
          body
        };
      }, transformResponse: (response, meta, arg) => {
        localStorage.setItem("token", response.token);
        return { ...response, meta };
      }, transformErrorResponse: (error) => {
        return {
          error: true,
          errorMessage: "Login error!"
        };
      }
    }),
    registerUser: builder.mutation({
      query: (body) => {
        return {
          url: "/users",
          method: "post",
          body
        };
      }
    }),
    getUserInfoByCourse: builder.query({
      query: (id) => ({
        url: `/user-info-by-course/${id}`,
        method: "get"
      })
    })
  })
});

export const { useLoginMutation, useRegisterUserMutation, useLogoutMutation, useGetUserInfoByCourseQuery } = userApi;