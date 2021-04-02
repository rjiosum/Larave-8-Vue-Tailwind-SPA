<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\PasswordUpdateRequest;
use App\Domains\User\Contracts\IUpdateUserPassword;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;


class PasswordController extends Controller
{

    /**
     * @param PasswordUpdateRequest $request
     * @param IUpdateUserPassword $update
     * @return JsonResponse
     */
    public function update(PasswordUpdateRequest $request, IUpdateUserPassword $update)
    {
        $user = $request->user('api');
        $status = $update->updatePassword(['id' => $user->id, 'password' => $request->password]);

        return $this->responseJson(
            (bool)$status,
            [],
            (bool)$status
                ? trans('response.success.update', ['attribute'=> 'Password'])
                : trans('response.error.update', ['attribute'=> 'password']),
            Response::HTTP_ACCEPTED);
    }
}
