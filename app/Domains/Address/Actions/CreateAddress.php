<?php


namespace App\Domains\Address\Actions;


use App\Domains\Address\Contracts\ICreateAddress;
use App\Models\Address;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class CreateAddress implements ICreateAddress
{
    /**
     * @var Address
     */
    private $address;

    /**
     * @var User
     */
    private $user;

    /**
     * CreateAddress constructor.
     * @param Address $address
     */
    public function __construct(Address $address)
    {
        $this->address = $address;
        $this->user = auth()->guard('api')->user();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param array $params
     * @return mixed
     */
    public function createAddress(array $params)
    {
        $collection = collect($params);

        return DB::transaction(function () use (&$collection) {
            $address = $this->user->addresses()->create($collection->all());
            $address->save();
            return $address;
        }, 5);
    }
}
