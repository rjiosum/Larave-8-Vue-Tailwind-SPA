<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;

class PasswordResetLinkController extends Controller
{
    /**
     * Handle an incoming password reset link request.
     *
     * @param Request $request
     * @return JsonResponse|RedirectResponse|void
     * @throws ValidationException
     */
    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        // We will send the password reset link to this user. Once we have attempted
        // to send the link, we will examine the response then see the message we
        // need to show to the user. Finally, we'll send out a proper response.
        $response = Password::sendResetLink(
            $request->only('email')
        );

        return ($response == Password::RESET_LINK_SENT)
            ? $this->sendResetLinkResponse($response)
            : $this->sendResetLinkFailedResponse($response);
    }

    /**
     * Get the response for a successful password reset link.
     *
     * @param string $response
     * @return RedirectResponse|JsonResponse
     */
    protected function sendResetLinkResponse($response)
    {
        return $this->responseJson(true, [], trans($response), Response::HTTP_OK);
    }

    /**
     * Get the response for a failed password reset link.
     *
     * @param string $response
     * @return void
     * @throws ValidationException
     */
    protected function sendResetLinkFailedResponse($response)
    {
        throw ValidationException::withMessages([
            'email' => [trans($response)],
        ]);
    }
}
