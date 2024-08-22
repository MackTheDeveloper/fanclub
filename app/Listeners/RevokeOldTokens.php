<?php

namespace App\Listeners;

use Laravel\Passport\Events\AccessTokenCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\UserLoginLogs;
class RevokeOldTokens
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
     * @param  AccessTokenCreated  $event
     * @return void
     */
    public function handle(AccessTokenCreated $event)
    {
        $this->UserLoginHistory->setLoginLogForApi($event);
    }
}
