<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use App\Traits\ExportTrait;
use App\Exports\UsersExport;
use App\Imports\UsersImport;
use App\Models\User;
use App\Models\RoleUser;
use App\Models\Role;
use Auth;
use Exception;
use DataTables;
use Excel;
use Validator;
use DB;
use App\Traits\ReuseFunctionTrait;
use Mail;
use App\Models\UserProfilePhoto;
use App\Models\EmailTemplates;


class UserController extends Controller
{
    use ExportTrait;
    use ReuseFunctionTrait;

    /* ###########################################
    // Function: getUserList
    // Description: Display list of users
    // Parameter: request: Request
    // ReturnType: datatable object
    */ ###########################################
    public function getUserList(Request $request)
    {
        if($request->ajax()) {
            try {
                $parent_id = Auth::guard('admin')->user()->id;
                $user = \App\Models\User::select('users.id', 'users.firstname', 'users.lastname', 'users.email', DB::raw("date_format(users.created_at,'%Y-%m-%d %h:%i:%s') as user_created_at"), 'users.is_active', 'users.is_deleted', 'roles.role_title')
                    ->leftJoin('role_user','role_user.admin_id','=','users.id')
                    ->leftJoin('roles','roles.id','=','role_user.role_id')
                    ->where('users.parent_id', '=', $parent_id)
                    ->where('users.is_deleted', '=', 0)
                    ->where('users.user_type', '!=', 'frontend');
                    // ->get();

                if (isset($request->filter_role) && $request->filter_role != 'All' && $request->filter_role != '') {
                    $user = $user->where('role_user.role_id', '=', $request->filter_role);
                }

                $user = $user->get();
                // foreach ($user as $key => $value) {
                //     $user[$key]['user_created_at'] = getFormatedDate($user[$key]['user_created_at']);
                // }
                return Datatables::of($user)->make(true);
            } catch (\Exception $e) {
                return view('errors.500');
            }
        }
        $roles = \App\Models\Role::where('is_deleted',0)->get();
        return view('admin.users.user.list-users', compact('roles'));
    }
    public function getRegisteredUserList(Request $request)
    {
        if($request->ajax()) {
            // try {
            // $parent_id = Auth::guard('admin')->user()->id;
            $user = \App\Models\User::select('users.id', 'users.firstname', 'users.lastname', 'users.email', 'users.phone','users.area','users.city', DB::raw("date_format(users.created_at,'%Y-%m-%d %h:%i:%s') as user_created_at"), 'users.is_active', 'users.is_deleted')
                ->where('users.user_type', 'frontend')
                ->where('users.is_deleted', 0);
            if (isset($request->is_professional) && $request->is_professional != '') {
                $user = $user->where("users.is_professional",$request->is_professional);
            }
            if (isset($request->status) && $request->status != '') {
                $user = $user->where("users.is_active",$request->status);
            }
            $fromDate = $request->fromDate;
            $toDate = $request->toDate;
            if (!empty($fromDate) && !empty($toDate)) {
                // $user = $user->whereBetween(DB::raw("date_format(users.created_at,'%Y-%m-%d')"), array($fromDate, $toDate));
                $user = $user->whereBetween('users.created_at', array($fromDate, $toDate));
            }
            $user = $user->get();
            foreach ($user as $key => $u) {
                $u->profile_pic = UserProfilePhoto::getProfilePhoto($u->id);
            }
            return Datatables::of($user)->make();
            // } catch (\Exception $e) {
            //     return view('errors.500');
            // }
        }
        $roles = \App\Models\Role::where('is_deleted',0)->get();
        return view('admin.users.registered_users.list', compact('roles'));
    }

    /* ###########################################
    // Function: getUserForm
    // Description: Get add new user form
    // Parameter: No parameter
    // ReturnType: view
    */ ###########################################
    public function getUserForm()
    {
        $roles = \App\Models\Role::where('is_deleted',0)->get();
        return view('admin.users.user.add', compact('roles'));
    }
    public function getRegisteredUserForm()
    {
        // $roles = \App\Models\Role::get();
        return view('admin.users.registered_users.add');
    }

