<?php

namespace App\Http\Controllers;

use Auth;
use App\Models\Job;
use App\Models\Companies;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    //User dashboard    
    public function index(){
        try {
        if(Auth::user()->role == 'company'){
            //company id
            $company_id = Auth::user()->company_id;
            //company
            $company = Companies::where('id',$company_id)->first();
                if (Auth::user()->is_company()) {
                    if(Auth::user()->company_id == NULL){
                        return redirect()->route('company.add.get');
                    }
                    // Company jobs count
                    $jobs_count = Job::where('company_id',$company_id)->count();
                    // Company jobs limit
                    $jobs = Job::where('company_id',$company_id)->orderby('id','desc')->limit(10)->get();
                    $vacancies = [];                
    	            foreach ($jobs as $vacancy){
    	                $createAt = $vacancy->created_at->diffForHumans();
    	                $vacancies[] = [
    	            	    'id' => $vacancy->id,
                            'job_title' => $vacancy->job_title,
                            'link' => $vacancy->job_slug,
                            'create_at' => $createAt,
    	                ];
                    };
                    $statistic = [
                        'jobs' => $jobs_count,
                    ];
                }
            return view('dashboard.index',compact('statistic', 'vacancies'));     
        } elseif (Auth::user()->role == 'jobseeker') {
            $jobs = Job::orderby('id','desc')->limit(10)->get();
                $vacancies = [];                
    	        foreach ($jobs as $vacancy){
    	            $createAt = $vacancy->created_at->diffForHumans();
    	            $vacancies[] = [
    	        	    'id' => $vacancy->id,
                        'job_title' => $vacancy->job_title,
                        'link' => $vacancy->job_slug,
                        'create_at' => $createAt,
    	            ];
                };
            return view('dashboard.jobseeker',compact('vacancies'));     
        }

        } catch (\Exception $e) {
            return $e->getMessage();
        }
        
    }
}
