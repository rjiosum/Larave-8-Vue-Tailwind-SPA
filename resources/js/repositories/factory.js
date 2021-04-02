require ('./bootstrap');

import Auth from './api/authenticate';
import User from './api/user';
import Crud from './api/crud';

const auth = Auth(window.axios)('/api/auth');
const user = User(window.axios)('/api/user');
const article = Crud(window.axios)('/api/user/article');
const address = Crud(window.axios)('/api/user/address');
const country = Crud(window.axios)('/api/country');
const home = Crud(window.axios)('/api');

const repositories = {
    auth,
    user,
    article,
    address,
    country,
    home
};

export const Factory = {
    get: name => repositories[name]
};
