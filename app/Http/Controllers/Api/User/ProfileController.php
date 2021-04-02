<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\ProfileUpdateRequest;
use App\Http\Resources\UserPublicResource;
use App\Domains\User\Contracts\IUpdateUserProfile;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ProfileController extends Controller
{

    /**
     * @param Request $request
     * @return JsonResponse|Application|ResponseFactory|Response
     */
    public function index(Request $request)
    {
        if (!$request->user('api')) {
            return response([], Response::HTTP_PRECONDITION_FAILED);
        }
        return $this->responseJson(true, new UserPublicResource($request->user('api')), '', Response::HTTP_OK);
    }

    /**
     * @param ProfileUpdateRequest $request
     * @param IUpdateUserProfile $update
     * @return JsonResponse
     */
    public function update(ProfileUpdateRequest $request, IUpdateUserProfile $update)
    {
        $user = $request->user('api');
        $status = $update->updateProfile(['id' => $user->id, 'first_name' => $request->first_name, 'last_name' => $request->last_name]);

        return $this->responseJson(
            (bool)$status,
            [],
            (bool)$status
                ? trans('response.success.update', ['attribute' => 'Profile'])
                : trans('response.error.update', ['attribute' => 'profile']),
            Response::HTTP_ACCEPTED);
    }

}
