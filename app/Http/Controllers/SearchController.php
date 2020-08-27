<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Models\JobTypes;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    //Search
    public function index(Request $request){
        //$accounts = Account::search($request->input('search'))->get();
        $jobTypes = JobTypes::all();
        if($request->has('search')||$request->has('job_type')){  
            $jobtype= 'jobtype:'. $request->input('job_type');
            $vacancies = Job::search($request->input('search'))->with(
                [
                    'facetFilters' =>  [$jobtype],
                ]
            )->orderBy('id', 'DESC')->paginate(10);
        } else {
      //      // Jobs
           $vacancies = Job::orderBy('id', 'DESC')->paginate(20);
        }
        $vacancies->load('employer','country');
        //dd($vacancies);
        return view('jobs.search', compact('vacancies','jobTypes'));
        
    }
}
