<?php

namespace App\Http\Controllers;


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
use App\Song;
use Logger;
use Mail;
//use Log4php;
Logger::configure('../config.xml');

class SongsController extends Controller
{
	private $song;
	public function __construct(Song $song)
	{
		$this->song = $song;
	}
	
    public function index(Song $songs)
	{
		$songs = $this->song->get();
		
		$log = Logger::getLogger("main");
		$log->error("EasyCron Works");
		//mail('ankur.vyas@marutitech.com','ankur','ankur');
		/*Mail::raw('Text to e-mail', function($message)
		{
			$message->from('avyas534.av@gmail.com', 'Sopari Account System');
		
			$message->to('ankur.vyas@marutitech.com');
		});*/
		$ipAddress = $_SERVER['REMOTE_ADDR'];
		if (array_key_exists('HTTP_X_FORWARDED_FOR', $_SERVER)) {
			$ipAddress = array_pop(explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']));
		}
		//print_r($ipAddress);
		//exit();
		return view('pages.songs',['songs' => $songs]);
	}
	
	public function show(Song $songs,$slug)
	{
		$songs = $this->song->whereSlug($slug)->first();
			
		return view('pages.show',['songs' => $songs]);
	}
	
	public function edit(Song $songs,$slug)
	{
		$songs = $this->song->whereSlug($slug)->first();
		return view('pages.edit',compact('songs'));
	}
	
	public function update($slug)
	{
		$songs = $this->song->whereSlug($slug)->first();
		$songs->title = \Request::get('title');
		$songs->lyrics = \Request::get('lyrics');
		$songs->slug = \Request::get('slug');
		$songs->save();
		
		$file = \Request::file('myname');
		$imageName = $songs->slug . '.' . $file->getClientOriginalExtension();
		//print_r($imageName);
		//exit();

		\Request::file('myname')->move(
			base_path() . '/public/images/catalog/', $imageName
		);
		
		return Redirect('songs');
	}
	
	public function create()
	{
		return view('pages.create');
	}
	
	public function store(Song $songs)
	{
		$songs->title = \Request::get('title');
		$songs->lyrics = \Request::get('lyrics');
		$songs->slug = \Request::get('slug');
		$songs->save();
		
		$file = \Request::file('myname');
		$imageName = $songs->slug . '.' . $file->getClientOriginalExtension();
		
		\Request::file('myname')->move(
			base_path() . '/public/images/catalog/', $imageName
		);
		
		return Redirect('songs');
	}
}
