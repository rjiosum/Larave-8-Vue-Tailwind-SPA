<?php

namespace App\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;

trait IssueToken
{
    /**
     * @param Request $request
     * @param $grantType
     * @param string $scope
     * @return mixed
     */
    public function issueToken(Request $request, $grantType, $scope = "*")
    {
        /*$response = Http::asForm()->post(config('passport.url'), [
            'grant_type' => $grantType,
            'client_id' => $this->client->id,
            'client_secret' => $this->client->secret,
            'username' => $request->email,
            'password' => $request->password,
            'scope' => $scope
        ]);

        return $response;*/

        /*$params = [
            'grant_type' => $grantType,
            'client_id' => $this->client->id,
            'client_secret' => $this->client->secret,
            'scope' => $scope
        ];
        if ($grantType !== 'social') {
            $params['username'] = $request->username ?: $request->email;
        }
        $request->request->add($params);
        $proxy = Request::create(config('passport.url'), 'POST');
        return Route::dispatch($proxy);*/

        $params = [
            'username' => $request->email,
            'password' => $request->password,
            'grant_type' => $grantType,
            'client_id' => $this->client->id,
            'client_secret' => $this->client->secret,
            'scope' => $scope
        ];

        $request = app('request')->create(config('passport.url'), 'POST', $params);
        return app('router')->prepareResponse($request, app()->handle($request));
    }
}
