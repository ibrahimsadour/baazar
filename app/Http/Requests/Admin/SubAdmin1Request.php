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

namespace App\Http\Requests\Admin;

class SubAdmin1Request extends Request
{
	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		return [
			'code' => ['required', 'min:2', 'max:20'],
			'name' => ['required', 'min:2', 'max:255'],
		];
	}
}
