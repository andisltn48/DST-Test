<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class ProductTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_create_product()
    {
        $user = User::where('role','Admin')->first();

        $request = [
            'name'=>'Beras Tester',
            'type'=>'Sembako',
            'price'=>'250000',
            'quantity'=>'10',
        ];

        $response = $this->actingAs($user)->post('http://127.0.0.1:8000/api/products', $request);

        $response->assertStatus(201);
    }

    public function test_update_product()
    {
        $user = User::where('role','Admin')->first();

        $request = [
            'name'=>'Beras Tester Setelah Update',
            'type'=>'Sembako 2',
            'price'=>'250000',
            'quantity'=>'10',
        ];

        $response = $this->actingAs($user)->post('http://127.0.0.1:8000/api/products/OVrYX', $request);

        $response->assertStatus(201);
    }

    public function test_delete_product()
    {
        $user = User::where('role','Admin')->first();

        $response = $this->actingAs($user)->delete('http://127.0.0.1:8000/api/products/OVrYX');

        $response->assertStatus(201);
    }

    public function test_get_product()
    {
        $user = User::where('role','Admin')->first();

        $response = $this->actingAs($user)->get('http://127.0.0.1:8000/api/products');

        $response->assertStatus(200);
    }

    public function test_get_detail_product()
    {
        $user = User::where('role','Admin')->first();

        $response = $this->actingAs($user)->get('http://127.0.0.1:8000/api/products/SMGtI');

        $response->assertStatus(200);
    }
}
