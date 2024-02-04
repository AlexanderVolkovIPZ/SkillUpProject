import { useParams } from "react-router-dom";
import { useIsCurrentUserCourseCreatorQuery } from "../../store/courseApi";

const WithCourseOwnershipCheckRoutes = ({ children }) => {
  const { id } = useParams();
  const { data: userCreatorData, isLoading } = useIsCurrentUserCourseCreatorQuery(id);

  return (
    !isLoading && userCreatorData?.isUserCourseCreator ? children : null
  );
};

export default WithCourseOwnershipCheckRoutes;
