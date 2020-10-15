<div class = 'row'>
    <div class="col-md-6">
        <div class="card">
        <div class="card-header card-header-icon card-header-primary no-print">
            <div class="card-icon">
            <i class="material-icons">more_time</i>
            </div>
            <div class = 'row'>
                <div class = 'col-md-6'><h4 class="card-title ">Service Duration</h4></div>
                <div class = 'col-md-6 text-right'><span class = 'sduration_add' ><i class = 'fa fa-plus'></i></span></div>

            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
            <table class="table table-striped table-no-bordered table-hover" id = "sdurationtable">
                <thead class=" text-primary">
                    <th>Duration</th>
                    <th>Status</th>
                    <th>Action</th>
                </thead>
                <tbody>
                    @foreach($sdurationlist as $sdurationlist_array)
                    <tr id = "{{$sdurationlist_array->id}}">
                        <td>{{$sdurationlist_array->name}}</td>
                        <td>
                            <?php if($sdurationlist_array->status == 1): ?>
                                <span class = 'sduration_active'>Active</span>
                            <?php elseif($sdurationlist_array->status == 0): ?>
                                <span class = 'sduration_deactive'>Inactive</span>
                            <?php endif ?>
                        </td>
                        <td><span class = 'sduration_edit'><i class = 'fa fa-cog'></i></span>&nbsp;<span class = 'sduration_delete'><i class = 'fa fa-trash'></i></span></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            </div>
        </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
        <div class="card-header card-header-icon card-header-primary no-print">
            <div class="card-icon">
            <i class="material-icons">more_time</i>
            </div>
            <div class = 'row'>
                <div class = 'col-md-6'><h4 class="card-title ">Days Per Week</h4></div>
                <div class = 'col-md-6 text-right'><span class = 'daytoweek_add' ><i class = 'fa fa-plus'></i></span></div>

            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
            <table class="table table-striped table-no-bordered table-hover" id = "daytoweektable">
                <thead class=" text-primary">
                    <th>Days</th>
                    <th>Status</th>
                    <th>Action</th>
                </thead>
                <tbody>
                    @foreach($daytoweeklist as $daytoweeklist_array)
                    <tr id = "{{$daytoweeklist_array->id}}">
                        <td>{{$daytoweeklist_array->name}}</td>
                        <td>
                            <?php if($daytoweeklist_array->status == 1): ?>
                                <span class = 'daytoweek_active'>Active</span>
                            <?php elseif($daytoweeklist_array->status == 0): ?>
                                <span class = 'daytoweek_deactive'>Inactive</span>
                            <?php endif ?>
                        </td>
                        <td><span class = 'daytoweek_edit'><i class = 'fa fa-cog'></i></span>&nbsp;<span class = 'daytoweek_delete'><i class = 'fa fa-trash'></i></span></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            </div>
        </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
        <div class="card-header card-header-icon card-header-primary no-print">
            <div class="card-icon">
            <i class="material-icons">more_time</i>
            </div>
            <div class = 'row'>
                <div class = 'col-md-6'><h4 class="card-title ">Times Per Day</h4></div>
                <div class = 'col-md-6 text-right'><span class = 'timetoday_add' ><i class = 'fa fa-plus'></i></span></div>

            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
            <table class="table table-striped table-no-bordered table-hover" id = "timetodaytable">
                <thead class=" text-primary">
                    <th>Times</th>
                    <th>Status</th>
                    <th>Action</th>
                </thead>
                <tbody>
                    @foreach($timetodaylist as $timetodaylist_array)
                    <tr id = "{{$timetodaylist_array->id}}">
                        <td>{{$timetodaylist_array->name}}</td>
                        <td>
                            <?php if($timetodaylist_array->status == 1): ?>
                                <span class = 'timetoday_active'>Active</span>
                            <?php elseif($timetodaylist_array->status == 0): ?>
                                <span class = 'timetoday_deactive'>Inactive</span>
                            <?php endif ?>
                        </td>
                        <td><span class = 'timetoday_edit'><i class = 'fa fa-cog'></i></span>&nbsp;<span class = 'timetoday_delete'><i class = 'fa fa-trash'></i></span></td>
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
<div class="modal fade" id="sduration_add_modal">
    <div class="modal-dialog">
            <div class="modal-content">
            
            <!-- Modal Header -->
            <div class="modal-header">
                    <h4 class="modal-title" style="font-weight:500;">Add Duration</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            
            <!-- Modal body -->
            <div class="modal-body">
                <form id = 'sdurationaddform' action="{{url('admin/sdurationaddForm')}}" method="POST">
                    {{ csrf_field() }}
                    <div class = 'row'>
                        <div class = 'col-md-12'>
                            <div class="form-group">
                                <label for="sduration">Duration</label>
                                <textarea class="form-control" name = 'sduration' id="sduration" rows="5"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class = 'row mt-2'>
                        <div class = 'col-md-12'>
                            <div class="form-group">
                                <label for="sduration_status">Status</label>
                                <select name = 'sduration_status' id = 'sduration_status' class="form-control selectpicker">
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
                    <button type="button" class="btn btn-primary btn-link sdurationaddbtn" data-dismiss="modal">Done</button>
                    <button type="button" class="btn btn-danger btn-link" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!--- end modal -->
    <!-- The Modal -->
