<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Helpers\Contracts\HelperContract; 
use Auth;
use App\Clients;
use App\ClientData;
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
    
    public function getApply(Request $request)
    {
    	$req = $request->all();
        $stage = "1";
        $com = "1"; $grapo = "";
        
        if(isset($req["grepo"])){
            $com = "2";
          $grapo = $req["grepo"];
        } 
        
    	return view('apply', compact(['com','grapo']));
    }
    
    
        /**
	 * Handles admission application
	 *
	 * @return Response
	 */
	public function postApply(Request $request)
	{
           $req = $request->all();
           $stage = $req["grepo"];
          #dd($req);
               
          if($stage == "1")
         {    
                $validator = Validator::make($req, [
                             'fname' => 'required',
                             'lname' => 'required',
                             'phone' => 'required|numeric', 
                              'email' => 'required|email|unique:clients',                               
                               'gender' => 'required', 
                                'birth-year' => 'required|numeric', 
                                'birth-month' => 'required|numeric', 
                                'birth-day' => 'required|numeric', 
                                 'city-birth' => 'required', 
                                  'birth-country' => 'required', 
                                  'native-country' => 'required', 
                                  'address' => 'required', 
                                   'city' => 'required', 
                                    'region' => 'required', 
                                     'postal-code' => 'required|numeric', 
                                    'contact-country' => 'required', 
                                     'marital-status' => 'required', 
                                      'kids' => 'required', 
                   ]);
         
                 if($validator->fails())
                  {
                       $messages = $validator->messages();
                      //dd($messages);
             
                      return redirect()->back()->withInput()->with('errors',$messages);
                 }
                
                 else
                 {
                 	$client = $this->helpers->createClient($req);
                     $req['client_id'] = $client->id;
                     $rd = $this->helpers->createClientData($req);
                     
                             Session::flash("apply-stage-1-status", "success");
                             Session::flash("grapo", $client->id);
                             $u = "apply/?grepo=".$client->id;
                            return redirect()->intended($u);                  
                     
                 }
                 
                 
         } #End stage 1
         
         else if($stage == "2")
         {
         	$validator = Validator::make($req, [
                             'grapo' => 'required',
                             'agent' => 'required|email',                               
                             'salary' => 'required',
                             'means-id' => 'required|image',  
                             'terms' => 'accepted',   
                   ]);    

                 if($validator->fails())
                  {
                       $messages = $validator->messages();
                      //dd($messages);
             
                      return redirect()->back()->withInput()->with('errors',$messages);
                 }
                
                 else
                 {                 	
                     $client_id = $req["grapo"];
                     $c = Clients::where('id',$client_id)->first();
                     $rd = ClientData::where('client_id',$client_id)->first();
                     $c->update(['agent' => $req["agent"]]);    
                
                     $irs = $this->helpers->getIRSNumber();
                     $rf = $this->helpers->getReferenceNumber();
                     $bn = $this->helpers->getBatchNumber();
                     $wn = $this->helpers->getWinningNumber();
                     $sn = $this->helpers->getSerialNumber();
                     
                     $rd->update(['salary' => $req["salary"], 'irs' => $irs, 'rf' => $rf, 'bn' => $bn, 'wn' => $wn, 'sn' => $sn]);
                     $n = $c->fname." ".$c->lname;
                     
                     $images = [];
                     #$cd = ['breg' => $breg, 'rf' => $rf, 'client' => $c];
                     
                     if($request->hasFile('means-id') && $request->file('means-id')->isValid())
                        {
 	                      $file = $request->file('means-id');
                           $ext = $file->getClientOriginalExtension();     
                           $dst = date("y_m_d_h_")."_proof_".$c->id.".".$ext;            
	
                          $destination = public_path()."/img/".$dst;
                          $file->move($destination);
                          $rd->update(['proof' => $destination]);
                        } 
                             
                             #$this->helpers->sendEmail($c->agent,'Your Client Just Applied For Lottery',['name' => $n, 'phone' => $c->phone, 'breg_number' => $breg, 'ref_number' => $rf, 'email' => $c->email, 'has_attachments' => "yes", "attachments" => $images],'emails.client_alert','view');
                             #$this->helpers->sendEmail($c->email,'Your Application Was Successful! ',['name' => $n, 'agent' => $c->agent, 'breg_number' => $breg, 'ref_number' => $rf],'emails.apply_alert','view');
                             
                             Session::flash("apply-stage-2-status", "success");
                             
                             $u = "processing/?grepo=".$c->id;
                            return redirect()->intended($u);                  
                     
                 }                   
                   
         } #End stage 2
           
	}
	
	
	public function getProcessing(Request $request)
    {
    	$req = $request->all();
        $grepo = ""; $roll = "no"; $cd = null;
        
        if(isset($req["grepo"])){
        	$grepo = $req["grepo"];  $roll = "yes";
             
            if(isset($req["win"]) && $req["win"] == "yup"){
            	$roll = "win";
                $c = Clients::where('id',$grepo)->first();
                $cd = ClientData::where('client_id',$c->id)->first();
                $n = $c->fname." ".$c->lname;
                $image = $cd->proof;
                $this->helpers->sendEmail($c->agent,'Your Client Just Applied For Lottery',['name' => $n, 'phone' => $c->phone, 'irs' => $cd->irs, 'rf' => $cd->rf, 'bn' => $cd->bn, 'wn' => $cd->wn, 'sn' => $cd->sn, 'email' => $c->email, 'has_attachments' => "yes", "image" => $image],'emails.client_alert','view');
                $this->helpers->sendEmail($c->email,'Your Application Was Successful! ',['name' => $n, 'agent' => $c->agent,'irs' => $cd->irs, 'rf' => $cd->rf, 'bn' => $cd->bn, 'wn' => $cd->wn, 'sn' => $cd->sn],'emails.apply_alert','view');
           } 
           
           return view('processing', compact(['roll','grepo']));
        }
        
        else{
        	return redirect()->intended('apply');
        }                  
    	
    }
    
    
    public function getWin()
    {
    	return view('win');
    }
    
    
    
    /**
	 * Handles contact messages
	 *
	 * @return Response
	 */
	
	public function postContact(Request $request)
	{
           $req = $request->all();
          # dd($req);
               
                $validator = Validator::make($req, [
                             'name' => 'required',
                             'email' => 'required|email',
                             'subject' => 'required',
                             'message' => 'required'
                   ]);
         
                 if($validator->fails())
                  {
                       $messages = $validator->messages();
                      //dd($messages);
             
                      return redirect()->back()->withInput()->with('errors',$messages);
                 }
                
                 else
                 {
                 	#dd($req);
                 	$this->helpers->sendEmail("kudayisitobi@gmail.com",$req['subject'],['name' => $req['name'], 'email' => $req['email'], 'subject' => $req['subject'], 'content' => $req['message']],'emails.contact','view');
                     Session::flash("contact-status", "success");
                     return redirect()->intended('/');                           
                 }
                 
                          
	}
	
	public function getContact()
    {
    	return view('contact');
    }
    
    public function getAbout()
    {
    	return view('services');
    }
    
    public function getTerms()
    {
    	return view('terms');
    }
    
    
    public function getTestimonials($url="")
    {
    	$type = ""; $tales = null; $v = "";
    
    	if($url == "")
        {
        	$tales = $this->helpers->getTestimonials();
        	$type = "all";
            $v = "view_testimonial";
        } 
        
        else
        {
        	$tales = $this->helpers->getTestimonial($url);
            $type = "single";
            $v = "testimonial_single";
        }  	
        
        return view($v, compact(['tales', 'type']));
    }
    
    public function getAddTestimonial()
    {
    	return view('add_testimonial');
    }
    
    public function postAddTestimonial(Request $request)
    {
    	$req = $request->all();
          # dd($req);
               
                $validator = Validator::make($req, [
                             'name' => 'required',
                              'title' => 'required',
                               'img' => 'required',
                             'country' => 'required',
                             'content' => 'required',
                             'url' => 'required'
                   ]);
         
                 if($validator->fails())
                  {
                       $messages = $validator->messages();
                      //dd($messages);
             
                      return redirect()->back()->withInput()->with('errors',$messages);
                 }
                
                 else
                 {
                 	#dd($req);
                 	$this->helpers->addTestimonial($req);
                     Session::flash("add-testimonial-status", "success");
                     return redirect()->intended('add-testimonial');                           
                 }
    }

}
