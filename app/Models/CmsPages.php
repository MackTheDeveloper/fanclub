<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class CmsPages extends Model
{
    protected $table = 'cms_pages';
    protected $fillable = ['name', 'slug', 'content', 'seo_title', 'seo_meta_keyword', 'seo_description', 'deleted', 'deleted_at', 'created_at', 'updated_at', 'is_active'];

    public static function getBySlug($slug)
    {
        $data = CmsPages::where('slug', $slug)->where('is_active', 1)->whereNull('deleted_at')->first();
        return $data;
    }

    public static function getSearchData($search = '', $limit = 0, $operation = '')
    {
        $return = self::selectRaw('id,name,slug')
            ->whereNull('deleted_at')
            ->where(function ($query2) use ($search) {
                $query2->where('name', 'like', '%' . $search . '%')
                    ->orWhere('slug', 'like', '%' . $search . '%');
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

    public static function getDataForFooter($ids)
    {
        $data = self::selectRaw('id,name,slug')->whereNull('deleted_at')->whereIn('id', explode(',', $ids))->get();
        $return = array();
        
        foreach ($data as $k => $v) {
            $route = Request::create($v->slug);
            // pre($route);
            $check = self::checkRoute($route);
            $return[$v->name] = $check ? url($v->slug) : '#';
        }
        return $return;
    }


    public static function checkRoute($route)
    {
        // pre($route->getRequestUri());
        $routes = Route::getRoutes();
        foreach ($routes as $r) {
            if ($r->uri == $route->getRequestUri()) {
                return true;
            } elseif (strpos($r->uri, $route->getRequestUri() . '/') !== false) {
                return true;
            }
        }
        return false;
    }
}
