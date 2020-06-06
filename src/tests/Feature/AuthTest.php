<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthTest extends Base
{

    public function testSuccessfulRegistrationByProvidingRequiredParameters()
    {
        $response = $this->withHeaders([
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ])->json('POST','api/register',[
            'name' => 'Anderson',
            'email' => 'adnderson@mail.com',
            'password' => $this->password,
            'password_confirmation' => $this->password
        ]);

       $response->assertStatus(201);
    }

    public function testFailedRegistrationWithoutName()
    {
        $response = $this->withHeaders([
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ])->json('POST','api/register',[
            'email' => 'adnderson@mail.com',
            'password' => $this->password,
            'password_confirmation' => $this->password
        ]);

       $response->assertStatus(422);
    }

    public function testFailedRegistrationWithoutEmail()
    {
        $response = $this->withHeaders([
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ])->json('POST','api/register',[
            'name' => 'Anderson',
            'password' => $this->password,
            'password_confirmation' => $this->password
        ]);

       $response->assertStatus(422);
    }

    public function testFailedRegistrationWithoutPassword()
    {
        $response = $this->withHeaders([
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ])->json('POST','api/register',[
            'name' => 'Anderson',
            'email' => 'adnderson@mail.com',
        ]);

       $response->assertStatus(422);
    }

    public function testFailedRegistrationwithoutPasswordConfirmation()
    {
        $response = $this->withHeaders([
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ])->json('POST','api/register',[
            'name' => 'Anderson',
            'email' => 'adnderson@mail.com',
            'password' => $this->password,
        ]);

       $response->assertStatus(422);
    }

    public function testFailedRegistrationWithWrongPasswordConfirmation()
    {
        $response = $this->withHeaders([
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ])->json('POST','api/register',[
            'name' => 'Anderson',
            'email' => 'adnderson@mail.com',
            'password' => $this->password,
            'password_confirmation' => 'dsfdsgf'
        ]);

       $response->assertStatus(422);
    }

    public function testFailedRegistrationByPasswordWithShortLength()
    {
        $response = $this->withHeaders([
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ])->json('POST','api/register',[
            'name' => 'Anderson',
            'email' => 'adnderson@mail.com',
            'password' => '123',
            'password_confirmation' => '123'
        ]);

       $response->assertStatus(422);
    }

    public function testSuccessfulLoginRequestByProvidngCorrectCredintials()
    {
        factory(\App\User::class, 1)->create([
           'email' => 'anderson@mail.com'
        ]);
//        fwrite(STDERR, print_r(\App\User::all()));
        $response = $this->withHeaders([
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ])->json('POST','api/login',[
            'email' => 'anderson@mail.com',
            'password' => '111111',
        ]);

       $response->assertStatus(200);
    }

    public function testFailedLoginRequestWithoutEmail()
    {
        factory(\App\User::class, 1)->create([
           'email' => 'anderson@mail.com'
        ]);
        $response = $this->withHeaders([
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ])->json('POST','api/login',[
            'password' => '111111',
        ]);

       $response->assertStatus(422);
    }

    public function testFailedLoginRequestWithoutPassword()
    {
        factory(\App\User::class, 1)->create([
           'email' => 'anderson@mail.com'
        ]);
        $response = $this->withHeaders([
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ])->json('POST','api/login',[
            'email' => 'anderson@mail.com',
        ]);

       $response->assertStatus(422);
    }

    public function testFailedLoginRequestWithWrongEmail()
    {
        factory(\App\User::class, 1)->create([
           'email' => 'anderson@mail.com'
        ]);
        $response = $this->withHeaders([
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ])->json('POST','api/login',[
            'email' => 'wrong@mail.com',
            'password' => '111111',
        ]);

       $response->assertStatus(401);
    }

    public function testFailedLoginRequestWithWrongPassword()
    {
        factory(\App\User::class, 1)->create([
           'email' => 'anderson@mail.com'
        ]);
        $response = $this->withHeaders([
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ])->json('POST','api/login',[
            'email' => 'anderson@mail.com',
            'password' => '22222',
        ]);

       $response->assertStatus(401);
    }

}
