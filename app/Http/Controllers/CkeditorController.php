<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CkeditorController extends Controller
{
    /**
     * success response method.
     *
     * @return \Illuminate\Http\Response
     */
    public function upload(Request $request)
    {
       // try {
        if($request->hasFile('upload')) {

            //$this->validate($request,[
            //    'upload'=> 'image|mimes:jpeg,png,jpg,gif'
            //]);
            $originName = $request->file('upload')->getClientOriginalName();
            $fileName = pathinfo($originName, PATHINFO_FILENAME);
            $extension = $request->file('upload')->getClientOriginalExtension();
            $extensionAllow = array('jpg','jpeg','png');
            if(in_array($extension,$extensionAllow) && $request->file('upload')){
                $fileName = 'upload/'.$fileName.time();
        
                //$request->file('upload')->move(public_path('images'), $fileName);
                //filename to store
                //$filenametostore = 'upload/'.$filename.'_'.uniqid().'.'.$extension;
            
                //Upload File to external server
                Storage::disk('sftp')->put($fileName, fopen($request->file('upload'), 'r+'));

                $CKEditorFuncNum = $request->input('CKEditorFuncNum');
                $url = 'https://cdn.crypto-job.com/'.$fileName;
                //$url = asset('images/'.$fileName);
                $msg = 'Image uploaded successfully'; 
                $response = "<script>window.parent.CKEDITOR.tools.callFunction($CKEditorFuncNum, '$url', '$msg')</script>";
                   
                @header('Content-type: text/html; charset=utf-8'); 
                echo $response; 
            
            }else {
                $CKEditorFuncNum = $request->input('CKEditorFuncNum');
                $msg = 'Error to upload'; 
                $url = '';
                $response = "<script>window.parent.CKEDITOR.tools.callFunction($CKEditorFuncNum, '$url', '$msg')</script>";
               
                @header('Content-type: text/html; charset=utf-8'); 
                echo $response;
            }
           // } catch (\Exception $e) {
           // return $e->getMessage();
            //}
        }
    }
}