    /* ###########################################
    // Function: addUser
    // Description: Get add new user form
    // Parameter: first_name: String, last_name: String, phone: Int, email: String, password: String, select_role: Int, id: Int
    // ReturnType: none
    */ ###########################################
    public function addUser(Request $request)
    {
        // try {
        $parent_id = Auth::guard('admin')->user()->id;
        $user = \App\Models\User::where('email', $request->email)->where('is_deleted',0)->first();
        if($user)
        {
            return redirect()->back()->with('msg', config('message.AuthMessages.EmailExists'))->with('alert-class', false)->withInput();
        }

        $user = \App\Models\User::where('phone', $request->phone)->first();
        if($user){
            return redirect()->back()->with('msg', config('message.AuthMessages.PhoneExists'))->with('alert-class', false)->withInput();
        }

        $user = new \App\Models\User;
        $user->user_type = 'backend';
        $user->parent_id = $parent_id;
        $user->firstname = $request->first_name;
        $user->lastname = $request->last_name;
        $user->phone = $request->phone;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->is_active = 1;
        $user->is_verify = 1;
        $user->created_at = date("Y-m-d H:i:s");
        if($user->save())
        {
            $role_user = new \App\Models\RoleUser;
            $role_user->role_id = $request->select_role;
            $role_user->admin_id = $user->id;
            $role_user->save();

            $data = [
                'FIRST_NAME'=>$user->firstname,
                'LAST_NAME'=>$user->lastname,
                'PASSWORD'=> $request->password,
                'EMAIL' => $user->email
            ];
            EmailTemplates::sendMail('new-user',$data,$user->email);


            // Send email start
            // $temp_arr = [];
            // $new_user = $this->getEmailTemp();
            // foreach($new_user as $code )
            // {
            //     if($code->code == 'NUSER')
            //     {
            //         array_push($temp_arr, $code);
            //     }
            // }

            // if(is_array($temp_arr))
            // {
            //     $value = $temp_arr[0]['value'];
            // }

            // $replace_data = array(
            //     '{{name}}' => $user->firstname,
            //     '{{link}}' => url(config('app.adminPrefix').'/login'),
            // );
            // $html_value = $this->replaceHtmlContent($replace_data,$value);
            // $data = [
            //     'html' => $html_value,
            // ];
            // $subject = $temp_arr[0]['subject'];;
            // Mail::send('admin.emails.add-new-user-email', $data, function ($message) use ($email,$subject) {
            //     $message->from('no.reply.magneto123@gmail.com', 'Alboumi');
            //     $message->to($email)->subject($subject);
            // });
            // Send email over
        }
        $notification = array(
            'message' => config('message.Users.AddUserSuccess'),
            'alert-type' => 'success'
        );
        // return redirect()->back()->with('msg', config('message.Users.AddUserSuccess'))->with('alert-class', true);
        return redirect(config('app.adminPrefix').'/user/list')->with($notification);
        // } catch (\Exception $th) {
        //     return view('errors.500');
        // }
    }
    public function addRegisteredUser(Request $request)
    {
        // try {
        $parent_id = Auth::guard('admin')->user()->id;
        $user = \App\Models\User::where('email', $request->email)->where('is_deleted',0)->first();
        if($user)
        {
            return redirect()->back()->with('msg', config('message.AuthMessages.EmailExists'))->with('alert-class', false)->withInput();
        }

        $user = \App\Models\User::where('phone', $request->phone)->first();
        if($user){
            return redirect()->back()->with('msg', config('message.AuthMessages.PhoneExists'))->with('alert-class', false)->withInput();
        }
        $validator = Validator::make($request->all(), [
            'email'=>'required|email|regex:/(.+)@(.+)\.(.+)/i'
        ]);
        $user = new \App\Models\User;
        $user->user_type = 'frontend';
        $user->parent_id = $parent_id;
        $user->firstname = $request->first_name;
        $user->lastname = $request->last_name;
        $user->phone = $request->phone;
        $user->email = $request->email;
        $user->area = $request->area;
        $user->city = $request->city;
        $user->is_verify = 1;
        $user->is_active = 1;
        $user->password = Hash::make($request->password);
        // $user->is_active = 1;
        // $user->is_approve = 1;
        $user->created_at = date("Y-m-d H:i:s");
        if($user->save())
        {
            if($request->hasFile('profile_pic')) {
                $fileObject = $request->file('profile_pic');
                UserProfilePhoto::uploadAndSaveProfilePhoto($fileObject,$user->id);
            }
        }
        $notification = array(
            'message' => config('message.Users.AddUserSuccess'),
            'alert-type' => 'success'
        );
        // return redirect()->back()->with('msg', config('message.Users.AddUserSuccess'))->with('alert-class', true);
        return redirect(config('app.adminPrefix').'/registeredUsers/list')->with($notification);
        // } catch (\Exception $th) {
        //     return view('errors.500');
        // }
    }

