<?php

namespace Tests\Repository;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserRepositoryTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_a_new_user()
    {
        $response = $this->post('/user', [
            'role' => 'translator',
            'name' => 'John Doe',
            'company_id' => 1,
            'department_id' => 1,
            'email' => 'email@domain.com',
            'dob_or_orgid' => '1991-01-01',
            'phone' => '+92-123-1234',
            'mobile' => '+92-123-5678',
            'password' => 'password',
            'consumer_type' => 'paid',
            'customer_type' => 'personal',
            'username' => 'johndoe',
            'post_code' => '123456',
            'address' => '123 Islamabad',
            'city' => 'Islamabad',
            'town' => 'Blue Area',
            'country' => 'Pakistan',
            'reference' => 'yes',
            'additional_info' => 'Some additional info goes here',
            'cost_place' => 'E-11/4',
            'fee' => 99.00,
            'time_to_charge' => 30,
            'time_to_pay' => 30,
            'charge_ob' => 50.00,
            'customer_id' => '112233',
            'charge_km' => 5.00,
            'maximum_km' => 100.00,
            'translator_ex' => [1, 2, 3],
            'translator_type' => 'general',
            'worked_for' => 'yes',
            'organization_number' => '123456789',
            'gender' => 'male',
            'translator_level' => 'advanced'
        ]);

        $response->assertStatus(302);
        $this->assertDatabaseHas('users', ['email' => 'email@domain.com']);
    }

    /** @test */
    public function it_can_update_an_existing_user()
    {
        $user = factory(App\User::class)->create([
            'name' => 'Jane Doe',
            'email' => 'jane@domain.com',
            'password' => bcrypt('password')
        ]);

        $response = $this->put('/user/' . $user->id, [
            'name' => 'Jane Smith',
            'email' => 'jane.smith@domain.com',
            'password' => 'newpassword'
        ]);

        $response->assertStatus(302);
        $this->assertDatabaseHas('users', ['email' => 'jane.smith@domain.com']);
    }
}
