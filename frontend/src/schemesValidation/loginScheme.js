import z from 'zod'

export const loginScheme = z.object({
  password:z.string().regex(/^[a-zA-Z0-9!@#$%^&*()_+{}\[\]:;<>,.?~\\/`-]{8,}$/, "Error password"),
  username:z.string().email("Email is not valid!")
})