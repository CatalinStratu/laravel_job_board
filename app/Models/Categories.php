<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Symfony\Component\HttpFoundation\Request;


class Categories extends Model
{
    protected $table = 'categories';
    protected $fillable = ['name', 'description','slug', 'status'];

     // list
     public static function list()
     {
         $elements = Categories::all();
 
         $list = [];
 
         foreach ($elements as $element) {
             $list[$element->id] = ucwords($element->name);
         }
 
         return $list;
     }
    
}
