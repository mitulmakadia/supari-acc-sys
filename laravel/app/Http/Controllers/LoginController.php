<?php
namespace App\Http\Controllers;
session_start();
require_once ("../log4php/Logger.php");
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Response;
use Input;
use Validator;
use Auth;
use Session;
use Redirect;
use App\Login;
use App\Http\Models\dbConfig;
use Logger;
use Mail;
//use Log4php;
Logger::configure('../config.xml');

class LoginController extends Controller
{
	private $login;
	private $connection;
	
	public function __construct(Login $login)
	{
		$this->login = $login;
	}
	
    public function index()
	{
		$loginData = array("email" => $_COOKIE['email'],
							"password" => $_COOKIE['password'],
							"error" => "");
		return view('templates.Login',['loginData' => $loginData]);
	}
	
	public function show()
	{
	}
	
	public function edit()
	{
	}
	
	public function update()
	{
	}
	
	public function create()
	{
	}
	
	public function store(Login $logins)
	{
		$email = \Request::get('email');
		$password = \Request::get('password');
		
		if(isset($email)&&isset($password))
		{
			try
			{
				//set cookie
				$checkMe = \Request::get('checkMe');
				if(isset($checkMe))
				{
					setcookie("email", "", time() - 3600);
					setcookie("password", "", time() - 3600);
					setcookie('email',$email, time() + (86400 * 30), "/");
					setcookie('password',$password, time() + (86400 * 30), "/");	
				}
				
				$logins = $this->login->where('email', $email)->first();

				if(isset($logins))
				{
					$_SESSION["login"] = "true";
					
					//maintain success log
					$log = Logger::getLogger("main");
					$ipAddress = $_SERVER['REMOTE_ADDR'];
					$log->info("Email: ".$email." Password: ".$password." IPAddress: ".$ipAddress." isSuccess: true");

					return Redirect('home');
				}
				else
				{
					//maintain success log for failure
					$log = Logger::getLogger("main");
					$ipAddress = $_SERVER['REMOTE_ADDR'];
					$log->info("Email: ".$email." Password: ".$password." IPAddress: ".$ipAddress." isSuccess: false");
					
					\Session::flash('flash_message','Please enter valid credentials.');
					return redirect('login');
				}
			}
			catch(\Exception $e)
			{
				return Redirect::to('login')
						->withErrors('not allowed') 
						->withInput(Input::except('password'));
			}
		}
		
	}
}
