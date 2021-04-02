<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class Address extends Model
{
    use HasFactory;

    protected $fillable = ['address_1', 'address_2', 'town', 'county', 'postcode', 'country_id'];

    /**
     * Address belongs to a user, a user can have many address
     *
     * @return BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Address belongs to a country
     *
     * @return BelongsTo
     */
    public function country()
    {
        return $this->belongsTo(Country::class);
    }



}
