<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class RequestTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testRequestMainPage()
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertViewIs('welcome');
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testRequestMainPageWithAuth()
    {
        $user = User::find(1);

        $response = $this->actingAs($user)
            ->get('/');
        $response->assertStatus(302);
        $response->assertRedirect('/kanban/board');
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testRequestloginPage()
    {
        $response = $this->get('/user/login');
        $response->assertStatus(200);
        $response->assertViewIs('user.login');
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testRequestMainLoginWithAuth()
    {
        $user = User::find(1);

        $response = $this->actingAs($user)
            ->get('/user/login');
        $response->assertStatus(302);
        $response->assertRedirect('/kanban/board');
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testRequestSignUpPage()
    {
        $response = $this->get('/user/sign-up');
        $response->assertStatus(200);
        $response->assertViewIs('user.sign-up');
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testRequestMainSignUpWithAuth()
    {
        $user = User::find(1);

        $response = $this->actingAs($user)
            ->get('/user/sign-up');
        $response->assertStatus(302);
        $response->assertRedirect('/kanban/board');
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testRequestSendMailPage()
    {
        $response = $this->get('/user/send-mail');
        $response->assertStatus(200);
        $response->assertViewIs('user.send-mail-password');
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testRequestMainSendMailWithAuth()
    {
        $user = User::find(1);

        $response = $this->actingAs($user)
            ->get('/user/sign-up');
        $response->assertStatus(302);
        $response->assertRedirect('/kanban/board');
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testRequestMainAuthPage()
    {
        $response = $this->get('/kanban/board');
        $response->assertStatus(302);
        $response->assertRedirect('/user/sign-up');
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testRequestMainAuth()
    {
        $user = User::find(1);

        $response = $this->actingAs($user)
            ->get('/kanban/board');
        $response->assertStatus(200);
        $response->assertViewIs('app.kanban');
    }
}
