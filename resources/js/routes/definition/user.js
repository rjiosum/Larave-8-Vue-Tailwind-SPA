import {lazy} from '../utils/utility';

import auth from '@/middleware/auth';
import isLoggedIn from '@/middleware/isLoggedIn';

export default [
    {
        path: '/user',
        component: lazy('user/UserIndex'),
        children: [
            {path: '', redirect: {name: 'user.dashboard'}},
            {
                path: 'dashboard',
                name: 'user.dashboard',
                component: lazy('user/UserDashboard'),
                meta: {
                    middleware: [isLoggedIn, auth],
                    title: 'Dashboard'
                }
            },
            {
                path: 'profile',
                name: 'user.profile',
                component: lazy('user/UserProfile'),
                meta: {
                    middleware: [isLoggedIn, auth],
                    title: 'Update Profile'
                }
            },
            {
                path: 'password',
                name: 'user.password',
                component: lazy('user/UserPassword'),
                meta: {
                    middleware: [isLoggedIn, auth],
                    title: 'Change Password'
                }
            },
            {
                path: 'avatar',
                name: 'user.avatar',
                component: lazy('user/UserAvatar'),
                meta: {
                    middleware: [isLoggedIn, auth],
                    title: 'Change Avatar'
                }
            },

            {
                path: 'article/create',
                name: 'user.create.article',
                component: lazy('user/article/CreateArticle'),
                meta: {
                    middleware: [isLoggedIn, auth],
                    title: 'Create Article'
                }
            },
            {
                path: 'article/:id/edit',
                name: 'user.edit.article',
                component: lazy('user/article/EditArticle'),
                props: true,
                meta: {
                    middleware: [isLoggedIn, auth],
                    title: 'Update Article'
                }
            },
            {
                path: 'article/:slug/view',
                name: 'user.view.article',
                component: lazy('user/article/ViewArticle'),
                props: true,
                meta: {
                    middleware: [isLoggedIn, auth]
                }
            },
            {
                path: 'articles',
                name: 'user.articles',
                component: lazy('user/article/ListArticles'),
                meta: {
                    middleware: [isLoggedIn, auth],
                    title: 'Articles'
                }
            },


            {
                path: 'addresses',
                name: "user.addresses",
                component: lazy('user/address/ListAddresses'),
                meta: {
                    middleware: [isLoggedIn, auth],
                    title: 'Addresses'
                }
            },
            {
                path: 'address/create',
                name: 'user.create.address',
                component: lazy('user/address/CreateAddress'),
                meta: {
                    middleware: [isLoggedIn, auth],
                    title: 'Create Address'
                }
            },
            {
                path: 'address/:id/edit',
                name: 'user.edit.address',
                component: lazy('user/address/EditAddress'),
                props: true,
                meta: {
                    middleware: [isLoggedIn, auth],
                    title: 'Update Address'
                }
            },
        ]
    }
]
