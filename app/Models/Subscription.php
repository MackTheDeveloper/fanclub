<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use HasFactory;
    protected $table = 'subscriptions';
    protected $fillable = [
        'id',
        'customer_id',
        'email',
        'phone',
        'subscription_plan',
        'amount',
        'status',
        'payment_id',
        'start_date',
        'end_date',
        'is_pending'
    ];

    public static function getDaywiseCount($date)
    {
        return self::whereDate('created_at', $date)->count();
    }

    public static function getSubscriptionDetails()
    {
        return self::whereNull('subscriptions.deleted_at')->select(
            'subscriptions.*',
            'users.firstname',
            'subscriptions.phone as phone',
            'subscription__plans.duration',
            'subscription__plans.price as amount',
            'subscriptions.status as status',
            'subscription__plans.subscription_name',
            'payment_id',
            'subscriptions.created_at'
        )
            ->leftJoin("subscription__plans", "subscriptions.subscription_plan", "subscription__plans.id")
            ->leftJoin("users", "subscriptions.customer_id", "users.id");
    }

    public static function getSearchData($search = '', $limit = 0, $operation = '')
    {
        $return = self::selectRaw('subscriptions.id,firstname,CONCAT(firstname," ",lastname) as fullName,subscriptions.email,subscriptions.payment_id')->leftjoin('users', 'users.id', 'subscriptions.customer_id')
            ->where('status', '1')
            ->whereNull('users.deleted_at')
            ->whereNull('subscriptions.deleted_at')
            ->where(function ($query2) use ($search) {
                $query2->where('firstname', 'like', '%' . $search . '%')
                    ->orWhere('lastname', 'like', '%' . $search . '%')
                    ->orWhere('subscriptions.email', 'like', '%' . $search . '%')
                    ->orWhere('subscriptions.payment_id', 'like', '%' . $search . '%');
            });
        if ($limit) {
            $return->limit($limit);
        }
        if ($operation == 'getTotal') {
            $return = $return->count();
        } else {
            $return = $return->get();
        }
        return $return;
    }
}
