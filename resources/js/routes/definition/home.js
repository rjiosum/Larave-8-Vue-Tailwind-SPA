import {lazy} from '../utils/utility';
import isLoggedIn from "@/middleware/isLoggedIn";

export default [
    {
        path: '/',
        name: 'home',
        component: lazy('home/Home'),
        meta: {
            middleware: [isLoggedIn],
            title: 'Home Page'
        }
    },
    {
        path: '/article/:slug',
        name: 'home.article',
        component: lazy('home/Article'),
        props: true,
        meta: {
            middleware: [isLoggedIn]
        }
    }
]