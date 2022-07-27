<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class TransactionTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_create_transaction()
    {
        $user = User::where('role','User')->first();

        $request = [
            'amount'=>1
        ];

        $response = $this->actingAs($user)->post('http://127.0.0.1:8000/api/transactions/rAPOb', $request);

        $response->assertStatus(201);
    }

    public function test_get_transaction()
    {
        $user = User::where('role','User')->first();

        $response = $this->actingAs($user)->get('http://127.0.0.1:8000/api/transactions');

        $response->assertStatus(200);
    }

    public function test_get_detail_transaction()
    {
        $user = User::where('role','User')->first();

        $response = $this->actingAs($user)->get('http://127.0.0.1:8000/api/transactions/enKCV');

        $response->assertStatus(200);
    }
}
