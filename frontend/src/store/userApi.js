import { createApi, fetchBaseQuery } from "@reduxjs/toolkit/query/react";
import { BASE_URL } from "../utils/consts";

export const userApi = createApi({
  reducerPath: "userApi",
  baseQuery: fetchBaseQuery({
    baseUrl: BASE_URL,
  }),
  endpoints: (builder) => ({
    registerUser: builder.mutation({
      query: (body) => {
        return {
          url: "/users",
          method: "post",
          body
        };
      }
    }),
  })
});

export const {  useRegisterUserMutation } = userApi;