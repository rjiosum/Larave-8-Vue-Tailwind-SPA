<?php


namespace App\Domains\Address\Actions;


use App\Domains\Address\Contracts\IDeleteAddress;
use App\Models\Address;
use Illuminate\Support\Facades\DB;


class DeleteAddress implements IDeleteAddress
{
    /**
     * @var Address
     */
    private $address;

    /**
     * DeleteAddress constructor.
     * @param Address $address
     */
    public function __construct(Address $address)
    {
        $this->address = $address;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return mixed
     */
    public function deleteAddress(int $id)
    {
        return DB::transaction(function () use (&$id) {
            return $this->address->where('id', $id)->delete();
        }, 5);
    }
}
