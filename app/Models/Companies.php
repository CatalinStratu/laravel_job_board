<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Companies extends Model
{
    protected $table = 'companies';
    protected $fillable = ['name' , 'logo','owner_id','email',  'category_id', 'credits', 'address'  , 'city' , 'phone' , 'fax' , 'website' , 'facebook' , 'twitter' , 'google_plus'  , 'no_employees' , 'no_jobs' , 'about'];
    
    //User company
    public function usercompany($query, $id)
    {
        return $query->where('owner_id', $id)->first();
    }

    //Job babance
    public function balance()
    {
        return $this->credits;
    }

    // Premium jobs
    public function checkcredits($credits){
        $totalcredits = $this->credits;
        $balance = $totalcredits - $credits;
        $this->credits = $balance;
        $this->save();
    }
}
