import {lazy} from '../utils/utility';
import guest from '@/middleware/guest';
import auth from '@/middleware/auth';
import isLoggedIn from '@/middleware/isLoggedIn';

export default [
    {
        path: '/register',
        name: 'auth.register',
        component: lazy('auth/Register'),
        meta: {
            middleware: [guest],
            title: 'Register'
        }
    },
    {
        path: '/login',
        name: 'auth.login',
        component: lazy('auth/Login'),
        meta: {
            middleware: [isLoggedIn, guest],
            title: 'Login'
        }
    },
    {
        path: '/logout',
        name: 'auth.logout',
        component: lazy('auth/Logout'),
        meta: {
            middleware: [isLoggedIn, auth]
        }
    },
    {
        path: '/forgot-password',
        name: 'password.reset.email',
        component: lazy('auth/password/PasswordResetLink'),
        meta: {
            middleware: [isLoggedIn, guest],
            title: 'Forgot Password'
        }
    },
    {
        path: '/password-reset/:token',
        name: 'password.reset',
        component: lazy('auth/password/PasswordReset'),
        meta: {
            middleware: [isLoggedIn, guest],
            title: 'Reset your password'
        }
    },

    {
        path: '/verify-email/:id/:hash',
        name: 'verification.verify',
        component: lazy('auth/verification/VerifyEmail'),
        meta: {
            middleware: [isLoggedIn, guest],
            title: 'Email Verification'
        }
    },
    {
        path: '/email/resend',
        name: 'verification.resend',
        component: lazy('auth/verification/VerifyEmailResend'),
        meta: {
            middleware: [isLoggedIn, guest],
            title: 'Resend email verification'
        }
    },
]