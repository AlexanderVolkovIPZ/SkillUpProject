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
      }),
      providesTags: ["Post", "Patch"]
    }),
    createTaskUser: builder.mutation({
      query: (body) => {
        return {
          url: "/task-user-create",
          method: "post",
          body
        };
      },
      invalidatesTags: ["Post"]
    }),
    deleteTaskUser: builder.mutation({
      query: (id) => {
        return {
          url: `/task-user-delete/${id}`,
          method: "delete"
        };
      },
      invalidatesTags: ["Post"]
    }),
    getTaskUsersByCourseId: builder.query({
      query: (id) => ({
        url: `/task-users-by-course/${id}`,
        method: "get"
      }),
      providesTags: ["Post", "Patch"]
    }),
    updateTaskUser: builder.mutation({
      query: ({ id, ...newData }) => {
        return {
          url: `/task-users/${id}`,
          method: "patch",
          headers: {
            "Content-Type": "application/merge-patch+json"
          },
          body: newData
        };
      },
      invalidatesTags: ["Patch"]
    })
  })
});

export const {
  useGetTaskUserQuery,
  useCreateTaskUserMutation,
  useDeleteTaskUserMutation,
  useGetTaskUsersByCourseIdQuery,
  useUpdateTaskUserMutation
} = taskUserApi;