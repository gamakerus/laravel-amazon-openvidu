
<div class = "row justify-content-center">
    <div class="col-lg-3 col-md-6 col-sm-6">
        <div class="card card-stats">
            <div class="card-header card-header-primary card-header-icon">
                <div class="card-icon">
                    <i class="material-icons">people</i>
                </div>
                <p class="card-category">Number of Providers</p>
                <h3 class="card-title"><?php echo $providersCnt; ?>
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
                <p class="card-category">Flexhealth</p>
                <h3 class="card-title"><?php echo $flexhealth_providers; ?>
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
                <p class="card-category">Direct Hire</p>
                <h3 class="card-title"><?php echo $directhire_providers; ?>
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
                <p class="card-category">Verified Providers</p>
                <h3 class="card-title"><?php echo $verified_providers; ?>
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
<div class = "row justify-content-center">
    <div class="col-lg-2 col-md-6 col-sm-6">
        <div class="card card-stats providerpanel <?php echo $servicetype==1?'panel_active':''; ?>" servicetype = "1">
            <h6 class="text-center mt-2">Caregivers</h6>
            <h6 class="text-center"><?php echo $caregivers; ?></h6>
        </div>
    </div>
    <div class="col-lg-2 col-md-6 col-sm-6">
        <div class="card card-stats providerpanel <?php echo $servicetype==2?'panel_active':''; ?>" servicetype = "2">
            <h6 class="text-center mt-2">Nurses</h6>
            <h6 class="text-center"><?php echo $nurses; ?></h6>
        </div>
    </div>
    <div class="col-lg-2 col-md-6 col-sm-6">
        <div class="card card-stats providerpanel <?php echo $servicetype==3?'panel_active':''; ?>" servicetype = "3">
            <h6 class="text-center mt-2">Therapists</h6>
            <h6 class="text-center"><?php echo $therapists; ?></h6>
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
                    <div class = 'col-md-6'><h4 class="card-title ">Provider List</h4></div>
                    <div class = 'col-md-6 text-right downloaddiv'>
                        <select name = '' id = '' class="form-control selectpicker downloadtype">
                            <option value="0">Download</option>
                            <option value="1">CSV</option>
                            <option value="2">PDF</option>
                        </select>
                        <button class = 'btn btn-info addproviderbtn' style = "margin-left:10px">Add New</button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                <table class="table table-striped table-no-bordered table-hover" id = "providertable">
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
                        @foreach($providers as $providers_array)
                        <tr id = "{{$providers_array->id}}">
                            <td>{{date("D, M j Y",strtotime($providers_array->created_at))}}</td>
                            <td>{{$providers_array->fname." ".$providers_array->lname}}</td>
                            <td>{{$providers_array->email}}</td>
                            <td>{{$providers_array->phone}}</td>
                            <td>
                                <?php if($providers_array->status == 1): ?>
                                    <span class = 'provider_active'>Active</span>
                                <?php elseif($providers_array->status == 0): ?>
                                    <span class = 'provider_deactive'>Inactive</span>
                                <?php endif ?>
                            </td>
                            <td>
                                <?php if($providers_array->allowed == 1): ?>
                                    <span class = 'provider_active'>Approved</span>
                                <?php elseif($providers_array->allowed == 0): ?>
                                    <span class = 'provider_deactive'>Not approved</span>
                                <?php endif ?>
                            </td>
                            <td>
                                <?php if($providers_array->allowed == 1): ?>
                                <span class = 'provider_service'><i class = 'fa fa-suitcase'></i></span>&nbsp;<span class = 'provider_ban'><i class = 'fa fa-ban'></i></span>&nbsp;<span class = 'provider_view'><i class = 'fa fa-eye'></i></span>&nbsp;<span class = 'resetpwd'><i class = 'fa fa-key'></i></span>&nbsp;<span class = 'provider_delete'><i class = 'fa fa-trash'></i></span>
                                <?php else: ?>
                                <span class = 'provider_service'><i class = 'fa fa-suitcase'></i></span>&nbsp;<span class = 'provider_unban'><i class = 'fa fa-check'></i></span>&nbsp;<span class = 'provider_view'><i class = 'fa fa-eye'></i></span>&nbsp;<span class = 'resetpwd'><i class = 'fa fa-key'></i></span>&nbsp;<span class = 'provider_delete'><i class = 'fa fa-trash'></i></span>
                                <?php endif ?>
                            </td>
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
<div class="modal fade" id="provider_detail_modal">
    <div class="modal-dialog">
            <div class="modal-content" style = "margin-top:100px">
            <div class="card-avatar">
                <a href="javascript:;">
                    <img class="img chosenproviderimg" src="">
                </a>
            </div>
            <!-- Modal Header -->
            <div class="modal-header">
                    <h4 class="modal-title providernamefordetail" style="font-weight:500;"></h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            
            <!-- Modal body -->
            <div class="modal-body">
                <div class = "row">
                    <div class = "col-md-12">
                        <div class = "row align-items-center ">
                            <div class = "col-md-6">
                                <div class = 'providertypeview'></div>
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
                <div class = "row mt-3">
                    <div class = "col-md-12">
                        <div class = "row align-items-center">
                            <div class = "col-md-6">
                                <div class = 'providerserviceview'></div>
                            </div>
                        </div>
                        <div class = "row">
                            <div class = "col-md-12">
                                <h6>Language : <br><span class = "chosenlanguage"></span></h6>
                                <h6>Expertise & Specializations : <br><span class = "chosenexspec"></span></h6>
                                <h6>Licenses : <br><span class = "chosenlicense"></span></h6>
                                <h6>Provided activities : <br><span class = "chosenactivities"></span></h6>
                            </div>
                            <div class = "col-md-12">  
                                <div class = "row">
                                    <div class = "col-md-6">
                                        <h6 class = "text-left">Service Schedual</h6>
                                        <div class = "sschedule_view"></div>
                                    </div>
                                    <div class = "col-md-6">
                                        <h6 class = "text-left">Interview Schedual</h6>
                                        <div class = "ischedule_view"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class = "row mt-3">
                    <div class = "col-md-12">
                        <div class = "row align-items-center ">
                            <div class = "col-md-6">
                                <div class = 'backgroundcheckview'>Background Check</div>
                            </div>
                            <div class = "col-md-6 text-right">
                                <button type="button" class="btn btn-success btn-link btn-sm">Download</button>
                            </div>
                        </div>
                        <div class = "row">
                            <div class = "col-md-12">
                                
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
<!-- The Modal -->
<div class="modal fade" id="provider_service_modal">
    <div class="modal-dialog">
            <div class="modal-content" style = "margin-top:100px">
                <!-- Modal Header -->
                <div class="modal-header">
                        <h4 class="modal-title" style="font-weight:500;">Job Details</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                
                <!-- Modal body -->
                <div class="modal-body">
                    <div class = "row jobdetail">
                    </div>
                </div>
                <!-- Modal footer -->
                <div class="modal-footer">
                        <button type="button" class="btn btn-primary btn-link viewalljobdetail" data-dismiss="modal">View All</button>
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
<!-- The Modal -->
<div class="modal fade" id="provider_add_modal">
    <div class="modal-dialog">
        <div class="modal-content">
        
            <!-- Modal Header -->
            <div class="modal-header">
                    <h4 class="modal-title" style="font-weight:500;">New Provider</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
        
            <!-- Modal body -->
            <div class="modal-body">
                <form id = 'provideraddform' action="{{url('admin/provideraddForm')}}" method="POST">
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
                    <div class = 'row'>
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
                    <div class = 'row'>
                        <div class = 'col-md-6'>
                            <div class="form-group">
                                <label for="provider_status">What services do you provide?</label>
                                <select name = 'service' id = 'service' class="form-control selectpicker">
                                    <option value="1">Caregiving</option>
                                    <option value="2">Nursing</option>
                                    <option value="3">Therapy</option>
                                </select>
                            </div>
                        </div>
                        <div class = 'col-md-6'>
                            <div class="form-group">
                                <label for="provider_status">What type do you prefer?</label>
                                <select name = 'hiretype' id = 'hiretype' class="form-control selectpicker">
                                    <option value="1">Flexhealth</option>
                                    <option value="2">Direct Hire</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class = 'row'>
                        <div class = 'col-md-12'>
                            <div class="form-group">
                                <label for="provider_status">Service Activities</label>
                                <select name = 'service_act' id = 'service_act' class="form-control selectpicker" multiple>
                                    @foreach($service as $service_array)
                                    <option value="<?php echo $service_array['id']; ?>"><?php echo $service_array['name']; ?></option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class = 'row'>
                        <div class = 'col-md-12'>
                            <div class="form-group">
                                <label for="provider_status">Languages</label>
                                <select name = 'language' id = 'language' class="form-control selectpicker" multiple>
                                    @foreach($language as $language_array)
                                    <option value="<?php echo $language_array['id']; ?>"><?php echo $language_array['name']; ?></option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class = 'row'>
                        <div class = 'col-md-12'>
                            <div class="form-group">
                                <label for="provider_status">Expertise & Specializations</label>
                                <select name = 'exspec' id = 'exspec' class="form-control selectpicker" multiple>
                                    @foreach($exspec as $exspec_array)
                                    <option value="<?php echo $exspec_array['id']; ?>"><?php echo $exspec_array['name']; ?></option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class = 'row'>
                        <div class = 'col-md-12'>
                            <div class="form-group">
                                <label for="provider_status">Licenses</label>
                                <select name = 'license' id = 'license' class="form-control selectpicker" multiple>
                                    @foreach($license as $license_array)
                                    <option value="<?php echo $license_array['id']; ?>"><?php echo $license_array['name']; ?></option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <input type = "hidden" id = "dialCode" name = "dialCode" />
                    <input type = "hidden" id = "countryISO" name = "countryISO" />
                    <input type = "hidden" id = "service_act_tmp" name = "service_act_tmp" />
                    <input type = "hidden" id = "license_tmp" name = "license_tmp" />
                    <input type = "hidden" id = "language_tmp" name = "language_tmp" />
                    <input type = "hidden" id = "exspec_tmp" name = "exspec_tmp" />
                </form>
            </div>
            <!-- Modal footer -->
            <div class="modal-footer">
                    <button type="button" class="btn btn-primary btn-link provideraddbtn">Done</button>
                    <button type="button" class="btn btn-danger btn-link" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<script>
    var input = document.querySelector("#phone");
    var iti = window.intlTelInput(input, {
        utilsScript: "{{asset('adminassets/js/utils.js')}}",
    });
    md.initFormExtendedDatetimepickers();
    function Makeweek(value){
        if(value == 0)
            return "Mon";
        else if(value == 1)
            return "Tue";
        else if(value == 2)
            return "Wed";
        else if(value == 3)
            return "Thu";
        else if(value == 4)
            return "Fri";
        else if(value == 5)
            return "Sat";
        else if(value == 6)
            return "Sun";
    }
    function MakeTime(value){
        value = parseInt(value) + 1;
        if(parseInt(value) == 12)
            return "12 PM";
        if(parseInt(value) == 24)
            return "12 AM";
        if(parseInt(value) > 12)
            return (parseInt(value) - 12)+" PM";
        return parseInt(value)+" AM";
    }
    $(document).ready(function() {
        
        $('.provider_delete').click(function(){
            select_provider = $(this).parent().parent();
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
                            url:"{{ url('/admin/deleteprovider') }}",
                            data:{'_token':'<?php echo csrf_token() ?>',id:select_provider.attr('id')},
                            success:function(data){
                                if(data['success'] == 'success'){
                                    Swal.fire({
                                        text: "Delete successfully",
                                        type: 'success',
                                        confirmButtonColor: '#3085d6',
                                        confirmButtonText: 'Done'
                                    }).then((result) => {
                                        select_provider.remove();
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
        $(document).on('click','.provider_ban',function(){
            select_provider = $(this).parent().parent();
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
                            url:"{{ url('/admin/banprovider') }}",
                            data:{'_token':'<?php echo csrf_token() ?>',id:select_provider.attr('id')},
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
                        $(this).parent().parent().children().eq(5).children().eq(0).removeClass('provider_active');
                        $(this).parent().parent().children().eq(5).children().eq(0).addClass('provider_deactive');
                        $(this).parent().parent().children().eq(5).children().eq(0).html('Not approved');
                        $(this).removeClass("provider_ban");
                        $(this).addClass("provider_unban");
                        $(this).children().eq(0).removeClass('fa-ban');
                        $(this).children().eq(0).addClass('fa-check');
                    }
            });
        });
        $(document).on('click','.provider_unban',function(){
            select_provider = $(this).parent().parent();
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
                            url:"{{ url('/admin/unbanprovider') }}",
                            data:{'_token':'<?php echo csrf_token() ?>',id:select_provider.attr('id')},
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
                        $(this).parent().parent().children().eq(5).children().eq(0).removeClass('provider_deactive');
                        $(this).parent().parent().children().eq(5).children().eq(0).addClass('provider_active');
                        $(this).parent().parent().children().eq(5).children().eq(0).html('Approved');
                        $(this).removeClass("provider_unban");
                        $(this).addClass("provider_ban");
                        $(this).children().eq(0).removeClass('fa-check');
                        $(this).children().eq(0).addClass('fa-ban');
                    }
            });
        });
        $('#providertable').DataTable({
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
        $(".provider_view").click(function(){
            select_provider = $(this).parent().parent();
            $(".providernamefordetail").html($(this).parent().parent().children().eq(1).html());
            $.ajax({
                type:'POST',
                url:"{{ url('/admin/chosenprovider') }}",
                data:{'_token':'<?php echo csrf_token() ?>',id:select_provider.attr('id')},
                dataType:"json",
                success:function(data){
                    if(data[0]['avatar'] != null && data[0]['avatar'] != "")
                        $(".chosenproviderimg").attr('src',"https://operations-flexhealth-me.s3.us-east-1.amazonaws.com/"+data[0]['avatar']);
                    else
                        $(".chosenproviderimg").attr('src',$(".base_url").val()+"adminassets/img/avatar.png");
                    if(data[0]['hiretype'] == 1)
                        $(".providertypeview").html("Flexhealth");
                    else
                        $(".providertypeview").html("Direct Hire");
                    $(".chosenfullname").html(data[0]['fname']+" "+data[0]['lname']);
                    $(".chosenemail").html(data[0]['email']);
                    $(".chosenphone").html(data[0]['phone']);
                    var date = new Date(data[0]['dob']);
                    $(".chosendob").html(((date.getMonth() > 8) ? (date.getMonth() + 1) : ('0' + (date.getMonth() + 1))) + '/' + ((date.getDate() > 9) ? date.getDate() : ('0' + date.getDate())) + '/' + date.getFullYear());
                    $(".chosenaddress").html(data[0]['address']);
                    if(data[0]['service'] == 1)
                        $(".providerserviceview").html("Caregiver");
                    else if(data[0]['service'] == 2)
                        $(".providerserviceview").html("Nursing");
                    else
                        $(".providerserviceview").html("Therapy");
                    $(".chosenlanguage").empty();
                    for(var i = 0;i < data[1].length;i++){
                        $(".chosenlanguage").append("<span class = 'item_property'>"+data[1][i]['name']+"</span>");
                    }
                    $(".chosenexspec").empty();
                    for(var i = 0;i < data[2].length;i++){
                        $(".chosenexspec").append("<span class = 'item_property'>"+data[2][i]['name']+"</span>");
                    }
                    $(".chosenactivities").empty();
                    for(var i = 0;i < data[3].length;i++){
                        $(".chosenactivities").append("<span class = 'item_property'>"+data[3][i]['name']+"</span>");
                    }
                    $(".chosenlicense").empty();
                    for(var i = 0;i < data[4].length;i++){
                        $(".chosenlicense").append("<span class = 'item_property'>"+data[4][i]['name']+"&nbsp;<a href = 'https://operations-flexhealth-me.s3.us-east-1.amazonaws.com/licensefile/"+select_provider.attr('id')+"/"+data[4][i]['file']+"' download='"+data[4][i]['file']+"'><i class = 'fa fa-download'></i></a></span>");
                    }
                    $(".sschedule_view").empty();
                    for(var i = 0;i < data[5].length;i++){
                        $(".sschedule_view").append("<div class = 'schedule_item'>"+Makeweek(data[5][i]['week'])+" : "+MakeTime(data[5][i]['start'])+" ~ "+MakeTime(data[5][i]['end'])+"</div>");
                    }
                    if(data[0]['live_in'] == 1)
                        $(".sschedule_view").append("<div class = 'schedule_item'>Live in</div>");
                    $(".ischedule_view").empty();
                    for(var i = 0;i < data[6].length;i++){
                        $(".ischedule_view").append("<div class = 'schedule_item'>"+Makeweek(data[6][i]['week'])+" : "+MakeTime(data[6][i]['start'])+" ~ "+MakeTime(data[6][i]['end'])+"</div>");
                    }
                    $("#provider_detail_modal").modal("show");
                }
            });
        });
        $(".provider_service").click(function(){
            $('.viewalljobdetail').attr('userid',$(this).parent().parent().attr('id'));
            $.ajax({
                type:'POST',
                url:"{{ url('/admin/viewjobdetail') }}",
                data:{'_token':'<?php echo csrf_token() ?>',id:$(this).parent().parent().attr('id')},
                dataType:'json',
                success:function(data){
                    $(".jobdetail").empty();
                    if(data.length == 0){
                        $(".jobdetail").append("<div class='col-md-12 text-center'><h6>There is no job requested</h6></div>");
                    }
                    for(var i = 0;i < data.length; i++){
                        var date = new Date(data[i]['created_at']);
                        var start = new Date(data[i]['start']);
                        var end = new Date(data[i]['end']);
                        if(data[i]['status'] == 1){
                            var jobtype = "Ongoing Job";
                        }
                        else if(data[i]['status'] == 2){
                            var jobtype = "Finished Job";
                        }
                        else if(data[i]['status'] == 3){
                            var jobtype = "Canceled Job";
                        }
                        else{
                            var jobtype = "";
                        }
                        $(".jobdetail").append("<div class = 'col-md-12'><div class = 'row'><div class = 'col-md-6'><div class = 'requestservice'>Job ID: "+data[i]['jobid']+"</div></div><div class = 'col-md-6'><div class = 'requestservicedate text-right'><h6>"+((date.getMonth() > 8) ? (date.getMonth() + 1) : ('0' + (date.getMonth() + 1))) + '/' + ((date.getDate() > 9) ? date.getDate() : ('0' + date.getDate())) + '/' + date.getFullYear()+"</h6></div></div></div><div class = 'row'><div class = 'col-md-6'><h6>"+((start.getMonth() > 8) ? (start.getMonth() + 1) : ('0' + (start.getMonth() + 1))) + '/' + ((start.getDate() > 9) ? start.getDate() : ('0' + start.getDate())) + '/' + start.getFullYear()+" ~ "+((end.getMonth() > 8) ? (end.getMonth() + 1) : ('0' + (end.getMonth() + 1))) + '/' + ((end.getDate() > 9) ? end.getDate() : ('0' + end.getDate())) + '/' + end.getFullYear()+"</h6></div><div class = 'col-md-6'><h6>"+(data[i]['starttime'] == "24"?"Live in":MakeTime(data[i]['starttime'])+" ~ "+MakeTime(data[i]['endtime']))+"</h6></div><div class = 'col-md-6'><h6 class = 'item_property'>Amount: $"+data[i]['amount']+"</h6></div><div class = 'col-md-6'><h6 class = 'item_property'>"+jobtype+"</h6></div></div></div>");
                        
                    }
                    $("#provider_service_modal").modal('show');

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
        $("#service").change(function(){
            $.ajax({
                type:'POST',
                url:"{{ url('/admin/getchosenactivities') }}",
                data:{'_token':'<?php echo csrf_token() ?>',value:$(this).val()},
                dataType:"json",
                success:function(data){
                    $("#service_act").empty();
                    for(var i = 0;i < data.length;i++){
                        $("#service_act").append("<option value='"+data[i]['id']+"'>"+data[i]['name']+"</option>");
                    }
                    $("#service_act").selectpicker('refresh');
                }
            });
        });
        $(".addproviderbtn").click(function(){
            $("#provider_add_modal").modal('show');
        });
        $(".provideraddbtn").click(function(){
            $("#service_act_tmp").val(JSON.stringify($("#service_act").val()));
            $("#license_tmp").val(JSON.stringify($("#license").val()));
            $("#language_tmp").val(JSON.stringify($("#language").val()));
            $("#exspec_tmp").val(JSON.stringify($("#exspec").val()));
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
                    $("#provideraddform").submit();
                }
            });
        });
        $(".providerpanel").click(function(){
            $('body').append("<form id = 'chosen_type_list' action='{{url('admin/chosenserviceProviders')}}' method='post'>"+'{{ csrf_field() }}'+"<input type = 'hidden' name = 'chosen_type' value = '"+$(this).attr("servicetype")+"' /></form>");
            $('#chosen_type_list').submit();
        });
        $(".downloadtype").change(function(){
            if($(this).val() == 1){
                $('body').append("<form id = 'expertCSV' action='{{ url('/admin/expertCSV') }}' method='get'>"+'{{ csrf_field() }}'+"</form>");
                $('#expertCSV').submit();
            }
            else if($(this).val() == 2){
                $('body').append("<form id = 'downloadPDF' action='{{ url('/admin/downloadPDF') }}' method='get'>"+'{{ csrf_field() }}'+"</form>");
                $('#downloadPDF').submit();
            }
            $(this).val(0);
            $(this).selectpicker('refresh');
        });
        $(".viewalljobdetail").click(function(){
            $('body').append("<form id = 'viewalljob' action='{{ url('/admin/viewalljob') }}' method='post'>"+'{{ csrf_field() }}'+"<input type = 'hidden' name = 'id' value = '"+$(this).attr('userid')+"' /></form>");
            $('#viewalljob').submit();
        });
    });
</script>