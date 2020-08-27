<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobTypes extends Model
{
	protected $table = 'job_types';
    protected $fillable = ['name', 'slug', 'status'];

    // List
    public static function list()
    {
    	$elements = JobTypes::all();

    	$list = [];

    	foreach ($elements as $element) {
    		$list[$element->id] = ucwords($element->name);
    	}

    	return $list;
    }
}