<div class="modal fade" id="sduration_edit_modal">
    <div class="modal-dialog">
        <div class="modal-content">
        
            <!-- Modal Header -->
            <div class="modal-header">
                    <h4 class="modal-title" style="font-weight:500;">Edit Duration</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
        
            <!-- Modal body -->
            <div class="modal-body">
                <form id = 'sdurationeditform' action="{{url('admin/sdurationeditForm')}}" method="POST">
                    {{ csrf_field() }}
                    <div class = 'row'>
                        <div class = 'col-md-12'>
                            <div class="form-group">
                                <label for="sduration">Duration</label>
                                <textarea class="form-control" name = 'esduration' id="esduration" rows="5"></textarea>
                            </div>
                        </div>
                    </div>
                    <input name = 'chosen_sdurationid' id = 'chosen_sdurationid' type="hidden" class="form-control">
                    <div class = 'row mt-2'>
                        <div class = 'col-md-12'>
                            <div class="form-group">
                                <label for="sduration_status">Status</label>
                                <select name = 'esduration_status' id = 'esduration_status' class="form-control selectpicker">
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
                    <button type="button" class="btn btn-primary btn-link sdurationeditbtn">Done</button>
                    <button type="button" class="btn btn-danger btn-link" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>


<!-- The Modal -->
<div class="modal fade" id="sduration_add_modal">
    <div class="modal-dialog">
            <div class="modal-content">
            
            <!-- Modal Header -->
            <div class="modal-header">
                    <h4 class="modal-title" style="font-weight:500;">Add Duration</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            
            <!-- Modal body -->
            <div class="modal-body">
                <form id = 'sdurationaddform' action="{{url('admin/sdurationaddForm')}}" method="POST">
                    {{ csrf_field() }}
                    <div class = 'row'>
                        <div class = 'col-md-12'>
                            <div class="form-group">
                                <label for="sduration">Duration</label>
                                <textarea class="form-control" name = 'sduration' id="sduration" rows="5"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class = 'row mt-2'>
                        <div class = 'col-md-12'>
                            <div class="form-group">
                                <label for="sduration_status">Status</label>
                                <select name = 'sduration_status' id = 'sduration_status' class="form-control selectpicker">
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
                    <button type="button" class="btn btn-primary btn-link sdurationaddbtn" data-dismiss="modal">Done</button>
                    <button type="button" class="btn btn-danger btn-link" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!--- end modal -->
    <!-- The Modal -->
<div class="modal fade" id="sduration_edit_modal">
    <div class="modal-dialog">
        <div class="modal-content">
        
            <!-- Modal Header -->
            <div class="modal-header">
                    <h4 class="modal-title" style="font-weight:500;">Edit Duration</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
        
            <!-- Modal body -->
            <div class="modal-body">
                <form id = 'sdurationeditform' action="{{url('admin/sdurationeditForm')}}" method="POST">
                    {{ csrf_field() }}
                    <div class = 'row'>
                        <div class = 'col-md-12'>
                            <div class="form-group">
                                <label for="sduration">Duration</label>
                                <textarea class="form-control" name = 'esduration' id="esduration" rows="5"></textarea>
                            </div>
                        </div>
                    </div>
                    <input name = 'chosen_sdurationid' id = 'chosen_sdurationid' type="hidden" class="form-control">
                    <div class = 'row mt-2'>
                        <div class = 'col-md-12'>
                            <div class="form-group">
                                <label for="sduration_status">Status</label>
                                <select name = 'esduration_status' id = 'esduration_status' class="form-control selectpicker">
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
                    <button type="button" class="btn btn-primary btn-link sdurationeditbtn">Done</button>
                    <button type="button" class="btn btn-danger btn-link" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>


