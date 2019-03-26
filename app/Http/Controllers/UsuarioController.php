<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use Illuminate\Support\Facades\Hash;
use App\User;


class UsuarioController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }


    public function show()
    {                
        return view('auth.passwords.change')->with('pageTitle', trans('passwords.TITLE'));
    }

    public function passwordChange (Request $request)
    {
        
        $validation = Validator::make($request->only('password_act'), []);

        if (! Hash::check($request->password_act, \Auth::user()->password))
        {
            $validation->errors()->add('password_act', trans('passwords.NO_VALID'));

            return $this->show()->withErrors($validation);
        }

        $this->validate($request, 
            [
                'password_act' => 'required',
                'password'     => 'required|confirmed']
            ,
            [
                'password.confirmed'              =>  trans('passwords.NO_MATCH'),
                'password_act.required'           =>  trans('passwords.REQUIRED', array('field' => 'Password Actual')),
                'password.required'               =>  trans('passwords.REQUIRED', array('field' => 'Password Nuevo')),
                'password_conformation.required'  =>  trans('passwords.REQUIRED', array('field' => 'Password Confirmación')),
            ]);

        $user = \Auth::user();
        $user->password = bcrypt($request->password);
        $user->save();

        $alerts = array(['type' => 'alert-success', 'msg' => 'Passowrd cambiado correctamente', 'lines' => [] ]);


        return $this->show()->with('alerts', $alerts);
    }
}
