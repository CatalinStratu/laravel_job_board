<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Models\Country;
use App\Models\Pricing;
use App\Models\JobTypes;
use App\Models\Categories;
use Illuminate\Http\Request;


class IndexController extends Controller
{
     /**
     * Show home page.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(){
        // Jobs
        $jobsQuery = Job::with(['employer','country'])->orderBy('id', 'DESC')->take(5)->get();
        
        $vacancies = [];

    	foreach ($jobsQuery as $vacancy){
    		$createAt = $vacancy->created_at->diffForHumans();
    		$vacancies[] = [
    			'id' => $vacancy->id,
                'job_title' => $vacancy->job_title,
                'link' => $vacancy->job_slug,
                'country_name' => $vacancy->country->country_name.($vacancy->remote=='1'? ', Remote' :''),
                'create_at' => ($createAt == 0) ? 1 : $createAt,
    			'company_name' => $vacancy->employer->name,
    		];
    	};
        return view('index',compact('vacancies'));
    }

    /**
     * Remote jobs home page.
     *
     * @return \Illuminate\Contracts\Support\Renderable
    */
    public function remote(){
        // Jobs
        $jobTypes = JobTypes::all();
        $jobsQuery = Job::with(['employer','country'])->where('remote','1')->orderBy('id', 'DESC')->paginate(10);
        
        $vacancies = [];

    	foreach ($jobsQuery as $vacancy){
    		$createAt = $vacancy->created_at->diffForHumans();
    		$vacancies[] = [
    			'id' => $vacancy->id,
                'job_title' => $vacancy->job_title,
                'link' => $vacancy->job_slug,
                'country_name' => $vacancy->country->country_name.($vacancy->remote=='1'? ' , Remote' :''),
                'create_at' => ($createAt == 0) ? 1 : $createAt,
    			'company_name' => $vacancy->employer->name,
    		];
    	};
        return view('jobs.remote',compact('vacancies','jobsQuery','jobTypes'));
    }

    /**
     * Jobs payded in Crypto  home page.
     *
     * @return \Illuminate\Contracts\Support\Renderable
    */
    public function crypto(){
        // Jobs
        $jobTypes = JobTypes::all();
        $jobsQuery = Job::with(['employer','country'])->where('accept_crypto','1')->orderBy('id', 'DESC')->paginate(10);
        
        $vacancies = [];

    	foreach ($jobsQuery as $vacancy){
    		$createAt = $vacancy->created_at->diffForHumans();
    		$vacancies[] = [
    			'id' => $vacancy->id,
                'job_title' => $vacancy->job_title,
                'link' => $vacancy->job_slug,
                'country_name' => $vacancy->country->country_name.($vacancy->remote=='1'? ', Remote' :''),
                'create_at' => ($createAt == 0) ? 1 : $createAt,
    			'company_name' => $vacancy->employer->name,
    		];
    	};
        return view('jobs.crypto',compact('vacancies','jobsQuery','jobTypes'));
    }

        /**
     * Jobs payded in Crypto  home page.
     *
     * @return \Illuminate\Contracts\Support\Renderable
    */
    public function category($slug){
        //category
        $category = Categories::where('slug', $slug)->first();
    	if ($category) {
            
            $category = [
                'id' => $category->id,
                'name' => $category->name,
                'description' => $category->description,
            ];
            // Jobs
            $jobsQuery = Job::with(['employer','country'])->where('category_id',$category['id'])->orderBy('id', 'DESC')->paginate(10);
            $vacancies = [];

    	    foreach ($jobsQuery as $vacancy){
    		    $createAt = $vacancy->created_at->diffForHumans();
    		    $vacancies[] = [
    			    'id' => $vacancy->id,
                    'job_title' => $vacancy->job_title,
                    'link' => $vacancy->job_slug,
                    'country_name' => $vacancy->country->country_name.($vacancy->remote=='1'? ', Remote' :''),
                    'create_at' => ($createAt == 0) ? 1 : $createAt,
    			    'company_name' => $vacancy->employer->name,
    		    ];
    	    };
    		return view('jobs.category',compact('category','vacancies'));
    	} else {
    		return back();
    	}
    }
    /**
     * Show pricing page.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function pricing(){

        $prices = Pricing::all();
        // Packages     
        $packages = [];

    	foreach ($prices as $price){
    		$packages[] = [
    			'id' => $price->id,
                'name' => $price->package_name,
                'price' => $price->job_slug,
                'premium_jobs' => $price->premium_job,
    		];
        };
        
        return view('pricing', compact('packages'));
    }

    /**
     * Show press page.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function press(){
       // $packages = Pricing::all();
        return view('press', compact('packages'));
    }

    /**
     * Show about page.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function about(){
        return view('about');
    }

     /**
     * How it works page.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function howitworks(){
        return view('howitworks');
    }

     /**
     * Terms and Conditions page.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function terms(){
        return view('terms');
    }

     /**
     * Copyright page.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function copyright(){
        return view('copyright');
    }
}
