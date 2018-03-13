<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use DatabaseTransactions;

    public function test_can_user_login()
    {
        $login = Auth::loginUsingId(3);
        $this->assertSame(3, $login->id);
        $response = $this->get('/home')->assertSee('Корзина');
        $response->assertStatus(200);
    }

    public function test_user_is_admin_access_to_admin_panel()
    {
        $login = Auth::loginUsingId(3);
        $this->assertSame(3, $login->id);
        $response = $this->get('/control')->assertSee('Панель управления');
        $response->assertStatus(200);
    }

    public function test_user_is_not_admin_has_no_access_to_admin_panel()
    {
        $login = Auth::loginUsingId(5);
        $this->assertSame(5, $login->id);
        $response = $this->get('/control');
        $response->assertDontSee('Панель управления');
        $response->assertStatus(302);
    }

    public function test_if_user_has_access_to_platform_make_page_available()
    {
        $login = Auth::loginUsingId(3);
        $this->assertEquals(1, $login->access);
        $response = $this->get('/tires');
        $response->assertSee('Подбор шин');

    }
}
