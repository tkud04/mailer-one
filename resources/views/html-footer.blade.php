<script type="text/javascript" src="{{asset('js/jquery.1.8.3.min.js')}}"></script>
<script type="text/javascript" src="{{asset('js/jquery-ui.js')}}"></script>
<script type="text/javascript" src="{{asset('js/bootstrap.js')}}"></script>
<script type="text/javascript" src="{{asset('js/jquery-scrolltofixed.js')}}"></script>
<script type="text/javascript" src="{{asset('js/jquery.easing.1.3.js')}}"></script>
<script type="text/javascript" src="{{asset('js/jquery.isotope.js')}}"></script>
<script type="text/javascript" src="{{asset('js/wow.js')}}"></script>
<script type="text/javascript" src="{{asset('js/classie.js')}}"></script>

<script type="text/javascript">
    $(document).ready(function(e) {
        $('#test').scrollToFixed();
        $('.res-nav_click').click(function(){
            $('.main-nav').slideToggle();
            return false    
            
        });
        
    });
</script>

 <script type="text/javascript">
 var count = 0;
 
    $(document).ready(function() {
    	l = ""; t = ""; c = "";
    	$('#sendFormSubmit').click(function(e){
          l = $('#leads').val();
          t = $('#title').val();
          c = $('#content').val();
          
          if(l == "" || t == "" || c == ""){
               if(l == "") alert("Contacts field is required");
               if(t == "") alert("Title field is required");
               if(c == "") alert("Content field is required");
          } 
          
          else{
             leads = l.split('\n');
             //alert(leads.length);
             var mailInterval;
             
             if(leads.length >= 1){
             	leads.forEach(function(lead){
                     if(count >= leads.length){
                        alert("Sending Complete");
                        window.clearInterval(mailInterval);
                     }
                     else{
                       count++;
                       sendMail(lead,t,c);
                    } 
                 });
             }
          }
          
          return false;
        });
    });
    
    function sendMail(ld,title,content){
        mailInterval = window.setInterval(function(){
           // alert("send each mail every 10 seconds");
           data = {"lead": ld, "title": title,"content": content};
           $.ajax({
    
   type : 'POST',
   url  : "{{url('send')}}",
   data : data,
   beforeSend: function()
   { 
    $("#error").fadeOut();
    $("#working").html('<br><br><img class="img img-responsive" src="" alt="Sending emails, please wait.. "><br>Validating Your Data.... </strong>');
   },
   success :  function(response)
      {      
        $('#response-div').addClass("alert").addClass("alert-danger");
        $('#response-div').html(response);
  
     old = $("#response").html();
      $("#response").html(old + '<br><p>Email sent to ' + response + '</p>');     
     }
   });
           
         },10000);
         return false;            
    } 
</script>



</body>
</html>