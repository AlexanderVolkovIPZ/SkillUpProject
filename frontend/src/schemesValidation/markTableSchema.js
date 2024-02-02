import z from "zod";

export const markTableSchema = z.object({
  mark: z.number().min(0).max(100).nullable().default(null),
  taskUserId: z.string().nullable().default(null)
});