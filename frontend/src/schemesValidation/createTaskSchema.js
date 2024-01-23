import { z } from "zod";

export const createTaskSchema = z.object({
  name: z.string().min(5).max(70).nonempty("This field can't be empty!"),
  description: z.string().max(10000).nullable(),
  date: z.date(),
  mark: z.number().min(0).max(100),
  file: z.any().nullable()
});