    /* ###########################################
    // Function: editUser
    // Description: Get user information from user id
    // Parameter: id: Int
    // ReturnType: view
    */ ###########################################
    public function editUser($id)
    {
        $user = \App\Models\User::select('users.id', 'users.firstname', 'users.lastname', 'users.email', 'users.phone', 'roles.role_title', 'roles.id as role_id')
            ->leftJoin('role_user','role_user.admin_id','=','users.id')
            ->leftJoin('roles','roles.id','=','role_user.role_id')
            ->where('role_user.admin_id',$id)
            ->first();
        $roles = \App\Models\Role::where('is_deleted',0)->get();
        return view('admin.users.user.edit', compact('roles', 'user'));
    }
    public function editRegisteredUser($id)
    {
        $user = \App\Models\User::select('users.id', 'users.firstname', 'users.lastname', 'users.email', 'users.phone','users.area','users.city')
            // ->leftJoin('role_user','role_user.admin_id','=','users.id')
            // ->leftJoin('roles','roles.id','=','role_user.role_id')
            ->where('users.id',$id)
            ->first();
        // $roles = \App\Models\Role::get();
        return view('admin.users.registered_users.edit', compact('user'));
    }

    /* ###########################################
    // Function: exportUsers
    // Description: Exporting Users to xlsx file
    // Parameter: No parameter
    // ReturnType: mime type
    */ ###########################################
    public function exportUsers()
    {
        try{
            return Excel::download(new UsersExport, 'fanclub Ltd._Users.xlsx');
        } catch(\Exception $ex) {
            return view('errors.500');
        }
    }

    /* ###########################################
    // Function: exportUsers
    // Description: Display import form for user
    // Parameter: No parameter
    // ReturnType: view
    */ ###########################################
    public function getimportUsersForm()
    {
        return view('admin.users.user.import');
    }

