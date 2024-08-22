<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Mail;
use Illuminate\Database\Eloquent\SoftDeletes;
use File;
use Image;

class EmailTemplates extends Model
{
		use SoftDeletes;
    protected $table = 'email_templates';

    protected $fillable = ['title', 'slug', 'subject', 'body','is_active', 'deleted', 'created_at', 'updated_at','deleted_at'];


    public static function getTemplate($slug){
 		$return = self::where('slug',$slug)->first();
 		return ($return)?:[];
    }

	
	public static function sendMail($slug,$data,$to){
		$template = self::getTemplate($slug);
		if ($template) {
			$mailBody = $template->body;
			$variables = [];
			$values = [];
			foreach ($data as $key => $value) {
				$variables[] = "{".$key."}";
				$values[] = $value;
			}
			$mailBody = str_replace($variables, $values, $mailBody);
			$ccEmails = [];
			$ccList = EmailTemplatesCc::selectRaw('email_cc')->where('template_id',$template->id)->get()->toArray();

			foreach ($ccList as $key => $value) {
				$ccEmails[] = $value['email_cc'];
			}

			try {
				Mail::send([], [], function ($message) use($mailBody,$template,$to,$ccEmails) {
				  	$message->to($to)
				    // ->cc($ccEmails)
				    ->subject($template->subject)
				    ->setBody($mailBody, 'text/html'); // for HTML rich messages
				});
			} catch (\Exception $e) {
				// pre('asd');
			}
			return 1;

		}
	}


	public static function uploadCKeditorImage($request)
	{
		$fileObject = $request->file('upload');
		$photo = $fileObject;
        $ext = $fileObject->extension();
        $filename = rand().'_'.time().'.'.$ext;
        $filePath = public_path().'/assets/images/ckimages';
        if (! File::exists($filePath)) {
            File::makeDirectory($filePath);
        }

        $img = Image::make($photo->path());
        // $img->resize(50, 50, function ($const) {
        //     $const->aspectRatio();
        // })->save($filePath.'/'.$filename);
        $photo->move($filePath.'/', $filename);

        $CKEditorFuncNum = $request->input('CKEditorFuncNum');
		$url = asset('/public/assets/images/ckimages/'.$filename); 
		$msg = 'Image uploaded successfully'; 
		$response = "<script>window.parent.CKEDITOR.tools.callFunction($CKEditorFuncNum, '$url', '$msg')</script>";
		   
		@header('Content-type: text/html; charset=utf-8'); 
		echo $response;
	}
}
