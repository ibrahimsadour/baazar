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

namespace App\Models\Traits;

trait ActiveTrait
{
    public function getActiveHtml()
    {
        if (!isset($this->active)) return false;
        
        return ajaxCheckboxDisplay($this->{$this->primaryKey}, $this->getTable(), 'active', $this->active);
    }
}