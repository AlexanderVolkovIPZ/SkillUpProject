import z from 'zod'

export const connectCourseSchema = z.object({
  name:z.string().max(70).nonempty("This field can't be empty!"),
})