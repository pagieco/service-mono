import axios from 'axios';
import { fetchHeadData } from '../libraries/data';

const userData = fetchHeadData('app/auth/user');

axios.defaults.baseURL = '/api';

if (userData) {
  axios.defaults.headers.common.Authorization = `Bearer ${userData.api_token}`;
}

export default axios;
