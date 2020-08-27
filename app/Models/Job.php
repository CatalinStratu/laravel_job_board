<?php

namespace App\Models;

use Laravel\Scout\Searchable;
use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    use Searchable;
    protected $guarded = ['id'];
    protected $fillable = ['views'];

    public function toSearchableArray()
    {
        $array = $this->toArray();
    
        $array = $this->transform($array);
    
        $array['company_name'] = $this->employer->name;
        $array['remote'] = $this->remote =='1'? 'Remote' :'';
        $array['jobtype'] = $this->jobtype->name;
        $array['country'] = $this->country->country_name.($this->remote =='1'? ' Remote' :'');
        //$array['author_email'] = $this->author->email;
    
        return $array;
    }
    // Company
    public function employer(){
        return $this->belongsTo(Companies::class, 'company_id');
    }
    
    // Categories
    public function category(){
        return $this->belongsTo(Categories::class, 'category_id');
    }    
    // Job Type
    public function jobtype(){
        return $this->belongsTo(JobTypes::class, 'job_type');
    }

    // Country
    public function country(){
        return $this->belongsTo(Country::class, 'country_id');
    }
}