<!-- The Modal -->
<div class="modal fade" id="daytoweek_add_modal">
    <div class="modal-dialog">
            <div class="modal-content">
            
            <!-- Modal Header -->
            <div class="modal-header">
                    <h4 class="modal-title" style="font-weight:500;">Add New</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            
            <!-- Modal body -->
            <div class="modal-body">
                <form id = 'daytoweekaddform' action="{{url('admin/daytoweekaddForm')}}" method="POST">
                    {{ csrf_field() }}
                    <div class = 'row'>
                        <div class = 'col-md-12'>
                            <div class="form-group">
                                <label for="daytoweek">Days</label>
                                <input class="form-control" name = 'daytoweek' id="daytoweek" />
                            </div>
                        </div>
                    </div>
                    <div class = 'row mt-2'>
                        <div class = 'col-md-12'>
                            <div class="form-group">
                                <label for="daytoweek_status">Status</label>
                                <select name = 'daytoweek_status' id = 'daytoweek_status' class="form-control selectpicker">
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
                    <button type="button" class="btn btn-primary btn-link daytoweekaddbtn" data-dismiss="modal">Done</button>
                    <button type="button" class="btn btn-danger btn-link" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!--- end modal -->
    <!-- The Modal -->
<div class="modal fade" id="daytoweek_edit_modal">
    <div class="modal-dialog">
        <div class="modal-content">
        
            <!-- Modal Header -->
            <div class="modal-header">
                    <h4 class="modal-title" style="font-weight:500;">Edit Duration</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
        
            <!-- Modal body -->
            <div class="modal-body">
                <form id = 'daytoweekeditform' action="{{url('admin/daytoweekeditForm')}}" method="POST">
                    {{ csrf_field() }}
                    <div class = 'row'>
                        <div class = 'col-md-12'>
                            <div class="form-group">
                                <label for="daytoweek">Days</label>
                                <input class="form-control" name = 'edaytoweek' id="edaytoweek" />
                            </div>
                        </div>
                    </div>
                    <input name = 'chosen_daytoweekid' id = 'chosen_daytoweekid' type="hidden" class="form-control">
                    <div class = 'row mt-2'>
                        <div class = 'col-md-12'>
                            <div class="form-group">
                                <label for="daytoweek_status">Status</label>
                                <select name = 'edaytoweek_status' id = 'edaytoweek_status' class="form-control selectpicker">
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
                    <button type="button" class="btn btn-primary btn-link daytoweekeditbtn">Done</button>
                    <button type="button" class="btn btn-danger btn-link" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>



<!-- The Modal -->
<div class="modal fade" id="timetoday_add_modal">
    <div class="modal-dialog">
            <div class="modal-content">
            
            <!-- Modal Header -->
            <div class="modal-header">
                    <h4 class="modal-title" style="font-weight:500;">Add New</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            
            <!-- Modal body -->
            <div class="modal-body">
                <form id = 'timetodayaddform' action="{{url('admin/timetodayaddForm')}}" method="POST">
                    {{ csrf_field() }}
                    <div class = 'row'>
                        <div class = 'col-md-12'>
                            <div class="form-group">
                                <label for="timetoday">Days</label>
                                <input class="form-control" name = 'timetoday' id="timetoday" />
                            </div>
                        </div>
                    </div>
                    <div class = 'row mt-2'>
                        <div class = 'col-md-12'>
                            <div class="form-group">
                                <label for="timetoday_status">Status</label>
                                <select name = 'timetoday_status' id = 'timetoday_status' class="form-control selectpicker">
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
                    <button type="button" class="btn btn-primary btn-link timetodayaddbtn" data-dismiss="modal">Done</button>
                    <button type="button" class="btn btn-danger btn-link" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!--- end modal -->
    <!-- The Modal -->
