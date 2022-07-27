<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class AuthTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_admin_register()
    {
        $request = [
            'name' => 'TesterAdmin',
            'email' => 'testeradmin1@gmail.com',
            'password' => 'Tester123',
        ];

        $response = $this->post('http://127.0.0.1:8000/api/register/admin',$request);

        $response->assertStatus(201);
    }

    public function test_user_register()
    {
        $request = [
            'name' => 'TesterUser',
            'email' => 'testeruser1@gmail.com',
            'password' => 'Tester123',
        ];

        $response = $this->post('http://127.0.0.1:8000/api/register/user',$request);

        $response->assertStatus(201);
    }

    public function test_login()
    {
        $request = [
            'email' => 'testeruser@gmail.com',
            'password' => 'Tester123',
        ];

        $response = $this->post('http://127.0.0.1:8000/api/login',$request);

        $response->assertStatus(200);
    }

    public function test_logout()
    {
        $user = User::where('email','testeruser@gmail.com')->first();

        $response = $this->actingAs($user)->post('http://127.0.0.1:8000/api/logout');

        $response->assertStatus(200);
    }
}
