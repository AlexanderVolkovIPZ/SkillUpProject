import { createApi, fetchBaseQuery } from "@reduxjs/toolkit/query/react";
import { BASE_URL } from "../utils/consts";

export const courseUserApi = createApi({
  reducerPath: "courseUserApi",
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
    create: builder.mutation({
      query: (body) => {
        return {
          url: "/course_users",
          method: "post",
          body
        };
      }
    }),

  })
});

export const { useCreateMutation } = courseUserApi;