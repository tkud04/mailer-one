@include("html-header")
<input type="hidden" id="ri" value="no">
<!--------- Input errors -------------->
                    @if (count($errors) > 0)
                          @include('input-errors', ["errors" => $errors])
                     @endif   
                     
@yield('content') 


@include("html-footer")