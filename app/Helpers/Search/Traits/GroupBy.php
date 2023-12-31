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

namespace App\Helpers\Search\Traits;

use Illuminate\Support\Facades\DB;

trait GroupBy
{
	protected function applyGroupBy()
	{
		if (!(isset($this->posts) && isset($this->groupBy))) {
			return;
		}
		
		if (is_array($this->groupBy) && count($this->groupBy) > 0) {
			// Get valid columns name
			$this->groupBy = collect($this->groupBy)->map(function ($value, $key) {
				if (str_contains($value, '.')) {
					$value = DB::getTablePrefix() . $value;
				}
				
				return $value;
			})->toArray();
			
			$this->posts->groupByRaw(implode(', ', $this->groupBy));
		}
	}
}
