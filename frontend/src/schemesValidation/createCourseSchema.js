import z from 'zod'

export const createCourseSchema = z.object({
  name:z.string().min(5).max(70).nonempty("This field can't be empty!"),
  title:z.string().max(100).nullable().default(""),
  description:z.string().max(2000).nullable().default(""),
})