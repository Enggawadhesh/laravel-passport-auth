<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testExample()
    {
        // $this->assertTrue(true);

        $data = [
	        'name' => 'Unit Test',
	        'email' => 'unittest'.rand(10,99).'@test.com',
	        'mobile' => '9988776655',
	        'username' => 'unittest'.rand(10,99),
	        'password' => 'password',
	      ];
	    
	    $this->post(route('createuser'), $data)
	        ->assertStatus(201);
	        // ->assertJson($data);
    }
}
