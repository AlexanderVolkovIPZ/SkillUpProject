import React from "react";
import { Outlet, useParams } from "react-router-dom";
import { useIsCurrentUserCourseCreatorQuery } from "../../store/courseApi";

const WithCourseOwnershipCheckRoutes = () => {
  const { id } = useParams();
  const { data: userCreatorData, isLoading } = useIsCurrentUserCourseCreatorQuery(id);

  return (
    !isLoading && userCreatorData?.isUserCourseCreator ? <Outlet /> : null
  );
};

export default WithCourseOwnershipCheckRoutes;
