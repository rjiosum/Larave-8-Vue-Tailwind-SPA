<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\AvatarUpdateRequest;
use App\Domains\User\Contracts\IUpdateUserAvatar;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class AvatarController extends Controller
{
    /**
     * Update resources avatar
     *
     * @param AvatarUpdateRequest $request
     * @param IUpdateUserAvatar $update
     * @return JsonResponse
     */
    public function update(AvatarUpdateRequest $request, IUpdateUserAvatar $update)
    {
        $user = $request->user('api');

        $filename = $update->storeAvatarImage($request->avatar, $user);
        $status = $update->updateAvatar(['id' => $user->id, 'avatar' => $filename]);

        return $this->responseJson(
            (bool)$status,
            ['file' => $filename],
            (bool)$status
                ? trans('response.success.update', ['attribute' => 'Avatar'])
                : trans('response.error.update', ['attribute' => 'avatar']),
            Response::HTTP_ACCEPTED);
    }
}
