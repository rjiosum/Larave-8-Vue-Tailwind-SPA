<?php

namespace App\Http\Controllers\Api\Address;

use App\Domains\Address\Contracts\{ICreateAddress, IDeleteAddress, IFetchAddress, IFindAddress, IUpdateAddress};
use App\Http\Controllers\Controller;
use App\Http\Requests\Address\{AddressStoreRequest, AddressUpdateRequest};
use App\Http\Resources\{AddressCollectionResource, AddressResource};
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\{JsonResponse, Request, Response};

class AddressController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @param IFetchAddress $fetch
     * @return AddressCollectionResource
     */
    public function index(Request $request, IFetchAddress $fetch)
    {
        $perPage = $request->get('per_page') ?? 12;

        return new AddressCollectionResource($fetch->fetchUserAddresses($perPage));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param AddressStoreRequest $request
     * @param ICreateAddress $create
     * @return JsonResponse
     */
    public function store(AddressStoreRequest $request, ICreateAddress $create)
    {
        $address = $create->createAddress($request->all());

        return $this->responseJson(
            (bool)$address,
            new AddressResource($address),
            (bool)$address
                ? trans('response.success.create', ['attribute' => 'Address'])
                : trans('response.error.create', ['attribute' => 'address']),
            Response::HTTP_CREATED
        );
    }

    /**
     * Display the specified resource.
     *
     * @param string $uuid
     * @param IFindAddress $find
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function show(string $uuid, IFindAddress $find)
    {
        $address = $find->findAddressByUuid($uuid);

        $this->authorize('view', $address);

        return $this->responseJson(true, new AddressResource($address), '', Response::HTTP_OK);
    }
    /**
     * Update the specified resource in storage.
     *
     * @param AddressUpdateRequest $request
     * @param string $uuid
     * @param IFindAddress $find
     * @param IUpdateAddress $update
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function update(AddressUpdateRequest $request,
                           string $uuid,
                           IFindAddress $find,
                           IUpdateAddress $update)
    {
        $address = $find->findAddressByUuid($uuid);

        $this->authorize('update', $address);

        if($request->has('check') && $request->check){
            return $this->responseJson(true, new AddressResource($address), '', Response::HTTP_OK);
        }

        $request->merge(['id' => $address->id]);
        $status = $update->updateAddress($request->all());

        return $this->responseJson(
            (bool)$status,
            new AddressResource($address->fresh()),
            (bool)$status
                ? trans('response.success.update', ['attribute' => 'Address'])
                : trans('response.error.update', ['attribute' => 'address']),
            Response::HTTP_ACCEPTED
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param string $uuid
     * @param IFindAddress $find
     * @param IDeleteAddress $delete
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function destroy(string $uuid, IFindAddress $find, IDeleteAddress $delete)
    {
        $address = $find->findAddressByUuid($uuid);
        $this->authorize('delete', $address);

        $status = $delete->deleteAddress($address->id);

        return $this->responseJson(
            (bool)$status,
            [],
            (bool)$status
                ? trans('response.success.delete', ['attribute' => 'Address'])
                : trans('response.error.delete', ['attribute' => 'address']),
            Response::HTTP_OK
        );

    }
}
