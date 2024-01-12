import { createApi, fetchBaseQuery } from "@reduxjs/toolkit/query/react";
import { BASE_URL } from "../utils/consts";

export const userApi = createApi({
  reducerPath: "userApi",
  baseQuery: fetchBaseQuery({
    baseUrl: BASE_URL,
    prepareHeaders: (headers, { getState }) => {
      const token = getState()?.auth?.token;
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
        console.error("Login error:", error);

        return {
          error: true,
          errorMessage: "Помилка при логіні"
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
    logout: builder.mutation({
      query: () => ({}),
      transformResponse (baseQueryReturnValue, meta, arg) {
        localStorage.removeItem("token");
      }
    })
  })
});

export const { useLoginMutation, useRegisterUserMutation, useLogoutMutation } = userApi;