<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\UserLoginLogs;
class LoginLogs
{
    private $UserLoginHistory; 
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(UserLoginLogs $UserLoginHistory)
    {
        $this->UserLoginHistory = $UserLoginHistory;
    }

    /**
     * Handle the event.
     *
     * @param  Login  $event
     * @return void
     */
    public function handle(Login $event)
    {
        $this->UserLoginHistory->setLogInLog();
    }
}
