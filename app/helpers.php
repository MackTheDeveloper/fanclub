<?php 

use App\Models\Role;
use App\Models\Permission;
use App\Models\PermissionRole;
use App\Models\PermissionUser;

/**
 * This function is use to checked role based permissions
 * @param array $role_type
 * @param $permission_slug
 * @return bool
 */
function whoCanCheck($role_type = array(), $permission_slug)
{
    if (Auth::guard('admin')->user()->parent_id == 0 && Auth::guard('admin')->user()->user_type == 'backend') {
        return true;
    }else{
        $current_user = (Auth::guard('admin')->check()) ? Auth::guard('admin')->user()->id : Auth::guard('photographer')->user()->id;
        // pre($current_user);
        $obj = new Role;
        $role_type = $obj->getCurrentRole('role_type');
        $role_id = $obj->getCurrentRole('id');
        // $role_type = Auth::$segment()->get()->role->first()->toArray();
        // $results = Permission::select('roles.role_type', 'permissions.permission_slug')
        //     ->join('permission_role', function ($join) use ($permission_slug) {
        //         $join->on('permissions.id', '=', 'permission_role.permission_id');
        //     })
        //     ->join('role_user', function ($join) {
        //         $join->on('role_user.role_id', '=', 'permission_role.role_id');
        //     })
        //     ->join('roles', function ($join) {
        //         $join->on('roles.id', '=', 'permission_role.role_id');
        //     })
        //     ->where('permissions.permission_slug', '=', $permission_slug)
        //     ->where('roles.role_type', '=', $role_type)
        //     ->where('role_user.admin_id', '=', $current_user)
        //     ->get()
        //     ->toArray();

        // if (count($results) > 0) {
        //     foreach ($results as $result) {
        //         if (in_array($result['role_type'], (array) $role_type)) {
        //             return true;
        //         } else {
        //             return false;
        //         }
        //     }
        // } else {
        //     return false;
        // }

        $permission = Permission::getIdBySlug($permission_slug);
        if ($permission) {
            $hasAccess = false;
            $userPermission = PermissionUser::where('user_id',$current_user)->first();
            if ($userPermission) {
                $rolePermission = PermissionUser::where('user_id',$current_user)->where('permission_id',$permission['id'])->first();
                if ($rolePermission) {
                    $hasAccess = true;
                }
            }else{
                $rolePermission = PermissionRole::where('role_id',$role_id)->where('permission_id',$permission['id'])->first();
                if ($rolePermission) {
                    $hasAccess = true;
                }
            }

            if ($hasAccess) {
                return true;
            } else {
                return false;
            }
        }
    }
}

/**
 * This function is used to generate Unique Id by getting last id from database table
 * @param $last_unique_id
 * @param string $prefix
 * @return mixed|string
 */
function getIdByLastUniqueId($last_unique_id, $prefix = '')
{
    if (!empty($last_unique_id)) {
        $uniqueId = preg_replace("/[^0-9]/", "", $last_unique_id);
        $uniqueId = $prefix . str_pad($uniqueId + 1, 5, '0', STR_PAD_LEFT);
    } else {
        $uniqueId = $prefix . str_pad(1, 5, '0', STR_PAD_LEFT);
    }
    return $uniqueId;
}