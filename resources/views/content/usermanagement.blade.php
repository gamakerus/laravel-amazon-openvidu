
<div class = "row justify-content-between">
    <div class="col-lg-3 col-md-6 col-sm-6">
        <div class="card card-stats">
            <div class="card-header card-header-primary card-header-icon">
                <div class="card-icon">
                    <i class="material-icons">people</i>
                </div>
                <p class="card-category">Number of Users</p>
                <h3 class="card-title"><?php echo count($users); ?>
                </h3>
            </div>
            <div class="card-footer">
                {{-- <div class="stats">
                    <i class="material-icons text-danger">warning</i>
                    <a href="javascript:;">Get More Space...</a>
                </div> --}}
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 col-sm-6">
        <div class="card card-stats">
            <div class="card-header card-header-info card-header-icon">
                <div class="card-icon">
                    <i class="material-icons">people</i>
                </div>
                <p class="card-category ">Active Users</p>
                <h3 class="card-title approveUsers"><?php echo $approved_users; ?>
                </h3>
            </div>
            <div class="card-footer">
                {{-- <div class="stats">
                    <i class="material-icons text-danger">warning</i>
                    <a href="javascript:;">Get More Space...</a>
                </div> --}}
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 col-sm-6">
        <div class="card card-stats">
            <div class="card-header card-header-danger card-header-icon">
                <div class="card-icon">
                    <i class="material-icons">people</i>
                </div>
                <p class="card-category">Inactive Users</p>
                <h3 class="card-title notapproveUsers"><?php echo count($users)-$approved_users; ?>
                </h3>
            </div>
            <div class="card-footer">
                {{-- <div class="stats">
                    <i class="material-icons text-danger">warning</i>
                    <a href="javascript:;">Get More Space...</a>
                </div> --}}
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 col-sm-6">
        <div class="card card-stats">
            <div class="card-header card-header-warning card-header-icon">
                <div class="card-icon">
                    <i class="material-icons">people</i>
                </div>
                <p class="card-category">Verified Users</p>
                <h3 class="card-title"><?php echo $verified_users; ?>
                </h3>
            </div>
            <div class="card-footer">
                {{-- <div class="stats">
                    <i class="material-icons text-danger">warning</i>
                    <a href="javascript:;">Get More Space...</a>
                </div> --}}
            </div>
        </div>
    </div>
</div>
<div class = 'row'>
    <div class="col-md-12">
        <div class="card">
        <div class="card-header card-header-icon card-header-primary no-print">
            <div class="card-icon">
            <i class="material-icons">people</i>
            </div>
            <div class = 'row'>
                <div class = 'col-md-6'><h4 class="card-title ">User List</h4></div>
                <div class = 'col-md-6 text-right downloaddiv'>
                    <select name = '' id = '' class="form-control selectpicker downloadtype">
                        <option value="0">Download</option>
                        <option value="1">CSV</option>
                        <option value="2">PDF</option>
                    </select>
                    <button class = 'btn btn-info adduserbtn' style = "margin-left:10px">Add New</button>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
            <table class="table table-striped table-no-bordered table-hover" id = "usertable">
                <thead class=" text-primary">
                    <th>Joined Date</th>
                    <th>Full Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Verified</th>
                    <th>Status</th>
                    <th>Action</th>
                </thead>
                <tbody>
                    @foreach($users as $users_array)
                    <tr id = "{{$users_array->id}}">
                        <td>{{date("D, M j Y",strtotime($users_array->created_at))}}</td>
                        <td>{{$users_array->fname." ".$users_array->lname}}</td>
                        <td>{{$users_array->email}}</td>
                        <td>{{$users_array->phone}}</td>
                        <td>
                            <?php if($users_array->status == 1): ?>
                                <span class = 'user_active'>Done</span>
                            <?php elseif($users_array->status == 0): ?>
                                <span class = 'user_deactive'>Pending</span>
                            <?php endif ?>
                        </td>
                        <td>
                            <?php if($users_array->allowed == 1): ?>
                                <span class = 'user_active'>Active</span>
                            <?php elseif($users_array->allowed == 0): ?>
                                <span class = 'user_deactive'>Inactive</span>
                            <?php endif ?>
                        </td>
                        <td><span class = 'user_service'><i class = 'fa fa-suitcase'></i></span>&nbsp;<span class = '<?php echo $users_array->allowed?'user_ban':'user_unban'; ?>'><i class = 'fa <?php echo $users_array->allowed?'fa-ban':'fa-check'; ?>'></i></span>&nbsp;<span class = 'user_view'><i class = 'fa fa-eye'></i></span>&nbsp;<span class = 'resetpwd'><i class = 'fa fa-key'></i></span>&nbsp;<span class = 'user_delete'><i class = 'fa fa-trash'></i></span></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            </div>
        </div>
        </div>
    </div>
