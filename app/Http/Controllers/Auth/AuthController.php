<?php

namespace App\Http\Controllers\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Session;
use App\Models\User;
use Hash;


// inspiration fra https://www.itsolutionstuff.com/post/laravel-custom-login-and-registration-exampleexample.html
class AuthController extends Controller
{
    /**
     * @return response()
     */
    public function login()
    {
        return view('auth.login');
    }  
    /**
     * @return response()
     */

    public function registration()
    {
        return view('auth.registration');
    }

    /**
     * @return response()
     */

    public function postLogin(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {
            return redirect()->intended('index')
                        ->withSuccess('You have Successfully loggedin');
        }
        return redirect("login")->withSuccess('Oppes! You have entered invalid credentials');
    }

      

    /**
     * @return response()
     */

    public function postRegistration(Request $request)

    {  
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
        ]);
        $data = $request->all();
        $check = $this->create($data);
        return redirect("index")->withSuccess('Great! You have Successfully loggedin');
    }

    

    /**
     * @return response()
     */

    public function index()
    {
        if(Auth::check()){
            return view('social.index');
        }
        return redirect("login")->withSuccess('Opps! You do not have access');
    }
    /**
     * @return response()
     */

    public function create(array $data)
    {
      return User::create([
        'name' => $data['name'],
        'email' => $data['email'],
        'password' => Hash::make($data['password'])
      ]);
    }

    /**
     * @return response()
     */

    public function logout() {
        Session::flush();
        Auth::logout();
        return Redirect('login');
    }


}