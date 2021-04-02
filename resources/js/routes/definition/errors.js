import {lazy} from '../utils/utility';
import isLoggedIn from '@/middleware/isLoggedIn';

export default [
    {
        path: '/404',
        name: 'not.found',
        component: lazy('errors/NotFound'),
        meta: {
            middleware: [isLoggedIn],
            title: 'Error 404'
        }
    },
    {
        path: '*',
        component: lazy('errors/NotFound'),
        meta: {
            middleware: [isLoggedIn],
            title: 'Error 404'
        }
    }
]