</div>
<!-- The Modal -->
<div class="modal fade" id="user_detail_modal">
    <div class="modal-dialog">
            <div class="modal-content" style = "margin-top:100px">
            <div class="card-avatar">
                <a href="javascript:;">
                    <img class="img chosenuserimg" src="">
                </a>
            </div>
            <!-- Modal Header -->
            <div class="modal-header">
                    <h4 class="modal-title usernamefordetail" style="font-weight:500;"></h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            
            <!-- Modal body -->
            <div class="modal-body">
                <div class = "row">
                    <div class = "col-md-12">
                        <div class = "row align-items-center">
                            <div class = "col-md-6">
                                <span class = 'usertypeview'></span>
                            </div>
                        </div>
                        <div class = "row">
                            <div class = "col-md-12">
                                <h6>Full Name : <span class = "chosenfullname"></span></h6>
                                <h6>Email : <span class = "chosenemail"></span></h6>
                                <h6>Phone : <span class = "chosenphone"></span></h6>
                                <h6>DOB : <span class = "chosendob"></span></h6>
                                <h6>Address : <span class = "chosenaddress"></span></h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Modal footer -->
            <div class="modal-footer">
                    <button type="button" class="btn btn-primary btn-link" data-dismiss="modal">Done</button>
                    <button type="button" class="btn btn-danger btn-link" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!--- end modal -->
<!-- The Modal -->
<div class="modal fade" id="user_service_modal">
    <div class="modal-dialog">
            <div class="modal-content" style = "margin-top:100px">
                <!-- Modal Header -->
                <div class="modal-header">
                        <h4 class="modal-title" style="font-weight:500;">Requested Service History</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                
                <!-- Modal body -->
                <div class="modal-body">
                    <div class = "row requestitem">
                        
                    </div>
                </div>
                <!-- Modal footer -->
                <div class="modal-footer">
                        <button type="button" class="btn btn-primary btn-link viewallservice" data-dismiss="modal">View All</button>
                        <button type="button" class="btn btn-danger btn-link" data-dismiss="modal">Close</button>
                </div>
        </div>
    </div>
</div>
<!-- The Modal -->
<div class="modal fade" id="user_add_modal">
    <div class="modal-dialog">
        <div class="modal-content">
        
            <!-- Modal Header -->
            <div class="modal-header">
                    <h4 class="modal-title" style="font-weight:500;">New Client</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
        
            <!-- Modal body -->
            <div class="modal-body">
                <form id = 'clientaddform' action="{{url('admin/clientaddForm')}}" method="POST">
                    {{ csrf_field() }}
                    <div class = "row">
                        <div class = "col-md-6">   
                            <div class="form-group bmd-form-group">
                                <label class="bmd-label-static">First Name</label>
                                <input name = 'fname' id = 'fname' type="text" class="form-control">
                            </div>
                        </div>
                        <div class = "col-md-6">   
                            <div class="form-group bmd-form-group">
                                <label class="bmd-label-static">Last Name</label>
                                <input name = 'lname' id = 'lname' type="text" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class = "row">
                        <div class = "col-md-6">   
                            <div class="form-group bmd-form-group">
                                <label class="bmd-label-static">Email</label>
                                <input name = 'email' id = 'email' type="email" class="form-control">
                            </div>
                        </div>
                        <div class = "col-md-6">   
                            <div class="form-group bmd-form-group">
                                <label class="bmd-label-static">Phone</label>
                                <input name = 'phone' id = 'phone' type="text" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class = 'row mt-2'>
                        <div class = 'col-md-6'>
                            <div class="form-group bmd-form-group">
                                <label class="bmd-label-static">Gender</label>
                                <select name = 'gender' id = 'gender' class="form-control" style = "height:36px">
                                    <option value="0">Male</option>
                                    <option value="1">Female</option>
                                </select>
                            </div>
                        </div>
                        <div class = 'col-md-6'>
                            <div class="form-group bmd-form-group">
                                <label class="bmd-label-static">DOB</label>
                                <input name = 'dob' id = 'dob' type="text" class="form-control datepicker">
                            </div>
                        </div>
                    </div>
                    <div class = 'row mt-2'>
                        <div class = 'col-md-12'>
                            <div class="form-group">
                                <label for="client_status">How do you plan to for the service?</label>
                                <select name = 'paymethod' id = 'paymethod' class="form-control selectpicker">
                                    <option value="1">Family and Personal funds</option>
                                    <option value="2">Long-term care insurance</option>
                                    <option value="3">Other Sources</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <input type = "hidden" id = "dialCode" name = "dialCode" />
                    <input type = "hidden" id = "countryISO" name = "countryISO" />
                </form>
            </div>
            <!-- Modal footer -->
            <div class="modal-footer">
                    <button type="button" class="btn btn-primary btn-link clientaddbtn">Done</button>
                    <button type="button" class="btn btn-danger btn-link" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- The Modal -->
