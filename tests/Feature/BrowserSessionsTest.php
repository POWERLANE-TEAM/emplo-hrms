<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Livewire\Livewire;
use App\Livewire\Profile\LogoutOtherBrowserSessionsForm;

class BrowserSessionsTest extends TestCase
{

    public function test_other_browser_sessions_can_be_logged_out(): void
    {
        $this->actingAs(User::factory()->create());

        Livewire::test(LogoutOtherBrowserSessionsForm::class)
            ->set('password', 'password')
            ->call('logoutOtherBrowserSessions')
            ->assertSuccessful();
    }
}
