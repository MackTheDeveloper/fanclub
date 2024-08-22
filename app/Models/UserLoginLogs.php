<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Auth;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;

class UserLoginLogs extends Model
{
    use SoftDeletes;
    protected $table = 'user_login_logs';

    protected $fillable = ['user_id', 'flag', 'operating_system', 'ip_address', 'login_at', 'logout_at', 'session_id'];

    public function setLogOutLog()
    {
        $this->where('session_id', request()->session()->getId())->update([
            'logout_at' => Carbon::now(),
            'flag' => '2'
        ]);
    }

    public function setLogInLog()
    {
        if (Auth::user()) {
            $this->insert(
                [
                    'user_id' => Auth::user()->id,
                    'login_at' => Carbon::now(),
                    'flag' => '1',
                    'created_at' => Carbon::now(),
                    'ip_address' => request()->getClientIp(),
                    'session_id' => request()->session()->getId()
                ]
            );
        }
    }

    public function setLoginLogForApi($event)
    {
        $this->insert(
            [
                'user_id' => $event->userId,
                'login_at' => Carbon::now(),
                'flag' => '1',
                'created_at' => Carbon::now(),
                'ip_address' => request()->getClientIp(),
                //'session_id' => request()->session()->getId()
                'session_id' => $event->tokenId
            ]
        );
    }

    public function setLogoutLogForApi($sessionId)
    {
        $this->where('session_id', $sessionId)->update([
            'logout_at' => Carbon::now(),
            'flag' => '2'
        ]);
    }
}