<div class="modal fade" id="timetoday_edit_modal">
    <div class="modal-dialog">
        <div class="modal-content">
        
            <!-- Modal Header -->
            <div class="modal-header">
                    <h4 class="modal-title" style="font-weight:500;">Edit Item</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
        
            <!-- Modal body -->
            <div class="modal-body">
                <form id = 'timetodayeditform' action="{{url('admin/timetodayeditForm')}}" method="POST">
                    {{ csrf_field() }}
                    <div class = 'row'>
                        <div class = 'col-md-12'>
                            <div class="form-group">
                                <label for="timetoday">Days</label>
                                <input class="form-control" name = 'etimetoday' id="etimetoday" />
                            </div>
                        </div>
                    </div>
                    <input name = 'chosen_timetodayid' id = 'chosen_timetodayid' type="hidden" class="form-control">
                    <div class = 'row mt-2'>
                        <div class = 'col-md-12'>
                            <div class="form-group">
                                <label for="timetoday_status">Status</label>
                                <select name = 'etimetoday_status' id = 'etimetoday_status' class="form-control selectpicker">
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
                    <button type="button" class="btn btn-primary btn-link timetodayeditbtn">Done</button>
                    <button type="button" class="btn btn-danger btn-link" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        $('.sduration_add').click(function(){
            $('#sduration_add_modal').modal('show');
        });
        $('.sdurationaddbtn').click(function(){
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
                                $('#sdurationaddform').submit();
                        }
                });
        });
        $('.sduration_delete').click(function(){
            select_sduration = $(this).parent().parent();
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
                            url:"{{ url('/admin/deletesduration') }}",
                            data:{'_token':'<?php echo csrf_token() ?>',id:select_sduration.attr('id')},
                            success:function(data){
                                if(data['success'] == 'success'){
                                    Swal.fire({
                                        text: "Delete successfully",
                                        type: 'success',
                                        confirmButtonColor: '#3085d6',
                                        confirmButtonText: 'Done'
                                    }).then((result) => {
                                        select_sduration.remove();
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
        $('.sduration_edit').click(function(){
            $('#chosen_sdurationid').val($(this).parent().parent().attr('id'));
            $.ajax({
                type:'POST',
                url:"{{ url('/admin/chosensduration') }}",
                data:{'_token':'<?php echo csrf_token() ?>',id:$(this).parent().parent().attr('id')},
                dataType:"json",
                success:function(data){
                    $('#esduration').val(data[0]['name']);
                    $('#esduration_status').val(data[0]['status']);
                    $('#esduration_status').selectpicker('refresh');
                    $('#sduration_edit_modal').modal('show');
                    
                }
            });	
        });
        $('.sdurationeditbtn').click(function(){
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
                        $('#sdurationeditform').submit();
                    }
                });
        });


        $('.daytoweek_add').click(function(){
            $('#daytoweek_add_modal').modal('show');
        });
        $('.daytoweekaddbtn').click(function(){
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
                                $('#daytoweekaddform').submit();
                        }
                });
        });
        $('.daytoweek_delete').click(function(){
            select_daytoweek = $(this).parent().parent();
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
                            url:"{{ url('/admin/deletedaytoweek') }}",
                            data:{'_token':'<?php echo csrf_token() ?>',id:select_daytoweek.attr('id')},
                            success:function(data){
                                if(data['success'] == 'success'){
                                    Swal.fire({
                                        text: "Delete successfully",
                                        type: 'success',
                                        confirmButtonColor: '#3085d6',
                                        confirmButtonText: 'Done'
                                    }).then((result) => {
                                        select_daytoweek.remove();
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
        $('.daytoweek_edit').click(function(){
            $('#chosen_daytoweekid').val($(this).parent().parent().attr('id'));
            $.ajax({
                type:'POST',
                url:"{{ url('/admin/chosendaytoweek') }}",
                data:{'_token':'<?php echo csrf_token() ?>',id:$(this).parent().parent().attr('id')},
                dataType:"json",
                success:function(data){
                    $('#edaytoweek').val(data[0]['name']);
                    $('#edaytoweek_status').val(data[0]['status']);
                    $('#edaytoweek_status').selectpicker('refresh');
                    $('#daytoweek_edit_modal').modal('show');
                    
                }
            });	
        });
        $('.daytoweekeditbtn').click(function(){
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
                        $('#daytoweekeditform').submit();
                    }
                });
        });



        $('.timetoday_add').click(function(){
            $('#timetoday_add_modal').modal('show');
        });
        $('.timetodayaddbtn').click(function(){
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
                                $('#timetodayaddform').submit();
                        }
                });
        });
        $('.timetoday_delete').click(function(){
            select_timetoday = $(this).parent().parent();
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
                            url:"{{ url('/admin/deletetimetoday') }}",
                            data:{'_token':'<?php echo csrf_token() ?>',id:select_timetoday.attr('id')},
                            success:function(data){
                                if(data['success'] == 'success'){
                                    Swal.fire({
                                        text: "Delete successfully",
                                        type: 'success',
                                        confirmButtonColor: '#3085d6',
                                        confirmButtonText: 'Done'
                                    }).then((result) => {
                                        select_timetoday.remove();
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
        $('.timetoday_edit').click(function(){
            $('#chosen_timetodayid').val($(this).parent().parent().attr('id'));
            $.ajax({
                type:'POST',
                url:"{{ url('/admin/chosentimetoday') }}",
                data:{'_token':'<?php echo csrf_token() ?>',id:$(this).parent().parent().attr('id')},
                dataType:"json",
                success:function(data){
                    $('#etimetoday').val(data[0]['name']);
                    $('#etimetoday_status').val(data[0]['status']);
                    $('#etimetoday_status').selectpicker('refresh');
                    $('#timetoday_edit_modal').modal('show');
                    
                }
            });	
        });
        $('.timetodayeditbtn').click(function(){
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
                        $('#timetodayeditform').submit();
                    }
                });
        });
        $('#sdurationtable').DataTable({
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
        $('#daytoweektable').DataTable({
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
        $('#timetodaytable').DataTable({
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