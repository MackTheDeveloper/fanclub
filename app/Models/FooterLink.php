<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FooterLink extends Model
{
    use HasFactory;
    protected $table = 'footer_links';

    protected $fillable = ['relation_data','name','type','sort_order','is_active','deleted_at'];

    public static $types = [
        'cms' => 'CMS',
        'artist' => 'Artist',
        'category' => 'Category',
        // 'genre' => 'Genre',
        // 'language' => 'Language',
        'dynamicgroup' => 'Dynamic Group',
    ];
    public static function getTypes()
    {
        return self::$types;
    }

    public static function getSortOrder(){
        $return = self::selectRaw('sort_order')->where('is_active',1)->orderBy('sort_order','desc')->first();
        return $return?$return->sort_order+1:1;
    }


    public static function getFooterData()
    {
        $dataFooter = self::selectRaw('name,type,relation_data')->where('deleted_at',null)->where('is_active','1')->orderBy('sort_order','asc')->limit(4)->get();

        $return = array();
        foreach($dataFooter as $k => $v)
        {
            if($v->type == 'cms')
            {
                $return[$k]['footerDetails']['footerName'] = $v->name;
                $return[$k]['footerDetails']['footerType'] = $v->type;
                $return[$k]['footerMenuData'] = CmsPages::getDataForFooter($v->relation_data);
            }else if($v->type == 'artist')
            {
                $return[$k]['footerDetails']['footerName'] = $v->name;
                $return[$k]['footerDetails']['footerType'] = $v->type;
                $return[$k]['footerMenuData'] = Artist::getDataForFooter($v->relation_data);
            }else if($v->type == 'genre')
            {
                $return[$k]['footerDetails']['footerName'] = $v->name;
                $return[$k]['footerDetails']['footerType'] = $v->type;
                $return[$k]['footerMenuData'] = MusicGenres::getDataForFooter($v->relation_data);
            }else if($v->type == 'category')
            {
                $return[$k]['footerDetails']['footerName'] = $v->name;
                $return[$k]['footerDetails']['footerType'] = $v->type;
                $return[$k]['footerMenuData'] = MusicCategories::getDataForFooter($v->relation_data);
            // }else if($v->type == 'language')
            // {
            //     $return[$k]['footerDetails']['footerName'] = $v->name;
            //     $return[$k]['footerDetails']['footerType'] = $v->type;
            //     $return[$k]['footerMenuData'] = MusicLanguages::getDataForFooter($v->relation_data);
            }else if($v->type == 'dynamicgroup')
            {
                $return[$k]['footerDetails']['footerName'] = $v->name;
                $return[$k]['footerDetails']['footerType'] = $v->type;
                $return[$k]['footerMenuData'] = DynamicGroups::getDataForFooter($v->relation_data);
            }
        }
        return $return;
    }
}
