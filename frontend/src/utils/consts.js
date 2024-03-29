const roles = {
  ADMIN: "ROLE_ADMIN",
  CLIENT: "ROLE_USER",
  MANAGER:"ROLE_MANAGER"
};

const responseStatus = {
  HTTP_OK: 200,
  HTTP_CREATED: 201,
  HTTP_NO_CONTENT: 204,
  HTTP_BAD_REQUEST: 400,
  HTTP_ERROR_VALIDATION: 422,
  FORBIDDEN: 403,
  HTTP_INTERNAL_SERVER_ERROR: 500,
  HTTP_UNAUTHORIZED: 401,
  HTTP_CONFLICT: 409
};

const BASE_URL = "https://localhost/api"

export {
  roles,
  responseStatus,
  BASE_URL
};