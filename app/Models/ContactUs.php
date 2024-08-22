<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use App\User;
use Carbon\Carbon;


class ContactUs extends Model
{
    use SoftDeletes;
    
    protected $table = 'contact_us';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name','last_name','email','phone','message','ip_address','os_name','browser_name','browser_version', 'is_replied'
    ];
    public function contactUsReply(){
        return $this->hasOne('App\Models\ContactUsReply', 'contact_us_id', 'id');
    }

    public function getCreatedAtAttribute($date){
        return Carbon::parse($date)->format('Y-m-d');
        // return date('Y-m-d H:i:s', strtotime($date));
    }


    public static function addNew($data){
        $return = '';
        $success = true;

        try{
            $create = new ContactUs();
            foreach ($data as $key => $value) {
                $create->$key = $value;
            }
            $create->ip_address = $create->ip_address?:($_SERVER['SERVER_ADDR']?:'');
            $create->save();
            $return = $create;
            try {
                $data = ['NAME'=>$create->first_name,'EMAIL'=>$create->email,'MESSAGE'=>$create->message];
                $adminEmails = ['akeating@fanclubltd.com', 'dmagennis@fanclubltd.com', 'sunny@magnetoitsolutions.com'];
                EmailTemplates::sendMail('contact-us-mail-admin',$data,$adminEmails);

                $data = ['NAME'=>$create->first_name];
                EmailTemplates::sendMail('contact-us-mail-users',$data,$create->email);
            } catch (\Exception $e) {
                
            }
        }catch(\Exception $e){
            $return = $e->getMessage();
            $success = false;
        }
        return ['data'=>$return,'success'=>$success];
    }

}