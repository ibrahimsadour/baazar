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

namespace App\Models\Scopes;

use App\Models\Permission;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Schema;

class LocalizedScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     *
	 * @param \Illuminate\Database\Eloquent\Builder $builder
	 * @param \Illuminate\Database\Eloquent\Model $model
	 * @return \Illuminate\Database\Eloquent\Builder
	 */
    public function apply(Builder $builder, Model $model)
    {
    	if (empty(config('country.code'))) {
			return $builder;
		}
		
    	// Apply this scope when the Domain Mapping plugin is installed.
		// And when the session is NOT shared.
		// And, apply it from the Admin panel only.
		if (config('plugins.domainmapping.installed')) {
			if (!config('settings.domainmapping.share_session')) {
				if (request()->segment(1) == admin_uri()) {
					// 'countries' table filter
					if ($model->getTable() == 'countries') {
						return $builder->where('code', config('country.code'));
					}
					
					// Tables with 'country_code' column filter
					if (Schema::hasColumn($model->getTable(), 'country_code')) {
						if ($model->getTable() == 'users') {
							
							if (Permission::checkDefaultPermissions()) {
								$builder->permission(Permission::getStaffPermissions())->orWhere('country_code', config('country.code'));
							}
							
						} else {
							$builder->where('country_code', config('country.code'));
						}
						
						return $builder;
					}
					
					// Tables with 'post' relation filter
					if (in_array($model->getTable(), ['payments', 'pictures', 'reviews'])) {
						return $builder->has('post');
					}
				}
			}
		}
        
		return $builder;
    }
}
