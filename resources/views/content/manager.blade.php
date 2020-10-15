<div class = 'row'>
    <div class="col-md-12">
        <div class="card">
        <div class="card-header card-header-icon card-header-primary no-print">
            <div class="card-icon">
            <i class="material-icons">people_outline</i>
            </div>
            <div class = 'row'>
                <div class = 'col-md-6'><h4 class="card-title ">Manager Information</h4></div>
                <div class = 'col-md-6 text-right'><span class = 'manager_add' ><i class = 'fa fa-plus'></i></span></div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
            <table class="table table-striped table-no-bordered table-hover" id = "managertable">
                <thead class=" text-primary">
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Action</th>
                </thead>
                <tbody>
                    @foreach($managerlist as $managerlist_array)
                    <tr userstr = "{{$managerlist_array->id}}" id = "{{$managerlist_array->id}}">
                        <td>{{$managerlist_array->fname}}</td>
                        <td>{{$managerlist_array->lname}}</td>
                        <td>{{$managerlist_array->email}}</td>
                        <td>
                            <?php if($managerlist_array->roles == 1): ?>
                                <span class = 'manager_roles'>Admin</span>
                            <?php elseif($managerlist_array->roles == 2): ?>
                                <span class = 'manager_roles'>Manager</span>
                            <?php elseif($managerlist_array->roles == 0): ?>
                                <span class = 'manager_roles'>Staff</span>
                            <?php endif ?>
                        </td>
                        <td>
                            <?php if($managerlist_array->status == 1): ?>
                                <span class = 'manager_active'>Active</span>
                            <?php elseif($managerlist_array->status == 0): ?>
                                <span class = 'manager_deactive'>Inactive</span>
                            <?php endif ?>
                        </td>
                        <td><span class = 'manager_edit'><i class = 'fa fa-cog'></i></span>&nbsp;<span class = 'manager_sqedit'><i class = 'fa fa-question-circle'></i></span>&nbsp;<span class = 'manager_delete'><i class = 'fa fa-trash'></i></span></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            </div>
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
<!-- The Modal -->
<div class="modal fade" id="manager_add_modal">
    <div class="modal-dialog">
        <div class="modal-content">
        
            <!-- Modal Header -->
            <div class="modal-header">
                    <h4 class="modal-title" style="font-weight:500;">New Manager</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
        
            <!-- Modal body -->
            <div class="modal-body">
                <form id = 'manageraddform' action="{{url('admin/manageraddForm')}}" method="POST">
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
                        <div class = "col-md-12">   
                            <div class="form-group bmd-form-group">
                                <label class="bmd-label-static">Email</label>
                                <input name = 'email' id = 'email' type="email" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class = 'row mt-2'>
                        <div class = 'col-md-6'>
                            <div class="form-group">
                                <label for="manager_status">Roles</label>
                                <select name = 'manager_roles' id = 'manager_roles' class="form-control selectpicker">
                                    <option value="1">Admin</option>
                                    <option value="2">Manager</option>
                                    <option value="0">Staff</option>
                                </select>
                            </div>
                        </div>
                        <div class = 'col-md-6'>
                            <div class="form-group">
                                <label for="manager_status">Status</label>
                                <select name = 'manager_status' id = 'manager_status' class="form-control selectpicker">
                                    <option value="1">Active</option>
                                    <option value="0">Inactive</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <!-- Modal footer -->
            <div class="modal-footer">
                    <button type="button" class="btn btn-primary btn-link manageraddbtn">Done</button>
                    <button type="button" class="btn btn-danger btn-link" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- The Modal -->
<div class="modal fade" id="manager_edit_modal">
    <div class="modal-dialog">
        <div class="modal-content">
        
            <!-- Modal Header -->
            <div class="modal-header">
                    <h4 class="modal-title" style="font-weight:500;">Set Role and Status</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
        
            <!-- Modal body -->
            <div class="modal-body">
                <form id = 'managereditform' action="{{url('admin/managereditForm')}}" method="POST">
                    {{ csrf_field() }}
                    <input name = 'chosen_managerid' id = 'chosen_managerid' type="hidden" class="form-control">
                    <div class = 'row mt-2'>
                        <div class = 'col-md-6'>
                            <div class="form-group">
                                <label for="manager_status">Roles</label>
                                <select name = 'emanager_roles' id = 'emanager_roles' class="form-control selectpicker">
                                    <option value="1">Admin</option>
                                    <option value="2">Manager</option>
                                    <option value="0">Staff</option>
                                </select>
                            </div>
                        </div>
                        <div class = 'col-md-6'>
                            <div class="form-group">
                                <label for="manager_status">Status</label>
                                <select name = 'emanager_status' id = 'emanager_status' class="form-control selectpicker">
                                    <option value="1">Active</option>
                                    <option value="0">Inactive</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <!-- Modal footer -->
            <div class="modal-footer">
                    <button type="button" class="btn btn-primary btn-link managereditbtn">Done</button>
                    <button type="button" class="btn btn-danger btn-link" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- The Modal -->
