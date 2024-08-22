<?php

namespace App\Traits;

use Response;
use App\Models\GlobalLanguage;

trait CommonTrait
{
	/*
		get default language and piece of default language based on parameter value
	*/
	public function getDefaultLanguage($part)
	{
		$defaultLanguage = GlobalLanguage::with('language')->where('is_default',1)->first();
		if ($part != null) {
			$defaultLanguage = $defaultLanguage['language']["$part"];
		}
		return $defaultLanguage;
	}

	/*
		get non default language list
	*/
	public function getNonDefaultLanguage()
	{
		$nonDefaultlanguage = GlobalLanguage::with('language')->where('is_default',0)->get();
		return $nonDefaultlanguage;
	}
}