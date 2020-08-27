<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    //public function __construct()
    //{
   //     $this->middleware(['auth','verified']);
   // }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $method = $request->isMethod('post');
        if($method){
            dd($request->all());
            //Save to database. Recommended data type for the image is blob.
        }else{
            //Return the view
            return view('test');     
        }
    }

    public function store(Request $request)
    {
    if($request->hasFile('profile_image')) {
         
        //get filename with extension
        $filenamewithextension = $request->file('profile_image')->getClientOriginalName();
 
        //get filename without extension
        $filename = pathinfo($filenamewithextension, PATHINFO_FILENAME);
 
        //get file extension
        $extension = $request->file('profile_image')->getClientOriginalExtension();
 
        //filename to store
        $filenametostore = 'logos/'.$filename.'_'.uniqid().'.'.$extension;
 
        //Upload File to external server
        Storage::disk('sftp')->put($filenametostore, fopen($request->file('profile_image'), 'r+'));
 
        //Store $filenametostore in the database
    }
 
    return redirect('images')->with('status', "Image uploaded successfully.");
    }
    
}