<div class="modal fade" id="resetpwd_modal">
    <div class="modal-dialog">
        <div class="modal-content">
        
            <!-- Modal Header -->
            <div class="modal-header">
                    <h4 class="modal-title" style="font-weight:500;">Reset Password</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
        
            <!-- Modal body -->
            <div class="modal-body">
                <form id = 'resetpwdForm' action="{{url('admin/resetpwdForm')}}" method="POST">
                    {{ csrf_field() }}
                    <div class = "row">
                        <div class = "col-md-6">   
                            <div class="form-group bmd-form-group">
                                <label class="bmd-label-static">Password</label>
                                <input name = 'password' id = 'password' type="password" class="form-control">
                            </div>
                        </div>
                        <div class = "col-md-6">   
                            <div class="form-group bmd-form-group">
                                <label class="bmd-label-static">Confirm Password</label>
                                <input name = 'cpassword' id = 'cpassword' type="password" class="form-control">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <!-- Modal footer -->
            <div class="modal-footer">
                    <button type="button" class="btn btn-primary btn-link resetpwdbtn">Done</button>
                    <button type="button" class="btn btn-danger btn-link" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@yield('alarm')
@if ($errors->any())
    <script>
        $.notify({
            icon: "add_alert",
            message: "This Email has been already used in our system. Please try again."

            }, {
            type: 'info',
            timer: 1000,
            placement: {
                from: 'top',
                align: 'center'
            }
        });
    </script>
