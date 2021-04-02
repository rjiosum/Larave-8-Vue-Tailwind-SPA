<?php


namespace Tests;


use Illuminate\Testing\TestResponse;

trait TestHelper
{
    public function create(string $class, array $attributes = [], int $times = null)
    {
        return $class::factory($times)->create($attributes);
    }

    public function make(string $class, array $attributes = [], int $times = null)
    {
        return $class::factory($times)->make($attributes);
    }

    public function raw(string $class, array $attributes = [], int $times = null)
    {
        return $class::factory($times)->raw($attributes);
    }

    public function dumpResponse(TestResponse $response)
    {
        dd([$response->getContent(), $response->getStatusCode()]);
    }
}