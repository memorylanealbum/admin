<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
      <meta name="description" content="">
      <meta name="author" content="">
      <title>Admin | Memorylanealbum</title>
      <!-- Bootstrap core CSS -->
      <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
      <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
      <link href="css/custom.css" rel="stylesheet">
      <style>
        .hide{display: none; visibility: hidden;}
        .btn-s{height: 34px; width: 90%;}
        .btn-c{height: 34px; width: 10%;}
        .logout {
            position: absolute;
            margin: -30px 0px 0px 0px;
            background: #fff;
            padding: 0px 5px;
            font-weight: bold;
            color: #0275d8;
            right: 30px;
        }
      </style>
   </head>
   <body>
      <!-- Page Content -->
      <div class="container">
         <div class="row">
            <div class="col-lg-12 text-center">
               <div class="search-box">
                  <span class="attach-label-box">Filtering Options</span>
                  <span class="logout">
                        <a href="{{ route('logout') }}"
                            onclick="event.preventDefault();
                                     document.getElementById('logout-form').submit();">
                            Logout
                        </a>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            {{ csrf_field() }}
                        </form>
                  </span>
                  <form method="post" id="form">
                     <div class="col-xs-2 padding-zero">
                        <div class="checkbox">
                           <label class="cursor-mouse">Subscription Type</label>
                        </div>
                        <div class="checkbox ">
                           <label class="cursor-mouse">Date</label>
                        </div>
                     </div>
                     <div class="col-xs-4 padding-zero">
                        <div class="checkbox">
                           <select class="form-control" name="subscription_type">
                              <option value="">Not Selected</option>
                              <option value="weekly">Weekly</option>
                              <option value="monthly">Monthly</option>
                           </select>
                           <input hidden="" name="highest_level" value="1">
                        </div>
                        <div class="checkbox">
                           <input class="form-control" name="date" readonly>
                        </div>
                     </div>
                     <div class="col-xs-2 padding-zero">
                        <div class="checkbox">
                           <label class="cursor-mouse">Users</label>
                        </div>
                     </div>
                     <div class="col-xs-4 padding-zero">
                        <div class="checkbox">
                           <div id="country_div">
                              <select disabled="" class="form-control" name="users">
                                 <option value="">Not Selected</option>
                              </select>
                           </div>
                        </div>
                        <div class="checkbox">
                           <button type="reset" class="btn btn-default btn-sm pull-right btn-c"><i class="fa fa-times" aria-hidden="true"></i></button>
                           <button type="button" id="submit" class="btn btn-default btn-sm pull-right btn-s"><i class="fa fa-search" aria-hidden="true"></i> Search</button>
                        </div>
                     </div>
                     <div class="clearfix"></div>
                  </form>
               </div>
               <div class="heading">
                  User Details
               </div>
               <table class="table hide" id="user_details">
                  <tbody>
                     <tr>
                        <td class="col-xs-4"><img id="profile_pic" src="img/blank-profile.png" style="height: 200px; width:200px;" /></td>
                        <td class="col-xs-4">
                           <table class="table">
                              <tbody>
                                 <tr>
                                    <td>Name</td>
                                    <td id="name"></td>
                                 </tr>
                                 <tr>
                                    <td>Email</td>
                                    <td id="email"></td>
                                 </tr>
                              </tbody>
                           </table>
                        </td>
                     </tr>
                  </tbody>
               </table>
               <div class="heading">
                  Memories
               </div>
               <div class="container">
                  <div class="row text-center text-lg-left" id="images">

                  </div>
               </div>
            </div>
         </div>
      </div>
      <!-- Bootstrap core JavaScript -->
      <script src="vendor/jquery/jquery.min.js"></script>
      <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
      <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
   </body>
   <script>
    $(document).ready(function(){
        var stringConstructor = "test".constructor;
        var arrayConstructor = [].constructor;
        var objectConstructor = {}.constructor;
        function whatIsIt(object) {
            if (object === null) {
                return "null";
            }
            else if (object === undefined) {
                return "undefined";
            }
            else if (object.constructor === stringConstructor) {
                return "String";
            }
            else if (object.constructor === arrayConstructor) {
                return "Array";
            }
            else if (object.constructor === objectConstructor) {
                return "Object";
            }
            else {
                return "don't know";
            }
        }
        $('[name=subscription_type]').on('change', function(){
            $('#user_details').addClass("hide");
            if($(this).val() != ""){
                $.ajax({
                   url: "{{ url('request') }}",
                   type: "get",
                   data: "url=/admin/users/"+$(this).val(),
                   success: function(response){
                       if(whatIsIt(response) == "String")
                           response = JSON.parse(response);
                       var html='<option value="">Not Selected</option>'
                       if(response.status){
                            $.each(response.data, function(i, item) {
                                html += '<option data-email="'+item.email+'" data-name="'+item.name+'" data-image="'+item.image+'" value="'+item.id+'">'+item.name+'</option>';
                            });
                            $('[name=users]').html(html).prop('disabled', false);
                       }
                   }
                });
            }
        });
        $('[name=users]').on('change', function(){
            $('#user_details').addClass("hide");
            if($(this).val() != ""){
                $('#user_details').removeClass("hide");
                $('#name').html($(this).find(':selected').attr('data-name'));
                $('#email').html($(this).find(':selected').attr('data-email'));
                $('#profile_pic').attr("src", "{{env('IMAGE_URL')}}/"+$(this).find(':selected').attr('data-image'));
                $.ajax({
                   url: "{{ url('request') }}",
                   type: "get",
                   data: "url=/admin/user/first-date/"+$(this).val(),
                   success: function(response){
                       if(whatIsIt(response) == "String")
                           response = JSON.parse(response);
                       if(response.status){
                            $( "[name=date]" ).datepicker({
                                dateFormat:'yy-mm-dd',
                                showButtonPanel:true,
                                changeMonth: true,
                                changeYear: true,
                                minDate: new Date(response.data.year, response.data.month - 1, response.data.month)
                            });
                       }
                       else{
                            $( "[name=date]" ).datepicker( "destroy" );
                            $( "[name=date]" ).removeClass("hasDatepicker").removeAttr('id');
                       }
                   }
                });
            }
            else
            {
                $('#name').html("");
                $('#email').html("");
                $('#profile_pic').attr("/img/blank-profile.png");
            }
        });
        $('#submit').on('click', function(e){
            $('#images').addClass("hide");
            if($('[name=subscription_type]').val() == "" || $('[name=users]').val() == "" || $('[name=date]').val() == "")
                return;
            $.ajax({
               url: "{{ url('request') }}",
               type: "get",
               data: "url=/admin/image/"+$('[name=users]').val()+"/"+$('[name=date]').val(),
               success: function(response){
                   if(whatIsIt(response) == "String")
                       response = JSON.parse(response);
                   if(response.status){
                       var html = "";
                        $.each(response.data, function(i, item) {
                            if(item != ""){
                                html += '<div class="col-lg-3 col-md-4 col-xs-6">'
                                html += '<a href="{{env('IMAGE_URL')}}/'+item.image+'" target="_blank" class="d-block mb-4 h-100">'
                                html += '<img class="img-fluid img-thumbnail" src="{{env('IMAGE_URL')}}/'+item.thumb_320+'" alt=""></a></div>';
                            }
                            else{
                                html += '<div class="col-lg-3 col-md-4 col-xs-6">'
                                html += '<a href="#" class="d-block mb-4 h-100">'
                                html += '<img class="img-fluid img-thumbnail" src="img/na.jpg" alt=""></a></div>';
                            }
                        });
                        console.log(html)
                        $('#images').html(html).removeClass("hide");
                   }
                   else{
                       
                   }
               }
            });
        });
    });
   </script>
</html>