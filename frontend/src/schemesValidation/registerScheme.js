import z from 'zod'

export const registerScheme = z.object({
  password:z.string().regex(/^[a-zA-Z0-9!@#$%^&*()_+{}\[\]:;<>,.?~\\/`-]{8,}$/, "Error password"),
  confirmPassword:z.string().regex(/^[a-zA-Z0-9!@#$%^&*()_+{}\[\]:;<>,.?~\\/`-]{8,}$/, "Error password"),
  firstName:z.string().max(20).min(2).regex(/^[A-Z][a-zA-Z\s']+$/, "Error firstName"),
  lastName:z.string().max(20).min(2).regex(/^[A-Z][a-zA-Z\s']+$/, "Error lastName"),
  email:z.string().email("Email is not valid!")
}).refine((data)=>  data.password===data.confirmPassword,{
  message:"Passwords do not match",
  path:["confirmPassword"]
})