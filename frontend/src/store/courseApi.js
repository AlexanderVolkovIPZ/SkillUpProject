import { createApi, fetchBaseQuery } from "@reduxjs/toolkit/query/react";
import { BASE_URL } from "../utils/consts";

export const courseApi = createApi({
  reducerPath: "courseApi",
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
    userCourses: builder.query({
      query: () => ({
        url: `/user-courses`,
        method: "get"
      }),
      providesTags: ["Post"]
    }),
    create: builder.mutation({
      query: (body) => {
        return {
          url: "/courses",
          method: "post",
          body
        };
      },
      invalidatesTags: ["Post"]
    }),
    connectCourse: builder.mutation({
      query: ({ code, body }) => {
        return {
          url: `/course-connect/${code}`,
          method: "post",
          body
        };
      },
      invalidatesTags: ["Post"]
    }),
    course: builder.query({
      query: (id) => ({
        url: `/courses/${id}`,
        method: "get"
      }),
      providesTags: ["Post"]
    }),
    userCourseByCode: builder.query({
      query: (code) => ({
        url: `/user-course/${code}`,
        method: "get"
      }),
      providesTags: ["Post"]
    }),
    updateCourse: builder.mutation({
      query: ({ id, ...newData }) => ({
        url: `/courses/${id}`, // Assuming your API endpoint supports updating by ID
        method: "put",
        body: newData
      }),
    }),
    isCurrentUserCourseCreator: builder.query({
      query: (id) => ({
        url: `/user-course-creator/${id}`,
        method: "get"
      })
    })
  })
});

export const {
  useCreateMutation,
  useUserCoursesQuery,
  useUserCourseByCodeQuery,
  useCourseQuery,
  useUpdateCourseMutation,
  useIsCurrentUserCourseCreatorQuery,
  useConnectCourseMutation
} = courseApi;