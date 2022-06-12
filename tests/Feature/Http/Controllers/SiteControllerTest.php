<?php

namespace Tests\Feature\Http\Controllers;

use Tests\TestCase;
use App\Models\Site;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SiteControllerTest extends TestCase
{

    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_it_creates_site()
    {
        // laravel won't handle the exeption so we will have the full information
        $this->withoutExceptionHandling();
        // create a user
        $user = User::factory()->create();
        // user make a  post request to a route to create a site
        $response = $this
        ->followingRedirects()
        ->actingAs($user)
        ->post(route('sites.store'), [
            'name' => 'Google',
            'url' => 'https://www.google.com.au'
        ]);
        // make sure that the site exist within the DB
        $site  = Site::first();
        $this->assertEquals(1, Site::count());
        $this->assertEquals('Google', $site->name);
        $this->assertEquals('https://www.google.com.au', $site->url);
        $this->assertNull($site->is_online);
        $this->assertEquals($user->id, $site->user->id);
        // make sure see site name on the page
        $response->assertSeeText('Google');
        // make sure we are in the right url
        $this->assertEquals(route('sites.show', $site), url()->current());
    }


    public function test_it_only_allow_authenticated_users_to_create_sites()
    {
        // user make a  post request to a route to create a site
        $response = $this
        ->followingRedirects()
        ->post(route('sites.store'), [
            'name' => 'Google',
            'url' => 'https://www.google.com.au'
        ]);
        // make sure no site exists in the DB
        $site  = Site::first();

        // make sure see the login on the page, because we will be redirect to the login page
        $response->assertSeeText('Log in');
        // make sure we are in the login page
        $this->assertEquals(route('login'), url()->current());
    }
}
