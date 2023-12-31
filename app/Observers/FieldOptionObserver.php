<?php
/**
 * bazaralkhaleej - Bazaralkhaleej Ads Web Application
 * Copyright (c) BeDigit. All Rights Reserved
 *
 * Website: https://bazaralkhaleej.com
 *
 * LICENSE
 * -------
 * This software is furnished under a license and may be used and copied
 * only in accordance with the terms of such license and with the inclusion
 * of the above copyright notice. If you Purchased from CodeCanyon,
 * Please read the full License from here - http://codecanyon.net/licenses/standard
 */

namespace App\Observers;

use App\Models\FieldOption;
use App\Models\PostValue;

class FieldOptionObserver
{
	/**
	 * Listen to the Entry deleting event.
	 *
	 * @param FieldOption $fieldOption
	 * @return void
	 */
	public function deleting($fieldOption)
	{
		// Delete all Posts Custom Field's Values
		$postValues = PostValue::where('value', $fieldOption->id)->get();
		if ($postValues->count() > 0) {
			foreach ($postValues as $value) {
				$value->delete();
			}
		}
	}
}
