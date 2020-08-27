<?php

namespace App\Http\Controllers;

use Auth;
use App\Models\User;
use App\Models\Companies;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CompaniesController extends Controller
{
     /*
     * Show add Company Form
     */
    public function index(){
        if(Auth::user()->company_id != NULL){
            return redirect('/');
        }
        return view('dashboard.companies.add');
    }

    /*
    *Add Company in database
    */
    public function add(Request $request){
        if(Auth::user()->company_id != NULL){
            return redirect(route('dashboard.index'));
        }
        $this->validate($request, [
            //'company_name' => 'required',
            'address' => 'max:255',
            'name' => 'required|max:255',
            //'country' => 'required',
            'logo'=> 'image|mimes:jpeg,png,jpg'
        ]);
        $company = new Companies();
        $company->name = $request->name;
        $company->address = $request->address;
        $company->about = $request->about;
        $company->slug  = unique_slug($request->name, 'Companies', 'slug');
        $company->owner_id = Auth::id();
        if($request->hasFile('logo')) {
            // $file = $request->file;
             //$company->logo  = $file->getClientOriginalName();
             //get filename with extension
             $filenamewithextension = $request->file('logo')->getClientOriginalName();
     
             //get filename without extension
             $filename = pathinfo($filenamewithextension, PATHINFO_FILENAME);
     
             //get file extension
             $extension = $request->file('logo')->getClientOriginalExtension();
  
             //filename to store
             $filenametostore = 'logos/'.$filename.'_'.uniqid().'.'.$extension;
             //Upload File to external server
             Storage::disk('sftp')->put($filenametostore, fopen($request->file('logo'), 'r+'));
             $url = 'https://cdn.crypto-job.com/'.$filenametostore;
             $company->logo = $url;   
        }
        $company->save();
        User::where('id', Auth::id())->update(array('company_id' => $company->id, 'company_role' => 'admin', ));
        return redirect(route('job.posted.get'))->back()->with('success','Company added successfully');
    }
    
    /*
    *Show Company Data in form to Edit
     */
    public function edit(){
       // $com = Companies::find($request->id);
        $company = Companies::where('owner_id', Auth::id())->first();
        return view('dashboard.companies.update', compact('company'));
    }
    
    /*
    *Update data in database
    */
    public function update(Request $request){
    
        $this->validate($request, [
            //'company_name' => 'required',
            'address' => 'max:255',
            'name' => 'required|max:255',
            //'country' => 'required',
            'logo'=> 'image|mimes:jpeg,png,jpg'
        ]);
        $company = Companies::find(Auth::user()->company_id);
        $company->name = $request->name;
        $company->owner_id = Auth::id();
        $company->address = $request->address;
        //$company->country = $request->country;
        //$company->city = $request->city;
        //$company->facebook = $request->facebook;
        //$company->twitter = $request->twitter;
        //$company->google_plus = $request->google_plus;
        $company->about = $request->about;
    
        if($request->hasFile('logo')) {
           // $file = $request->file;
            //$company->logo  = $file->getClientOriginalName();
            //get filename with extension
            $filenamewithextension = $request->file('logo')->getClientOriginalName();
    
            //get filename without extension
            $filename = pathinfo($filenamewithextension, PATHINFO_FILENAME);
    
            //get file extension
            $extension = $request->file('logo')->getClientOriginalExtension();
 
            //filename to store
            $filenametostore = 'logos/'.$filename.'_'.uniqid().'.'.$extension;
            //Upload File to external server
            Storage::disk('sftp')->put($filenametostore, fopen($request->file('logo'), 'r+'));
            $url = 'https://cdn.crypto-job.com/'.$filenametostore;
            $company->logo = $url;   
        }
        $company->save();
        return redirect()->back()->with('success','Company information updates successfully');
    }
    // Show company 
    public function show($slug){
        $company = Companies::where('job_slug', $slug)->first();
    	if ($company) {
            $company = [
                'id' => $company->id,
                'name' => $company->name,
                'logo' => $company->logo,
                'email' => $company->email,
                'address' => $company->address,
                'country' => $company->country,
                'phone' => $company->phone,
                'website' => $company->website,
                'about' => $company->about,

            ];
            // Jobs
            $jobsQuery = Job::with('employer')->where('company_id',$job['category_id'])->orderBy('id', 'DESC')->paginate(2);
            $vacancies = [];

    	    foreach ($jobsQuery as $vacancy){
    		    $createAt = $vacancy->created_at->diffForHumans();
    		    $vacancies[] = [
    			    'id' => $vacancy->id,
                    'job_title' => $vacancy->job_title,
                    'link' => $vacancy->job_slug,
                    'jobtype' => $vacancy->job_type_name,
                    'country_name' => ($vacancy->remote == '1' ? $vacancy->country_name : 'Remote'),
                    'create_at' => ($createAt == 0) ? 1 : $createAt,
    			    'company_name' => $vacancy->employer->name,
    		    ];
    	    };
    		return view('job',compact('job','vacancies'));
    	} else {
    		return back();
    	}
    }
}
