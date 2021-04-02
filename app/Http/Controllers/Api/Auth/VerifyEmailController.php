<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;


class VerifyEmailController extends Controller
{
    /**
     * Mark the authenticated user's email address as verified.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function __invoke(Request $request)
    {
        $user = User::where('id', $request->route('id'))->first();

        if (is_null($user)) {
            return response()->json([
                'status' => false,
                'message' => trans('verification.user')
            ], Response::HTTP_BAD_REQUEST);
        }

        if (! hash_equals((string) $request->route('id'), (string) $user->getKey())) {
            return response()->json([
                'status' => false,
                'message' => trans('verification.invalid')
            ], Response::HTTP_BAD_REQUEST);
        }

        if (! hash_equals((string) $request->route('hash'), sha1($user->getEmailForVerification()))) {
            return response()->json([
                'status' => false,
                'message' => trans('verification.invalid')
            ], Response::HTTP_BAD_REQUEST);
        }

        if ($user->hasVerifiedEmail()) {
            return response()->json([
                'status' => true,
                'message' => trans('verification.already_verified')
            ], Response::HTTP_OK);
        }

        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }

        return response()->json([
            'status' => true,
            'message' => trans('verification.verified')
        ], Response::HTTP_OK);
    }
}
