<?php

namespace App\Http\Controllers\Api\Country;

use App\Http\Controllers\Controller;
use App\Http\Resources\CountryCollectionResource;
use App\Models\Country;


class CountryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return CountryCollectionResource
     */
    public function index()
    {
        return new CountryCollectionResource(Country::all());
    }

}
