import { jwtDecode } from "jwt-decode";

export const getJWTInfo = ()=>{
  let tokenInfo = null

  if(localStorage.getItem("token")!==""){
    try{
      tokenInfo = jwtDecode(localStorage.getItem("token"));
    }catch (error){
      return null
    }

    return tokenInfo
  }

  return null
}