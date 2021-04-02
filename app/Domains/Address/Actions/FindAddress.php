<?php


namespace App\Domains\Address\Actions;


use App\Domains\Address\Contracts\IFindAddress;
use App\Models\Address;

class FindAddress implements IFindAddress
{
    /**
     * @var Address
     */
    private $address;

    /**
     * FindAddress constructor.
     * @param Address $address
     */
    public function __construct(Address $address)
    {
        $this->address = $address;
    }

    /**
     * Fetch the specified resource.
     *
     * @param int $id
     * @return mixed
     */
    public function findAddressById(int $id)
    {
        return $this->address::with('user')->findOrFail($id);
    }

    /**
     * Fetch the specified resource.
     *
     * @param $uuid
     * @return mixed
     */
    public function findAddressByUuid($uuid)
    {
        return $this->address::with(['user', 'country'])->where('uuid', '=', $uuid)->firstOrFail();
    }
}
