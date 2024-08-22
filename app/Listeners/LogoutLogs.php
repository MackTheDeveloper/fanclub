<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Logout;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\UserLoginLogs;
class LogoutLogs
{
    private $UserLogoutHistory; 

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(UserLoginLogs $UserLogoutHistory)
    {
        $this->UserLogoutHistory = $UserLogoutHistory;
    }

    /**
     * Handle the event.
     *
     * @param  Logout  $event
     * @return void
     */
    public function handle(Logout $event)
    {
        $this->UserLogoutHistory->setLogOutLog();
    }
}
