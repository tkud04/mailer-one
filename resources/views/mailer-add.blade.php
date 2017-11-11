<div class="container-fluid">
  <div class="row">
  	<div class="col-lg-12 col-xs-12">
  	  <center><h2>Simple AJAX Mailer</h2></center>
      </div>
  </div><br>
  	
  <div class="row">
  	<form id="sendForm">
  	<div class="col-lg-5 col-sm-5 col-md-5 col-xs-12">	  
  	       <h4>Enter your contacts here (one email address per line) <span style="color:red;">*</span></h4>
             <textarea class="form-control" id="leads" rows="10" required></textarea>
      </div>
      
  	<div class="col-lg-7 col-sm-7 col-md-7 col-xs-12">
  	       <h4>Message title <span style="color:red;">*</span></h4>
             <input type="text" class="form-control" id="title" required><br>
             	
             <h4>Enter your message here<span style="color:red;">*</span></h4>
             <textarea class="form-control" id="content" rows="10" required></textarea>
      </div>
     </form>
  </div><br> 
  
   <div class="row">
  	<div class="col-lg-12 col-xs-12">
  	  <center> <button id="sendFormSubmit" class="btn btn-success">Send</button></center>
      </div>
  </div><br>
  	
  <div class="row">
  	<div class="col-lg-12 col-xs-12">
  	  <center> 
           <div id="working"></div><br><br>
           <div id="response"></div>
        </center>
      </div>
  </div><br>
</div>
