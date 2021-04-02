<?php


namespace App\Domains\Address\Actions;


use App\Domains\Address\Contracts\IUpdateAddress;
use App\Models\Address;
use Illuminate\Support\Facades\DB;

class UpdateAddress implements IUpdateAddress
{
    /**
     * @var Address
     */
    private $address;

    /**
     * UpdateAddress constructor.
     * @param Address $address
     */
    public function __construct(Address $address)
    {
        $this->address = $address;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param array $params
     * @return mixed
     */
    public function updateAddress(array $params)
    {
        $collection = collect($params);

        return DB::transaction(function () use (&$collection) {
            return $this->address
                ->where('id', $collection->get('id'))
                ->update($collection->except(['id', 'uuid'])->all());
        }, 5);
    }
}
