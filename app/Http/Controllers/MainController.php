<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Helpers\Contracts\HelperContract; 
use Auth;
use Session; 
use Validator; 
use Carbon\Carbon; 

class MainController extends Controller {

	protected $helpers; //Helpers implementation
    
    public function __construct(HelperContract $h)
    {
    	$this->helpers = $h;            
    }

	/**
	 * Show the application welcome screen to the user.
	 *
	 * @return Response
	 */
	public function index()
    {
    	return view('index');
    }
      
    
        /**
	 * Handles email sending
	 *
	 * @return Response
	 */
	public function postSend(Request $request)
	{
           $req = $request->all();
          #dd($req);
          
          $validator = Validator::make($req, [
                             'title' => 'required',
                             'leads' => 'required',                               
                             'content' => 'required',
                   ]);    

                 if($validator->fails())
                  {
                       $ret = "<div class='alert alert-danger'><strong>Whoops!</strong> There were some problems signing you in.<br><br>";
                       $ret .= "<ul>";
					
                       foreach($messages->all() as $error) $ret .= "<li>".$error."</li>";            
                       $ret .= "</ul></div>";
                       return $ret;
                 }
                
                 else
                 {                 	
                     $title = $req["title"];
                     $leads = $req["leads"];
                     $content = $req["content"];
                     return "leads has ".count($leads)." elements";
                     $ret = "";
                     
                     if(count($leads) > 0)
                     {
                     	foreach($leads as $lead)
                         {
                         	$this->helpers->sendEmail($lead,$title,['content' => $content],'emails.bomb','view');
                             $ret.= "<h3 class='text-success'><i class='fa fa-paper-plane'></i> Message sent to $lead successfully</h3><br>";
                             sleep(10);
                         }
                     	
                     }
                     return $ret;                     
                  }                               
	}
	

}
