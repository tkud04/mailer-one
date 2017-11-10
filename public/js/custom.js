(function ($) {
                
    // Navigation scrolls
    $(".navbar-nav li a").on('click', function(event) {
        $('.navbar-nav li').removeClass('active');
        $(this).closest('li').addClass('active');
        var $anchor = $(this);
        var nav = $($anchor.attr('href'));
        if (nav.length) {
        $('html, body').stop().animate({				
            scrollTop: $($anchor.attr('href')).offset().top				
        }, 1500, 'easeInOutExpo');
        
        event.preventDefault();
        }
    });
    
    // Add smooth scrolling to all links in navbar
    $("a.mouse-hover, a.get-quote").on('click', function(event) {
      var hash = this.hash;
      if( hash ) {
        event.preventDefault();
        $('html, body').animate({
            scrollTop: $(hash).offset().top
        }, 1500, 'easeInOutExpo');
      }
    });
    
    $("#apply-submit").on('click', function(e){
         e.preventDefault();
         submitForm();
     });
})(jQuery);


function submitForm()
    {  
   var data = $("#loginForm").serialize();
    alert(data);
    
   $.ajax({
    
   type : 'POST',
   url  : "{{url('apply')}}",
   data : data,
   beforeSend: function()
   { 
    $("#error").fadeOut();
    $("#response").html('<br><br><div class="alert alert-info" role="alert" style=" text-align: center;"><strong class="block" style="font-weight: bold;">  <i class = "fa fa-spinner fa-2x slow-spin"></i>  Processing Your Request.... </strong></div>');
   },
   success :  function(response)
      {      
        $('#response-div').addClass("alert").addClass("alert-danger");
        $('#response').html(response);
  /**
     if(response=="ok"){
      $("#working").html('<br><br><div class="alert alert-success alert-dismissable"><strong class="block"> <i class="fa fa-check"></i> &nbsp; Success! Redirecting to Dashboard...</strong></div>');
     setTimeout(' window.location.href = "dashboard"; ',3000);
     }
     else if(response=="disabled"){
      $("#working").html('<br><br><div class="alert alert-danger alert-dismissable"><strong class="block"> <i class="fa fa-check"></i> &nbsp; Your account has been disabled.</strong></div>');
     }
     else if(response=="admin"){
      $("#working").html('<br><br><div class="alert alert-success alert-dismissable"><strong class="block"> <i class="fa fa-check"></i> &nbsp; Welcome Admin! Redirecting to Admin panel...</strong></div>');
      setTimeout(' window.location.href = "admin/dashboard"; ',3000);
     }     
     else{
         
      $("#error").fadeIn(1000, function(){      
    $("#error").html('<br><br><div class="alert alert-danger"> '+response+'</div>');
           $("#working").html('');
         });
     }
     **/
     
     }
   });
    return false;
  }