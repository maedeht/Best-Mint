<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use JWTAuth;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ProductTest extends Base
{
    public function testSuccessfullFetchingProductsList()
    {
        $response = $this->json('GET','api/products');
        $response->assertStatus(200);
    }

    public function testSuccessfullReceivingProductsWithPagination()
    {
        $response = $this->json('GET','api/products');
        $response->assertStatus(200);
        $responseArray = json_decode($response->getContent());
        $this->assertEquals(count($responseArray->data), $responseArray->meta->pagination->count);
    }

    public function testSuccessfulSearchingOnCategories()
    {
        factory(\App\Product::class,1)->create([
           'category' => 'books'
        ]);
        $response = $this->json('GET','api/products',['filter' => 'book']);
        $response->assertStatus(200);
        $responseArray = json_decode($response->getContent());
        $this->assertGreaterThan(0, count($responseArray->data));
    }

    public function testFailedSavingProductRequestWithoutAuthentication()
    {
        $response = $this->withHeaders([
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ])->json('POST','api/products',[
            'title' => 'A test product',
            'price' => 780,
            'description' => 'This is a test product',
            'qty' => 54,
            'category' => 'coats'
        ]);

        $response->assertStatus(401);
    }

    public function testSuccessfullSavingRequestForProduct()
    {
        $token = $this->getUserJWTToken();
        $response = $this->withHeaders([
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'Authorization' => 'Bearer '.$token
        ])->json('POST','api/products',[
            'title' => 'A test product',
            'price' => 780,
            'description' => 'This is a test product',
            'qty' => 54,
            'category' => 'glasses'
        ]);

        $response->assertStatus(201);
    }

    public function testFailedSavingRequestForProductWithoutTitle()
    {
        $token = $this->getUserJWTToken();
        $response = $this->withHeaders([
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'Authorization' => 'Bearer '.$token
        ])->json('POST','api/products',[
            'price' => 780,
            'description' => 'This is a test product',
            'qty' => 54,
            'category' => 'glasses'
        ]);

        $response->assertStatus(422);
    }

    public function testFailedSavingRequestForProductWithoutPrice()
    {
        $token = $this->getUserJWTToken();
        $response = $this->withHeaders([
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'Authorization' => 'Bearer '.$token
        ])->json('POST','api/products',[
            'title' => 'A test product',
            'description' => 'This is a test product',
            'qty' => 54,
            'category' => 'glasses'
        ]);

        $response->assertStatus(422);
    }

    public function testFailedSavingRequestForProductWithoutQuantity()
    {
        $token = $this->getUserJWTToken();
        $response = $this->withHeaders([
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'Authorization' => 'Bearer '.$token
        ])->json('POST','api/products',[
            'title' => 'A test product',
            'price' => 780,
            'description' => 'This is a test product',
            'category' => 'glasses'
        ]);

        $response->assertStatus(422);
    }

    public function testFailedSavingRequestForProductWithoutCategory()
    {
        $token = $this->getUserJWTToken();
        $response = $this->withHeaders([
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'Authorization' => 'Bearer '.$token
        ])->json('POST','api/products',[
            'title' => 'A test product',
            'price' => 780,
            'description' => 'This is a test product',
            'qty' => 54,
        ]);

        $response->assertStatus(422);
    }

    public function testSuccessfullUploadingProductsInformationFromFile()
    {
        Storage::fake('uploads');

        $header = 'category,title,price,description,qty';
        $row1 = 'glasses,lavaei,220000,ﺍﻮﻫ چﻩ ﺵیکﻩ,27';
        $row2 = 'shirts,hiliko,80000,ﺍیﻦﻣ ﺏگیﺭ ﺩیگﻩ,90';

        $content = implode("\n", [$header, $row1, $row2]);
        $token = $this->getAdminJWTToken();

        $response = $this->withHeaders([
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'Authorization' => 'Bearer '.$token
        ])->json('POST','api/products/upload',[
            'file' => UploadedFile::fake()->createWithContent(
                         'test.csv',
                         $content
                      ),
        ]);

        $response->assertStatus(201);

    }

    private function getUserJWTToken()
    {
        factory(\App\User::class,1)->create([
           'email' => 'test@mail.com',
           'password' => $this->password
        ]);
        $user = \App\User::where('email','test@mail.com')->first();
        return JWTAuth::fromUser($user);
    }

    private function getAdminJWTToken()
    {
        factory(\App\User::class,1)->create([
           'email' => 'director@mail.com',
           'password' => $this->password,
           'role' => 'admin'
        ]);
        $user = \App\User::where('email','director@mail.com')->first();
        return JWTAuth::fromUser($user);
    }
}
