<?php

namespace Tests\Unit;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use TestSeeder;

class Base extends TestCase
{
    use RefreshDatabase;

    protected function setUp() : void
    {
        parent::setUp();
        $this->password = Hash::make('111111');
        $this->productService = $this->app->make('App\Services\IProductService');
        $this->seed(TestSeeder::class);
    }
}
