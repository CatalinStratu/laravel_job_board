<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    /**
     * Change password controller
     * @method get
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function changePass()
    {
        return view('dashboard.account.newPass');
    }

    /**
     * Change password controller
     * @method post
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function changePassPost(Request $request)
    {
        $rules = [
            'old_password'  => 'required',
            'new_password'  => 'required|confirmed',
            'new_password_confirmation'  => 'required',
        ];
        $this->validate($request, $rules);

        $old_password = $request->old_password;
        $new_password = $request->new_password;
        //$new_password_confirmation = $request->new_password_confirmation;

        if(Auth::check())
        {
            $logged_user = Auth::user();

            if(Hash::check($old_password, $logged_user->password))
            {
                $logged_user->password = Hash::make($new_password);
                $logged_user->save();
                return redirect()->back()->with('success', 'Password has been changed successfully');
            }
            return redirect()->back()->with('error', 'Wrong old password');
        }
    }
}