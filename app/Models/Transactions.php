<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transactions extends Model
{
    use HasFactory;
    protected $table = 'transactions';
    protected $fillable = [
        'id',
        'customer_id',
        'name',
        'email',
        'phone',
        'plan',
        'amount',
        'status',
        'payment_id',
        'subscription_id',
        'failed_message',
        'card_name',
        'card_number',
        'card_expiry',
        'ip_address',
    ];

    public static function getDaywiseCount($date){
        //return self::whereDate('created_at',$date)->count();

        $return = self::selectRaw('SUM(amount) as total')->whereDate('created_at',$date)->first();
        return (int) $return->total;
    }

    public static function getMonthwiseCount($month, $year){
        //return self::whereDate('created_at',$date)->count();

        $return = self::selectRaw('SUM(amount) as total')->whereMonth('created_at',$month)->whereYear('created_at', $year)->first();
        return (int) $return->total;
    }

    public static function getYearwiseCount($year){
        //return self::whereDate('created_at',$date)->count();

        $return = self::selectRaw('SUM(amount) as total')->whereYear('created_at', $year)->first();
        return (int) $return->total;
    }

    public static function getSearchData($search = '', $limit = 0, $operation = '')
    {
        $return = self::selectRaw('transactions.id,CONCAT(firstname," ",lastname) as fullName,transactions.email,transactions.payment_id')->leftjoin('users','users.id','transactions.customer_id')
        //->where('status', '1')
        ->whereNull('users.deleted_at')
        ->whereNull('transactions.deleted_at')
        ->where(function ($query2) use ($search) {
            $query2->where('firstname', 'like', '%' . $search . '%')
                ->orWhere('lastname', 'like', '%' . $search . '%')
                ->orWhere('transactions.email', 'like', '%' . $search . '%')
                ->orWhere('transactions.payment_id', 'like', '%' . $search . '%');
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
