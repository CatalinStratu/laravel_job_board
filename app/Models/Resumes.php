<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Resumes extends Model
{

    protected $table = 'resumes';
    protected $fillable = ['title' , 'type','size','link','user_id'];
    
    //User resume
    public function user_resume($query, $id)
    {
        return $query->where('user_id', $id)->first();
    }
}
