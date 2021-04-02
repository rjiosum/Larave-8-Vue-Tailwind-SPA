<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class NewPasswordController extends Controller
{
    /**
     * Handle an incoming new password request.
     *
     * @param Request $request
     * @return JsonResponse|void
     *
     * @throws ValidationException
     */
    public function store(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|string|confirmed|min:8',
        ]);

        // Here we will attempt to reset the user's password. If it is successful we
        // will update the password on an actual user model and persist it to the
        // database. Otherwise we will parse the error and return the response.
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user) use ($request) {
                $user->forceFill([
                    'password' => Hash::make($request->password),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        // If the password was successfully reset, we will redirect the user back to
        // the application's home authenticated view. If there is an error we can
        // redirect them back to where they came from with their error message.
        return $status == Password::PASSWORD_RESET
                    ? $this->sendResetResponse($status)
                    : $this->sendResetFailedResponse($status);
    }

    /**
     * Get the response for a successful password reset.
     *
     * @param string $response
     * @return JsonResponse
     */
    protected function sendResetResponse($response)
    {
        return $this->responseJson(true, [], trans($response), Response::HTTP_OK);
    }

    /**
     * Get the response for a failed password reset.
     *
     * @param string $response
     * @return void
     * @throws ValidationException
     */
    protected function sendResetFailedResponse($response)
    {
        throw ValidationException::withMessages([
            'email' => [trans($response)],
        ]);
    }
}
