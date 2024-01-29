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
      }), providesTags: ["Post"]
    }),
    create: builder.mutation({
      query: (body) => {
        return {
          url: "/task-create",
          method: "post",
          body
        };
      }, invalidatesTags: ["Post"]
    }),
    getTask: builder.query({
      query: (id) => ({
        url: `/tasks/${id}`,
        method: "get"
      }), providesTags: ["Post"]
    }),
    updateTask: builder.mutation({
      query: ({ id, body }) => {
        return {
          url: `/task-update/${id}`,
          method: "post",
          body
        };
      },
      invalidatesTags: ["Post"]
    }),
    deleteTask: builder.mutation({
      query: (id, body) => {
        return {
          url: `/task-delete/${id}`,
          method: "delete"
        };
      },
      invalidatesTags: ["Post"]
    })

  })
});

export const {
  useGetTasksByCourseQuery,
  useCreateMutation,
  useGetTaskQuery,
  useGetTaskFileQuery,
  useUpdateTaskMutation,
  useDeleteTaskMutation
} = taskApi;