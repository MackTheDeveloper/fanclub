<?php

namespace App\Http\Controllers;

use App\Models\FooterLink;
use App\Models\Chats;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\View;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /*
    *
    	Define base url commonly to use anywhere in application
    *
    */
    // public $baseUrl = "";

    function __construct()
    {
        $footerData = FooterLink::getFooterData();
        View::share('frontendFooter', $footerData);
        //$this->baseUrl = url('/');
    }

    public function getBaseUrl()
    {
        return url('/');
    }
}
