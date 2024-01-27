import z from "zod";

export const attachTaskSolutionSchema = z.object({
  file: z.any().nullable().default(null),
  link: z.string().nullable().default("")
}).refine((data) => {
  return data.file !== null || data.link !== null;
}, {
  message: "At least one field (file or description) must be filled"
});