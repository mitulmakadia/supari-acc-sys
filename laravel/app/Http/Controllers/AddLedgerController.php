<?php
namespace App\Http\Controllers;
session_start();
require_once ("../log4php/Logger.php");
require_once ("../app/Http/Controllers/Password.php");
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Response;
use Session;
use Redirect;
use Logger;
use Mail;
use DB;
use App\Http\Controllers\Password;

//use Log4php;
Logger::configure('../config.xml');

class AddLedgerController extends Controller
{
	public function __construct()
	{
		
	}
	
    public function index()
	{
	    $users = DB::table('ledger')->Join('ledgergroup', 'ledger.Group_ID', '=', 'ledgergroup.Group_ID')->groupBy('ledger.Name')->select('ledger.Name as Name','ledger.FullName as FullName','ledgergroup.Name as Groups','ledger.OpeningBalance as OpeningBalance','ledger.CrDr as CrDr')->get();
		return view('templates.ledger',['users' => $users]);
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
	
	public function store()
	{
		$data = \Input::all();
		$Name= $data['Name'];
		$FullName= $data['FullName'];
		$Groups= $data['Groups'];
		if($Groups == "Bank Account")
			$grp = 1;
		else if($Groups == "Cash-in-Hand")
			$grp = 2; 
		else if($Groups == "Expenses")
			$grp = 3; 
		else if($Groups == "Sundry Creditors")
			$grp = 4; 
		else if($Groups == "Sundry Debitors")
			$grp = 5; 
		else if($Groups == "Supari A/c")
			$grp = 6; 
			
		$OpeningBalance= $data['OpeningBalance'];
		$CrDr= $data['CrDr'];
		
		if(isset($Name)&&isset($FullName)&&isset($Groups)&&isset($OpeningBalance)&&isset($CrDr))
	    {
		    $query=DB::select("select * from ledger where Name='$Name';");
            if (is_null($query)) {
            
           
              echo "You are already registered";
              } else {

  

			
			DB::table('ledger')->insert(
                    array(
						   'Name'     =>   $Name, 
                           'FullName' =>   $FullName,
						   'Group_Id'    => $grp,
						   'OpeningBalance' => $OpeningBalance,
                           'CrDr' => $CrDr						   
						  )
                     );
		    return redirect('ledger');		 
	    }}
	}
}
