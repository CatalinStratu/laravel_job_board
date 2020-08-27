<?php

namespace App\Http\Controllers;

use Auth;
use App\Models\Job;
use App\Models\Country;
use App\Models\JobTypes;
use App\Models\Categories;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use App\Http\Requests\Jobs\JobStoreRequest;

class JobsController extends Controller
{
    /**
     * Add new job.
     * @method GET
     * @return \Illuminate\Contracts\Support\Renderable
    */
    public function index()
    {
        $countries = Country::all();
        $jobTypes = JobTypes::all();
        $categories = Categories::all();
        //JobTypes
        return view('dashboard.jobs.new',compact('countries','jobTypes','categories'));
    }
    
    /**
    * Create a new job instance.
    *
    * @param  JobStoreRequest  $request
    * @return Response
    */
    public function newJobPost(JobStoreRequest $request){
        // Add new job
        $job = new Job();//+
        // Job title
        $job_title = $request->job_title;//++
        // Create a slug
        $slug = $job_title;//++
        // Create a unique slug
        $job_slug = unique_slug($job_title, 'Job', 'job_slug');//++
        // User id
        $job->user_id = Auth::user()->id;//++
        // Company id
        $job->company_id = Auth::user()->company_id;//++
        // Job title
        $job->job_title = $job_title;//++
        // Job slug
        $job->job_slug = $job_slug;//++
        // Job category
        $job->category_id = $request->category;//++
        // Job is remote
        if ($request->has('remote')) {
            $job->remote = $request->remote;//++
        }
        // Job salary min - max
        if ($request->has('salary')) {
            $job->salary = $request->salary;//++
        }
        // Job salary 
        if ($request->has('salary_upto')) {
            $job->salary_upto = $request->salary_upto;//++
        }
        // If company accept crypto to pay sallaryc
        if ($request->has('accept_crypto')) {
            $job->accept_crypto = $request->accept_crypto;//++
        }
        //Apply type
        $job->applyType = $request->applyType;
        // Job type
        $job->job_type = $request->job_type;//++
        // Job country id && name && address
        $job->country_id  = $request->country;//++
        $job->address = $request->address;//++
        // Job descriprion
        $job->description = $request->description;//++
        // Small job description
        $job->smalldescription = $request->smalldescription;//++
        // Job order by search time
        $job->search_time = now();//+
        $job->email = $request->email;
        $job->link = $request->link;
        // Status 
        $job->status = 1;//+
       // if($job->employer->balance() == 0){
       //     return redirect()->back()->with('success', 'You have have successfully posted your job, it will be live after verified by admin');
       // } else {
       //     $job->employer->checkcredits(10);
       // }
        $job->save();
        return redirect(route('job.posted.get'))->with('success', 'You have have successfully posted your job, it will be live after verified by admin');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function postedjobs()
    {
        $jobs = Job::where('user_id', Auth::user()->id)->orderBy('id', 'DESC')->paginate(10);
       // $vacancies = Vacancy::where('recruiter_id', Auth::user()->recruiter->id)->orderBy('created_at', 'DESC')->with(['candidates'])->paginate(10);
       // return view('front.recruiter.dashboard.vacancy.index', ['vacancies' => $vacancies]);
        return view('dashboard.jobs.all',compact('jobs'));
    }

    /**
     * Show a job.
     * @method GET
     * @return \Illuminate\Contracts\Support\Renderable
    */
    public function show($slug){
        $job = Job::with(['employer','jobtype'])->where('job_slug', $slug)->first();
        if ($job) {
            ///if($job->salary || $job->salary_upto != NULL){
           //     $salary = $job->salary.'-'.$job->salary_upto.' USD';
            //} elseif($job->salary != NULL) {
               // $salary = $job->salary.' USD';
            //} 
           // if($job->salary && $job->salary_upto == NULL){           
                 $salary = 'Not specifieted';
           // }
            $job = [
                'id' => $job->id,
                'job_title' => $job->job_title,
                'category_id' => $job->category_id,
                'job_description' => $job->description,
                'country_name' => $job->country->country_name,
                'jobtype' => $job->jobtype->name,
                'salary' => $salary,
                //'category' => $job->category->name,
                'create_at' => $job->created_at->diffForHumans(),
                'address' => $job->address,
                'remote' => $job->remote,
                //Apply
                'applytype' => $job->applyType,
                'link' => $job->link,
                'email' => $job->email,
                //Company
                'logo'=> $job->employer->logo,
                'company_name' => $job->employer->name,
                'about_company' => Str::limit($job->employer->about, 249, '...'),
               // 'about_company' => 'test',
            ];
             // Jobs
             $jobsQuery = Job::with('employer')->where('category_id',$job['category_id'])->orderBy('id', 'DESC')->limit(4)->get()->except($job['id']);
            $vacancies = [];            
                
        	foreach ($jobsQuery as $vacancy){
        	    //$createAt = $vacancy->created_at->diffForHumans();
        	    $vacancies[] = [
        		    'id' => $vacancy->id,
                    'job_title' => $vacancy->job_title,
                    'link' => $vacancy->job_slug,
        		    'company_name' => $vacancy->employer->name,
        	    ];
            };
    
                //$key = 'job' . $job['id'];
                //if (!\Session::has($key)) {
                //    $post = Job::find($job['id']); // fetch post from database
                //    $post->views++;
                //    $post->save();            
                //}
                //\Session::put($key, 1);
            return view('job',compact('job','vacancies'));
        } else {
            return back();
        }
    }

    /**
     * Edit job.
     *
     * @method GET.
     * @return \Illuminate\Contracts\Support\Renderable
    */
    public function edit($job_id){
        $my_job = Job::findOrFail($job_id);
        if(Auth::id() != $my_job->user_id){
            return redirect(route('job.posted.get'))->with('success', 'You have have successfully posted your job, it will be live after verified by admin');
        } else {
            $countries = Country::all();
            $jobTypes = JobTypes::all();
            $categories = Categories::all();
            //JobTypes
            return view('dashboard.jobs.update',compact('countries','jobTypes','categories','my_job'));   
        }
    }

    /**
     * Edit job.
     *
     * @method POST.
     * @return \Illuminate\Contracts\Support\Renderable
    */
    public function edit_job(JobStoreRequest $request, $job_id){
        $job = Job::findOrFail($job_id);
        if(Auth::id() != $job->user_id){
            return redirect(route('job.posted.get'))->with('success', 'You have have successfully posted your job, it will be live after verified by admin');
        } else {
            // Job title
            $job_title = $request->job_title;//++
            // Create a slug
            $slug = $job_title;//++
            // Create a unique slug
            $job_slug = unique_slug($job_title, 'Job', 'job_slug');//++
            // User id
            $job->user_id = Auth::user()->id;//++
            // Company id
            $job->company_id = Auth::user()->company_id;//++
            // Job title
            $job->job_title = $job_title;//++
            // Job slug
            $job->job_slug = $job_slug;//++
            // Job category
            $job->category_id = $request->category;//++
            // Job is remote
            if ($request->has('remote')) {
                $job->remote = $request->remote;//++
            }
            // Job salary min - max
            if ($request->has('salary')) {
                $job->salary = $request->salary;//++
            }
            // Job salary 
            if ($request->has('salary_upto')) {
                $job->salary_upto = $request->salary_upto;//++
            }
            // If company accept crypto to pay sallaryc
            if ($request->has('accept_crypto')) {
                $job->accept_crypto = $request->accept_crypto;//++
            }
            //Apply type
            $job->applyType = $request->applyType;
            // Job type
            $job->job_type = $request->job_type;//++
            // Job country id && name && address
            $job->country_id  = $request->country;//++
            $job->address = $request->address;//++
            // Job descriprion
            $job->description = $request->description;//++
            // Small job description
            $job->smalldescription = $request->smalldescription;//++
            // Job order by search time
            $job->search_time = now();//+
            $job->email = $request->email;
            $job->link = $request->link;
            // Status 
            $job->status = 1;//+
            // if($job->employer->balance() == 0){
            //     return redirect()->back()->with('success', 'You have have successfully posted your job, it will be live after verified by admin');
            // } else {
            //     $job->employer->checkcredits(10);
            // }
            $job->update();
            return redirect(route('job.posted.get'))->with('success', 'You have have successfully update your job');
   
        }
    }
    /**
     * Dellete job.
     *
     * @return \Illuminate\Contracts\Support\Renderable
    */
    public function delete($job_slug)
    {
        $job = Job::where('job_slug', $job_slug)->first();
        if(Auth::id() != $job->user_id){
            return redirect()->back();
        } else {
            Job::where('job_slug', $job_slug)->delete();
        }
        return redirect()->back();
    }
}
