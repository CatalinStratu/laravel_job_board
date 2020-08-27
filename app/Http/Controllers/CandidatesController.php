<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CandidatesController extends Controller
{
    // Candidates Resume
    public function resume(){
        
        return view('dashboard.resume.index');
    }
}