    /* ###########################################
    // Function: importUser
    // Description: Display import form for user
    // Parameter: import_user_file: File
    // ReturnType: view
    */ ###########################################
    public function importUser(Request $request)
    {
        try{
            if($request->hasFile('import_user_file'))
            {
                $import = new UsersImport;
                Excel::import($import, $request->file('import_user_file'));
                $collection = $import->getCommon();

                $counter = 0;
                $errors = [];
                $suc_uploaded = [];
                $fail_uploaded = [];
                foreach($collection as $row)
                {
                    $email_arr = \App\Models\User::select('*')->pluck('email')->toArray();
                    $counter++;
                    $flag = 'true';
                    if($row[0] == "" || $row[1] == "" || $row[2] == "" || $row[3] == "" || $row[4] == "" || $row[5] == "")
                    {
                        $errors[] = "Record is incomplete for Row - ".$counter.". Please try again.";
                        $flag = 'false';
                    }

                    if(in_array($row[3], $email_arr))
                    {
                        $errors[] = $row[3]. " is already exist. Please use different email.";
                        $flag = 'false';
                    }

                    if(!in_array($row[3], $email_arr))
                    {
                        if (!filter_var($row[3], FILTER_VALIDATE_EMAIL)) {
                            $errors[] = $row[3]. " is Invalid.";
                            $flag = 'false';
                        }
                    }

                    if (strlen($row[5]) < 6) {
                        $errors[] = 'Password for '. $row[3] . ' should be 6 digits.';
                        $flag = 'false';
                    }

                    if($flag == 'true')
                    {
                        $user = new \App\Models\User;
                        if ($row[4] != '') {
                            $role = \App\Models\Role::where('role_title', $row[4])->first();
                        }
                        $user->firstname =  $row[0];
                        $user->lastname = $row[1];
                        $user->email = $row[3];
                        $user->phone = $row[2];
                        $user->parent_id = Auth::guard('admin')->user()->id;
                        $user->password = Hash::make($row[5]);
                        if($user->save())
                        {
                            $role_user = new \App\Models\RoleUser;
                            $role_user->role_id = $role->id;
                            $role_user->admin_id = $user->id;
                            $role_user->save();
                            $suc_uploaded[] = $counter;
                        }
                    }
                    else
                    {
                        $fail_uploaded[] = $counter;
                    }
                }
                return redirect()->back()->with('msg', $errors)->with('success', $suc_uploaded)->with('faile', $fail_uploaded);
            }
        } catch(\Maatwebsite\Excel\Validators\ValidationException $ex) {
            return view('errors.500');
        }

    }

    /* ###########################################
    // Function: deleteUser
    // Description: Delete exiting user
    // Parameter: id: Int, is_deleted: Int
    // ReturnType: array
    */ ###########################################
    public function deleteUser($user_id, Request $request)
    {
        // echo "string";die;
        $is_deleted = $request->is_deleted;
        $user = \App\Models\User::where('id', $user_id)->first();
        if($user)
        {
            // if($is_deleted == 0)
            // {
                $user->is_deleted = 1;
                $user->email = $user->email.'deleted'.now();
                $user->phone = $user->phone.'deleted'.now();
                // $user->handle = $user->handle.'deleted'.now();
                // $user->deleted_at = date('Y-m-d H:i:s');
            // }
            $user->save();
            $user->delete();
            $result['status'] = 'true';
            return $result;
        }
        else
        {
            $result['status'] = 'false';
            return $result;
        }
    }

    /* ###########################################
    // Function: userActDeaAct
    // Description: Delete exiting user
    // Parameter: user_id: Int, is_active: Int
    // ReturnType: array
    */ ###########################################
    public function userActDeaAct(Request $request)
    {
        try {
            $user = \App\Models\User::where('id',$request->user_id)->first();
            if($request->is_active == 1)
            {
                $user->is_active = $request->is_active;
                $msg = "User Activated Successfully!";
            }
            else
            {
                $user->is_active = $request->is_active;
                $msg = "User Deactivated Successfully!";
            }
            $user->save();
            $result['status'] = 'true';
            $result['msg'] = $msg;
            return $result;
        } catch(\Exception $ex) {
            return view('errors.500');
        }
    }

