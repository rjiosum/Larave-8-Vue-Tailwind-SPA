<?php

namespace App\Http\Controllers\Api\Auth;

use App\Facades\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Laravolt\Avatar\Facade as Avatar;


class RegisteredUserController extends Controller
{
    /**
     * @var Avatar
     */
    private $avatar;

    /**
     * Create a new controller instance.
     *
     * @param Avatar $avatar
     */
    public function __construct(Avatar $avatar)
    {
        $this->avatar = $avatar;
    }

    /**
     * Handle an incoming registration request.
     *
     * @param RegisterRequest $request
     * @return JsonResponse|Application|ResponseFactory|Response
     *
     * @throws ValidationException
     */
    public function store(RegisterRequest $request)
    {

        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        if ($request->has('avatar') && $request->avatar) {
            $this->createAvatar($user);
        }

        event(new Registered($user));

        return $this->registered($user)
            ?: (new AuthenticatedSessionController())->store(new LoginRequest($request->only(['email', 'password'])));
    }

    protected function registered(User $user)
    {
        if ($user instanceof MustVerifyEmail) {
            return response([
                'status' => true,
                'verify' => true,
                'message' => trans('verification.sent')
            ], Response::HTTP_OK);
        }
    }

    /**
     * @param User $user
     * @return bool
     */
    private function createAvatar(User $user)
    {
        $avatar = $this->avatar::create($user->first_name . ' ' . $user->last_name)->getImageObject()->encode('png');
        $avatarName = Helper::randomKey(8) . '.png';
        Storage::put('avatars/' . Helper::path($user->id) . $avatarName, (string)$avatar);

        return $user->update(['avatar' => $avatarName]);
    }
}
