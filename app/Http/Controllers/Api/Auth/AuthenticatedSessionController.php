<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Resources\UserPublicResource;
use App\Traits\IssueToken;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Validation\ValidationException;
use Laravel\Passport\Client;

class AuthenticatedSessionController extends Controller
{
    use IssueToken;

    private $client;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->client = Client::whereNull('user_id')
            ->where('provider', 'users')
            ->where('password_client', 1)
            ->where('revoked', 0)->first();
    }

    /**
     * Handle an incoming authentication request.
     *
     * @param LoginRequest $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function store(LoginRequest $request)
    {
        $request->authenticate();

        $user = Auth::user();

        if ($user instanceof MustVerifyEmail && ! $user->hasVerifiedEmail()) {
            return response()->json([
                'status' => false,
                'verify' => true,
                'message' => trans('verification.unverified')
            ]);
        }

        $response = $this->issueToken($request, 'password');

        if ($response->getStatusCode() != 200) {
            throw ValidationException::withMessages([
                'emailn' => [trans('auth.failed')],
            ]);
        }

        $data = json_decode($response->getContent());

        $param = $this->cookieParams($data->access_token);

        if($request->has('remember') && $request->remember){
            $param['minutes'] = 2628000; //five years
        }

        $cookie = Cookie::make($param['name'], $param['value'], $param['minutes'], $param['path'], $param['domain'], $param['secure'], $param['httponly'], $param['raw'], $param['samesite']);

        return $this->responseJson(true, new UserPublicResource($user), trans('auth.login'), Response::HTTP_OK)->withCookie($cookie);
    }

    /**
     * @param $token
     * @return array
     */
    private function cookieParams($token): array
    {
        return [
            'name' => config('passport.cookie.name'),
            'value' => $token,
            'minutes' => config('passport.cookie.minutes'), //0 means cookie will expires when browser is closed
            'path' => config('passport.cookie.path'),
            'domain' => config('passport.cookie.domain'),
            'secure' => config('passport.cookie.secure'),
            'httponly' => config('passport.cookie.httponly'),
            'raw' => config('passport.cookie.raw'),
            'samesite' => config('passport.cookie.samesite')
        ];
    }

    /**
     * Destroy an authenticated session.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function destroy(Request $request)
    {
        $request->user()->token()->revoke();
        $cookie = Cookie::forget(config('passport.cookie.name'));

        return $this->responseJson(true, [], trans('auth.logout'), Response::HTTP_OK)->withCookie($cookie);
    }
}
