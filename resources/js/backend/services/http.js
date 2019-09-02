import axios from 'axios';
import { isLoggedIn, getAccessToken } from '../auth';

axios.defaults.baseURL = '/api';

if (isLoggedIn()) {
  axios.defaults.headers.common.Authorization = `Bearer ${getAccessToken()}`;
}

export default axios;