@endif
<script>
    var input = document.querySelector("#phone");
    var iti = window.intlTelInput(input, {
        utilsScript: "{{asset('adminassets/js/utils.js')}}",
    });
    md.initFormExtendedDatetimepickers();
    $(document).ready(function() {
        
        $('.user_delete').click(function(){
            select_user = $(this).parent().parent();
            Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                    if (result.value) {
                        $.ajax({
                            type:'DELETE',
                            url:"{{ url('/admin/deleteuser') }}",
                            data:{'_token':'<?php echo csrf_token() ?>',id:select_user.attr('id')},
                            success:function(data){
                                if(data['success'] == 'success'){
                                    Swal.fire({
                                        text: "Delete successfully",
                                        type: 'success',
                                        confirmButtonColor: '#3085d6',
                                        confirmButtonText: 'Done'
                                    }).then((result) => {
                                        select_user.remove();
                                    });
                                }
                                else{
                                    Swal.fire({
                                        text: "Delete failed",
                                        type: 'warning',
                                        confirmButtonColor: '#3085d6',
                                        confirmButtonText: 'Done'
                                    });
                                }
                            }
                        });
                    }
            });
        });
        $(document).on('click','.user_ban',function(){
            select_user = $(this).parent().parent();
            Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, ban it!'
            }).then((result) => {
                    if (result.value) {
                        $.ajax({
                            type:'POST',
                            url:"{{ url('/admin/banuser') }}",
                            data:{'_token':'<?php echo csrf_token() ?>',id:select_user.attr('id')},
                            success:function(data){
                                if(data['success'] == 'success'){
                                    Swal.fire({
                                        text: "Ban successfully",
                                        type: 'success',
                                        confirmButtonColor: '#3085d6',
                                        confirmButtonText: 'Done'
                                    });
                                }
                                else{
                                    Swal.fire({
                                        text: "Ban failed",
                                        type: 'warning',
                                        confirmButtonColor: '#3085d6',
                                        confirmButtonText: 'Done'
                                    });
                                }
                            }
                        });
                        $(this).parent().parent().children().eq(5).children().eq(0).removeClass('user_active');
                        $(this).parent().parent().children().eq(5).children().eq(0).addClass('user_deactive');
                        $(this).parent().parent().children().eq(5).children().eq(0).html('Inactive');
                        $(this).removeClass("user_ban");
                        $(this).addClass("user_unban");
                        $(this).children().eq(0).removeClass('fa-ban');
                        $(this).children().eq(0).addClass('fa-check');
                        $(".approveUsers").html(parseInt($(".approveUsers").html())-1);
                        $(".notapproveUsers").html(parseInt($(".notapproveUsers").html())+1);
                    }
            });
        });
        $(document).on('click','.user_unban',function(){
            select_user = $(this).parent().parent();
            Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, Approve it!'
            }).then((result) => {
                    if (result.value) {
                        $.ajax({
                            type:'POST',
                            url:"{{ url('/admin/unbanuser') }}",
                            data:{'_token':'<?php echo csrf_token() ?>',id:select_user.attr('id')},
                            success:function(data){
                                if(data['success'] == 'success'){
                                    Swal.fire({
                                        text: "Approve successfully",
                                        type: 'success',
                                        confirmButtonColor: '#3085d6',
                                        confirmButtonText: 'Done'
                                    });
                                }
                                else{
                                    Swal.fire({
                                        text: "Approve failed",
                                        type: 'warning',
                                        confirmButtonColor: '#3085d6',
                                        confirmButtonText: 'Done'
                                    });
                                }
                            }
                        });
                        $(this).parent().parent().children().eq(5).children().eq(0).removeClass('user_deactive');
                        $(this).parent().parent().children().eq(5).children().eq(0).addClass('user_active');
                        $(this).parent().parent().children().eq(5).children().eq(0).html('Active');
                        $(this).removeClass("user_unban");
                        $(this).addClass("user_ban");
                        $(this).children().eq(0).removeClass('fa-check');
                        $(this).children().eq(0).addClass('fa-ban');
                        $(".approveUsers").html(parseInt($(".approveUsers").html())+1);
                        $(".notapproveUsers").html(parseInt($(".notapproveUsers").html())-1);
                    }
            });
        });
        $('#usertable').DataTable({
            "pagingType": "full_numbers",
            "lengthMenu": [
            [10, 25, 50, -1],
            [10, 25, 50, "All"]
            ],
            responsive: true,
            language: {
            search: "_INPUT_",
            searchPlaceholder: "Search records",
            }
        });
        $(".user_view").click(function(){
            select_user = $(this).parent().parent();
            $(".usernamefordetail").html($(this).parent().parent().children().eq(1).html());
            $.ajax({
                type:'POST',
                url:"{{ url('/admin/chosenuser') }}",
                data:{'_token':'<?php echo csrf_token() ?>',id:select_user.attr('id')},
                dataType:"json",
                success:function(data){
                    if(data[0]['avatar'] != null && data[0]['avatar'] != "")
                        $(".chosenuserimg").attr('src',"https://operations-flexhealth-me.s3.us-east-1.amazonaws.com/"+data[0]['avatar']);
                    else
                        $(".chosenuserimg").attr('src',$(".base_url").val()+"adminassets/img/avatar.png");
                    if(data[0]['paymethod'] == 1)
                        $(".usertypeview").html("Family and Personal Funds");
                    else if(data[0]['paymethod'] == 2)
                        $(".usertypeview").html("Long-term Care Insurance");
                    else
                        $(".usertypeview").html("Other Sources");
                    $(".chosenfullname").html(data[0]['fname']+" "+data[0]['lname']);
                    $(".chosenemail").html(data[0]['email']);
                    $(".chosenphone").html(data[0]['phone']);
                    var date = new Date(data[0]['dob']);
                    $(".chosendob").html(((date.getMonth() > 8) ? (date.getMonth() + 1) : ('0' + (date.getMonth() + 1))) + '/' + ((date.getDate() > 9) ? date.getDate() : ('0' + date.getDate())) + '/' + date.getFullYear());
                    $(".chosenaddress").html(data[0]['address']);
                    if(data[0]['service'] == 1)
                        $(".userserviceview").html("Caregiver");
                    else if(data[0]['service'] == 2)
                        $(".userserviceview").html("Nursing");
                    else
                        $(".userserviceview").html("Therapy");
                    $("#user_detail_modal").modal("show");
                }
            });
        });
        $(".user_service").click(function(){
            $(".viewallservice").attr('userid',$(this).parent().parent().attr('id'));
            $.ajax({
                type:'POST',
                url:"{{ url('/admin/viewrequestservice') }}",
                data:{'_token':'<?php echo csrf_token() ?>',id:$(this).parent().parent().attr('id')},
                dataType:'json',
                success:function(data){
                    $(".requestitem").empty();
                    if(data.length == 0){
                        $(".requestitem").append("<div class='col-md-12 text-center'><h6>There is no service requested</h6></div>");
                    }
                    for(var i = 0;i < data.length; i++){
                        if(data[i]['type'] == 1)
                            var servicetype = "Caregiver";
                        else if(data[i]['type'] == 2)
                            var servicetype = "Nursing";
                        else
                            var servicetype = "Therapy";

                        var date = new Date(data[i]['created_at']);
                        if(data[i]['service'] == "0"){
                            var serviceitem = "<h6 class = 'item_property'>Other Request - "+data[i]['othertext']+"</h6>";
                        }
                        else{
                            var serviceArray = data[i]['sname'].split("<||>");
                            var serviceitem = "";
                            for(var j = 0;j < serviceArray.length; j++){
                                serviceitem += "<h6 class = 'item_property'>"+serviceArray[j]+"</h6>";
                            }
                        }
                        $(".requestitem").append("<div class = 'col-md-12'><div class = 'row'><div class = 'col-md-6'><div class = 'requestservice'>"+servicetype+"</div></div><div class = 'col-md-6'><div class = 'requestservicedate text-right'>"+((date.getMonth() > 8) ? (date.getMonth() + 1) : ('0' + (date.getMonth() + 1))) + '/' + ((date.getDate() > 9) ? date.getDate() : ('0' + date.getDate())) + '/' + date.getFullYear()+"</div></div></div><div class = 'row'><div class = 'col-md-12'>"+serviceitem+"</div></div></div>");
                        
                    }
                    $("#user_service_modal").modal('show');
                }
            });
        });
        $(".adduserbtn").click(function(){
            $("#user_add_modal").modal('show');
        });
        $(".clientaddbtn").click(function(){
            Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, submit it!'
            }).then((result) => {
                if (result.value) {
                    $("#dialCode").val(iti.getSelectedCountryData().dialCode);
                    $("#countryISO").val(iti.getSelectedCountryData().iso2);
                    $("#clientaddform").submit();
                }
            });
        });
        $(".resetpwd").click(function(){
            $(".resetpwdbtn").attr('userid',$(this).parent().parent().attr('id'));
            $("#resetpwd_modal").modal('show');
        });
        $(".resetpwdbtn").click(function(){
            if($("#password").val() == ""){
                $.notify({
                    icon: "add_alert",
                    message: "Please enter new password"

                    }, {
                    type: 'info',
                    timer: 1000,
                    placement: {
                        from: 'top',
                        align: 'center'
                    }
                });
            }
            else if($("#password").val().length < 8){
                $.notify({
                    icon: "add_alert",
                    message: "Password should be at least 8 characters."

                    }, {
                    type: 'info',
                    timer: 1000,
                    placement: {
                        from: 'top',
                        align: 'center'
                    }
                });
            }
            else if($("#password").val() != $("#cpassword").val()){
                $.notify({
                    icon: "add_alert",
                    message: "Password confirm password"

                    }, {
                    type: 'info',
                    timer: 1000,
                    placement: {
                        from: 'top',
                        align: 'center'
                    }
                });
            }
            else{
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, submit it!'
                }).then((result) => {
                    if (result.value) {
                        $.ajax({
                            type:'POST',
                            url:"{{ url('/admin/resetpwd') }}",
                            data:{'_token':'<?php echo csrf_token() ?>',id:$(this).attr('userid'),pwd:$("#password").val()},
                            success:function(data){
                                if(data['success'] == 'success'){
                                    Swal.fire({
                                        text: "Reset Password successfully",
                                        type: 'success',
                                        confirmButtonColor: '#3085d6',
                                        confirmButtonText: 'Done'
                                    });
                                    $("#resetpwd_modal").modal('hide');
                                    $("#password").val("");
                                    $("#cpassword").val("");
                                }
                                else{
                                    Swal.fire({
                                        text: "Reset Password failed",
                                        type: 'warning',
                                        confirmButtonColor: '#3085d6',
                                        confirmButtonText: 'Done'
                                    });
                                }
                            }
                        });
                    }
                });
            }
        });
        $(".downloadtype").change(function(){
            if($(this).val() == 1){
                $('body').append("<form id = 'expertCSV' action='{{ url('/admin/expertCSVforusers') }}' method='get'>"+'{{ csrf_field() }}'+"</form>");
                $('#expertCSV').submit();
            }
            else if($(this).val() == 2){
                $('body').append("<form id = 'downloadPDFforusers' action='{{ url('/admin/downloadPDFforusers') }}' method='get'>"+'{{ csrf_field() }}'+"</form>");
                $('#downloadPDFforusers').submit();
            }
            $(this).val(0);
            $(this).selectpicker('refresh');
        });
        $(".viewallservice").click(function(){
            $('body').append("<form id = 'viewallrequest' action='{{ url('/admin/viewallrequest') }}' method='post'>"+'{{ csrf_field() }}'+"<input type = 'hidden' name = 'id' value = '"+$(this).attr('userid')+"' /></form>");
            $('#viewallrequest').submit();
        });
    });
</script>