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

namespace App\Models;

use App\Observers\FieldOptionObserver;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Http\Controllers\Admin\Panel\Library\Traits\Models\Crud;
use App\Http\Controllers\Admin\Panel\Library\Traits\Models\SpatieTranslatable\HasTranslations;

class FieldOption extends BaseModel
{
    use Crud, HasFactory, HasTranslations;
    
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'fields_options';
    
    /**
     * The primary key for the model.
     *
     * @var string
     */
    // protected $primaryKey = 'id';
    
    /**
     * Indicates if the model should be timestamped.
     *
     * @var boolean
     */
    public $timestamps = false;
    
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'field_id',
        'value',
        'parent_id',
        'lft',
        'rgt',
        'depth',
    ];
    public $translatable = ['value'];
    
    /**
     * The attributes that should be hidden for arrays
     *
     * @var array
     */
    // protected $hidden = [];
    
    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    // protected $dates = [];
    
    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */
    protected static function boot()
    {
        parent::boot();
	
		FieldOption::observe(FieldOptionObserver::class);
    }
    
    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */
    public function field()
    {
        return $this->belongsTo(Field::class, 'field_id');
    }
    
    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */
    
    /*
    |--------------------------------------------------------------------------
    | ACCESSORS | MUTATORS
    |--------------------------------------------------------------------------
    */
	
	/*
	|--------------------------------------------------------------------------
	| OTHER PRIVATE METHODS
	|--------------------------------------------------------------------------
	*/
}