    /* ###########################################
    // Function: updateUser
    // Description: Update existing user information
    // Parameter: first_name: String, last_name: String, phone: Int, email: String, password: String, select_role: Int, id: Int
    // ReturnType: none
    */ ###########################################
    public function updateUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'profile_pic' => 'mimes:jpeg,jpg,png,gif',
            'cover_pic' => 'mimes:jpeg,jpg,png,gif',
            'category_hero' => 'mimes:jpeg,jpg,png,gif',
            'location_hero' => 'mimes:jpeg,jpg,png,gif',
            'phone' => 'required',
            // 'phone' => 'required|unique:users,phone,' . $request->user_id,
        ]);

        if($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        try {
            $parent_id = Auth::guard('admin')->user()->id;
            $user = \App\Models\User::where('id', $request->user_id)->where('parent_id', $parent_id)->first();
            $user->firstname = $request->first_name;
            $user->lastname = $request->last_name;
            $user->phone = $request->phone;
            $user->email = $request->email;
            if($user->save())
            {
                return redirect()->back()->with('msg', config('message.Users.AddUserSuccess'))->with('alert-class', true);
                $role_user = \App\Models\RoleUser::where('admin_id', $user->id)->first();
                $role_user->role_id = $request->select_role;
                $role_user->save();
            }
            $notification = array(
                'message' => config('message.Users.UpdateUserSuccess'),
                'alert-type' => 'success'
            );
            // return redirect()->back()->with('msg', config('message.Users.UpdateUserSuccess'))->with('alert-class', true);
            return redirect(config('app.adminPrefix').'/user/list')->with($notification);
        } catch (\Exception $th) {
            return view('errors.500');
        }
    }
    public function updateRegisteredUser(Request $request)
    {
        // try {
        $parent_id = Auth::guard('admin')->user()->id;
        $emailExists = \App\Models\User::where('email', $request->email)->where('id','!=',$request->user_id)->where('is_deleted',0)->first();
        if($emailExists)
        {
            return redirect()->back()->with('msg', config('message.AuthMessages.EmailExists').' ('.$request->email.')')->with('alert-class', false)->withInput();
        }

        $user = \App\Models\User::where('phone', $request->phone)->where('id','!=',$request->user_id)->first();
        if($user){
            return redirect()->back()->with('msg', config('message.AuthMessages.PhoneExists'))->with('alert-class', false)->withInput();
        }
        $user = \App\Models\User::where('id', $request->user_id)->first();
        $user->firstname = $request->first_name;
        $user->lastname = $request->last_name;
        $user->phone = $request->phone;
        $user->email = $request->email;
        $user->area = $request->area;
        $user->city = $request->city;
        if($user->save())
        {
            if($request->hasFile('profile_pic')) {
                $fileObject = $request->file('profile_pic');
                UserProfilePhoto::uploadAndSaveProfilePhoto($fileObject,$user->id);
            }
        }
        $notification = array(
            'message' => config('message.Users.UpdateUserSuccess'),
            'alert-type' => 'success'
        );
        // return redirect()->back()->with('msg', config('message.Users.UpdateUserSuccess'))->with('alert-class', true);
        return redirect(config('app.adminPrefix').'/fan/list')->with($notification);
        // } catch (\Exception $th) {
        //     return view('errors.500');
        // }
    }

    public function getPermissions($id, Request $request)
    {
        $user = User::find($id);
        $userRole = RoleUser::where('admin_id',$id)->first();
        if ($userRole) {
            $hasPermission = \App\Models\PermissionUser::where('user_id',$id)->get()->toArray();
            $role = Role::findOrFail($userRole->role_id);
            if ($request->method() == 'GET') {
                if($id == 1) {
                    return View('errors.401');
                } else {
                    $permissions = \App\Models\Permission::get();
                    $arrPermissions = array();

                    foreach ($permissions as $key => $permission) {
                        $arrPermissions[$permission->permission_group][$key] = $permission;
                    }
                    $checkRole = ($hasPermission)?0:1;
                    return view('admin.users.user.permissions', compact('role','checkRole','user', 'arrPermissions'));
                }
            }

            if (!$hasPermission) {
                $hasPermission = \App\Models\PermissionRole::where('role_id',$userRole->role_id)->get();
                if ($hasPermission) {
                    foreach ($hasPermission as $key => $value) {
                        $copyPerm = new \App\Models\PermissionUser;
                        $copyPerm->permission_id = $value->permission_id;
                        $copyPerm->user_id = $id;
                        $copyPerm->save();
                    }
                }
            }

            // $role->permissions()->sync(json_decode($request->permission), true);
            $permission_user = \App\Models\PermissionUser::where('permission_id',$request->permission)->where('user_id',$id)->first();
            if($permission_user)
            {
                $permission_user->delete();
                return "success";
            }
            else
            {
                $permission_user = new \App\Models\PermissionUser;
                $permission_user->permission_id = $request->permission;
                $permission_user->user_id = $id;
                $permission_user->save();
                return "success";
            }
        }
    }
}
