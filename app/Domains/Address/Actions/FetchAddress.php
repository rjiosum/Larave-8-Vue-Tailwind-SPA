<?php


namespace App\Domains\Address\Actions;


use App\Domains\Address\Contracts\IFetchAddress;
use App\Models\Address;
use App\Models\User;

class FetchAddress implements IFetchAddress
{
    /**
     * @var Address
     */
    private $address;

    /**
     * @var User
     */
    private $user;

    public function __construct(Address $address)
    {
        $this->address = $address;
        $this->user = auth()->guard('api')->user();
    }

    /**
     * Fetch a listing of the resource.
     *
     * @param int $perPage
     * @param string $sortBy
     * @param string $sortDirection
     * @return mixed
     */
    public function fetchUserAddresses(int $perPage = 10, string $sortBy = 'id', string $sortDirection = 'desc')
    {
        return $this->user->addresses()->with(['user', 'country'])->orderBy($sortBy, $sortDirection)->paginate($perPage);
    }
}
