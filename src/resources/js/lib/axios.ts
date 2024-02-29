import Axios from 'axios';

const axios = Axios.create({
  baseURL: `${import.meta.env.VITE_OPENAPI_URL}`,
  withCredentials: true,
});

export default axios;
