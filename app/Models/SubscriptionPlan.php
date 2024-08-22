<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubscriptionPlan extends Model
{
    use HasFactory;
    protected $table = 'subscription__plans';
    protected $fillable = [
        'subscription_name',
        'duration',
        'price',
        'description',
        'type',
        'deleted_at'
    ];

    public function fan()
    {
        return $this->belongsTo(Fan::class);
    }

    public static function getDetails($id)
    {
        return self::find($id);
    }

    public static function getList()
    {
        return self::pluck('subscription_name', 'id');
    }

    public static function getListApi()
    {
        $return = [];
        $data = self::where('type',2)->get();
        if ($data) {
            foreach ($data as $key => $value) {
                $return[] = [
                    "isSelected" => $key ? "0" : "1",
                    'id' => strval($value->id),
                    'title' => $value->subscription_name,
                    'description' => $value->description,
                    'price' => $value->price,
                    'duration' => $value->duration,
                ];
            }
        }
        return $return;
    }

    public static function getAnnualSubscriptionData()
    {
        $return = [];
        $data = self::where('type', 2)->get();
        if ($data) {
            foreach ($data as $key => $value) {
                $titleExploaded = explode('/', $value->subscription_name);
                $return = [
                    'id' => strval($value->id),
                    'title' => $value->subscription_name,
                    'type' => $value->type,
                    'description' => $value->description,
                    'price' => $value->price,
                    'duration' => $value->duration,
                ];
            }
        }
        return $return;
    }

    public static function getBenifits(){
        $return = [];
        $benefits = [
           /*  "0" => "Exclusive Content",
            "1" => "Access to all Artists",
            "2" => "Unlimited Download",
            "3" => "Quality Music & Video Streaming",
            "4" => "Artist News & Event Calendar",
            "5" => "Create Fully Customisable Playlists" */
            "0" => "Downloads",
            "1" => "Artist News",
            "2" => "Gig Guides",
            "3" => "Customisable Playlists",
            "4" => "Fan Forum",
            "5" => "Latest Reviews",
            "6" => "Fan / Artist Mailbox"
        ];
        foreach ($benefits as $key => $value) {
            $return[] = [
                "title"=> $value,
                "month"=>1,
                "year"=>1
            ];
        }
        return $return;
    }
}
