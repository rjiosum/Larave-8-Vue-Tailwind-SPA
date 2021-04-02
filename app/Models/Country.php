<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;


class Country extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'ISOAlpha2', 'ISOAlpha3', 'status'];

    /**
     * A country can have many Addresses
     *
     * @return HasMany
     */
    public function addresses()
    {
        return $this->hasMany(Address::class);
    }
}