<div class="modal fade" id="manager_sqedit_modal">
    <div class="modal-dialog">
        <div class="modal-content">
        
            <!-- Modal Header -->
            <div class="modal-header">
                    <h4 class="modal-title" style="font-weight:500;">Set Security Question</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
        
            <!-- Modal body -->
            <div class="modal-body">
                    <input name = 'chosen_manageridforsq' id = 'chosen_manageridforsq' type="hidden" class="form-control">
                    <div class = 'row mt-2'>
                        <div class = 'col-md-12'>
                            <div class="form-group">
                                <label for="manager_status">Question</label>
                                <select name = 'questionid' id = 'questionid' class="form-control selectpicker">
                                @foreach($questionlist as $questionlist_array)
                                    <option value = "{{$questionlist_array->id}}">{{$questionlist_array->question}}</option>
                                @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class = "row mt-4">
                        <div class = 'col-md-12'>
                            <div class="form-group bmd-form-group">
                                <label class="bmd-label-static">Answer</label>
                                <input name = 'answer' id = 'answer' type="text" class="form-control">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <!-- Modal footer -->
            <div class="modal-footer">
                    <button type="button" class="btn btn-primary btn-link managersqeditbtn">Done</button>
                    <button type="button" class="btn btn-danger btn-link" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        $('.manager_delete').click(function(){
            select_manager = $(this).parent().parent();
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
                            url:"{{ url('/admin/deletemanager') }}",
                            data:{'_token':'<?php echo csrf_token() ?>',id:select_manager.attr('id')},
                            success:function(data){
                                if(data['success'] == 'success'){
                                    Swal.fire({
                                        text: "Delete successfully",
                                        type: 'success',
                                        confirmButtonColor: '#3085d6',
                                        confirmButtonText: 'Done'
                                    }).then((result) => {
                                        select_manager.remove();
                                    });
                                }
                                else{
                                    Swal.fire({
                                        text: "Delete failed",
                                        type: 'danger',
                                        confirmButtonColor: '#3085d6',
                                        confirmButtonText: 'Done'
                                    });
                                }
                            }
                        });
                    }
            });
        });
        $('.manager_edit').click(function(){
            $('#chosen_managerid').val($(this).parent().parent().attr('id'));
            $.ajax({
                type:'POST',
                url:"{{ url('/admin/chosenmanager') }}",
                data:{'_token':'<?php echo csrf_token() ?>',id:$(this).parent().parent().attr('id')},
                dataType:"json",
                success:function(data){
                    $('#emanager_status').val(data[0]['status']);
                    $('#emanager_status').selectpicker('refresh');
                    $('#emanager_roles').val(data[0]['roles']);
                    $('#emanager_roles').selectpicker('refresh');
                    $('#manager_edit_modal').modal('show');
                    
                }
            });	
        });
        $(".manager_sqedit").click(function(){
            $('#chosen_manageridforsq').val($(this).parent().parent().attr('userstr'));
            $('#manager_sqedit_modal').modal('show');
        });
        $('.managereditbtn').click(function(){
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
                        $('#managereditform').submit();
                    }
                });
        });
        $(".managersqeditbtn").click(function(){
            $.ajax({
                type:'POST',
                url:"{{ url('/admin/addsqanswer') }}",
                data:{'_token':'<?php echo csrf_token() ?>',id:$("#chosen_manageridforsq").val(),questionid:$("#questionid").val(),answer:$("#answer").val()},
                success:function(data){
                    if(data['success'] == 'success'){
                        Swal.fire({
                            text: "Your work is done successfully",
                            type: 'success',
                            confirmButtonColor: '#3085d6',
                            confirmButtonText: 'Done'
                        }).then((result) => {

                        });
                    }
                    else{
                        Swal.fire({
                            text: "Your work is failed",
                            type: 'danger',
                            confirmButtonColor: '#3085d6',
                            confirmButtonText: 'Done'
                        });
                    }
                }
            });
        });
        $(".manager_add").click(function(){
            $('#manager_add_modal').modal('show');
        });
        $(".manageraddbtn").click(function(){
            if($("#fname").val() == ""){
                $.notify({
                    icon: "add_alert",
                    message: "Please Enter Your First Name."

                    }, {
                    type: 'info',
                    timer: 1000,
                    placement: {
                        from: 'top',
                        align: 'center'
                    }
                });
            }
            else if($("#lname").val() == ""){
                $.notify({
                    icon: "add_alert",
                    message: "Please Enter Your Last Name."

                    }, {
                    type: 'info',
                    timer: 1000,
                    placement: {
                        from: 'top',
                        align: 'center'
                    }
                });
            }
            else if($("#email").val() == ""){
                $.notify({
                    icon: "add_alert",
                    message: "Please Enter Your Email."

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
                        $('#manageraddform').submit();
                    }
                });
            }
        });
        $('#managertable').DataTable({
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
    });
</script>