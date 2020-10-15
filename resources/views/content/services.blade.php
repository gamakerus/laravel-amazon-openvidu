<div class = 'row'>
    <div class="col-md-12">
        <div class="card">
        <div class="card-header card-header-icon card-header-primary no-print">
            <div class="card-icon">
            <i class="material-icons">medical_services</i>
            </div>
            <div class = 'row'>
                <div class = 'col-md-6'><h4 class="card-title ">Service Information</h4></div>
                <div class = 'col-md-6 text-right'><span class = 'service_add' ><i class = 'fa fa-plus'></i></span></div>

            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
            <table class="table table-striped table-no-bordered table-hover" id = "servicetable">
                <thead class=" text-primary">
                    <th>Type</th>
                    <th>Service</th>
                    <th>Status</th>
                    <th>Action</th>
                </thead>
                <tbody>
                    @foreach($servicelist as $servicelist_array)
                    <tr id = "{{$servicelist_array->id}}">
                        <td>
                            <?php if($servicelist_array->type == 1): ?>
                            Caregiving
                            <?php elseif($servicelist_array->type == 2): ?>
                            Nursing
                            <?php else: ?>
                            Therapy
                            <?php endif ?>
                        </td>
                        <td>{{$servicelist_array->name}}</td>
                        <td>
                            <?php if($servicelist_array->status == 1): ?>
                                <span class = 'service_active'>Active</span>
                            <?php elseif($servicelist_array->status == 0): ?>
                                <span class = 'service_deactive'>Inactive</span>
                            <?php endif ?>
                        </td>
                        <td><span class = 'service_edit'><i class = 'fa fa-cog'></i></span>&nbsp;<span class = 'service_delete'><i class = 'fa fa-trash'></i></span></td>
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
<div class="modal fade" id="service_add_modal">
    <div class="modal-dialog">
            <div class="modal-content">
            
            <!-- Modal Header -->
            <div class="modal-header">
                    <h4 class="modal-title" style="font-weight:500;">Add Service</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            
            <!-- Modal body -->
            <div class="modal-body">
                <form id = 'serviceaddform' action="{{url('admin/serviceaddForm')}}" method="POST">
                    {{ csrf_field() }}
                    <div class = 'row'>
                        <div class = 'col-md-12'>
                            <div class="form-group">
                                <label for="service">Service Type</label>
                                <select name = 'service_type' id = 'service_type' class="form-control selectpicker">
                                    <option value="1">Caregiving</option>
                                    <option value="2">Nursing</option>
                                    <option value="3">Therapy</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class = 'row mt-4'>
                        <div class = 'col-md-12'>
                            <div class="form-group">
                                <label for="service">Serivce</label>
                                <textarea class="form-control" name = 'service' id="service" rows="3"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class = 'row mt-2'>
                        <div class = 'col-md-12'>
                            <div class="form-group">
                                <label for="service_status">Status</label>
                                <select name = 'service_status' id = 'service_status' class="form-control selectpicker">
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
                    <button type="button" class="btn btn-primary btn-link serviceaddbtn" data-dismiss="modal">Done</button>
                    <button type="button" class="btn btn-danger btn-link" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!--- end modal -->
    <!-- The Modal -->
<div class="modal fade" id="service_edit_modal">
    <div class="modal-dialog">
        <div class="modal-content">
        
            <!-- Modal Header -->
            <div class="modal-header">
                    <h4 class="modal-title" style="font-weight:500;">Edit Question</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
        
            <!-- Modal body -->
            <div class="modal-body">
                <form id = 'serviceeditform' action="{{url('admin/serviceeditForm')}}" method="POST">
                    {{ csrf_field() }}
                    <div class = 'row'>
                        <div class = 'col-md-12'>
                            <div class="form-group">
                                <label for="service">Service Type</label>
                                <select name = 'eservice_type' id = 'eservice_type' class="form-control selectpicker">
                                    <option value="1">Caregiving</option>
                                    <option value="2">Nursing</option>
                                    <option value="3">Therapy</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class = 'row mt-4'>
                        <div class = 'col-md-12'>
                            <div class="form-group">
                                <label for="service">Serivce</label>
                                <textarea class="form-control" name = 'eservice' id="eservice" rows="3"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class = 'row mt-2'>
                        <div class = 'col-md-12'>
                            <div class="form-group">
                                <label for="service_status">Status</label>
                                <select name = 'eservice_status' id = 'eservice_status' class="form-control selectpicker">
                                    <option value="1">Active</option>
                                    <option value="0">Inactive</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <input name = 'chosen_serviceid' id = 'chosen_serviceid' type="hidden" class="form-control">

                </form>
            </div>
            <!-- Modal footer -->
            <div class="modal-footer">
                    <button type="button" class="btn btn-primary btn-link serviceeditbtn">Done</button>
                    <button type="button" class="btn btn-danger btn-link" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        $('.service_add').click(function(){
            $('#service_add_modal').modal('show');
        });
        $('.serviceaddbtn').click(function(){
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
                                $('#serviceaddform').submit();
                        }
                });
        });
        $('.service_delete').click(function(){
            select_service = $(this).parent().parent();
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
                            url:"{{ url('/admin/deleteservice') }}",
                            data:{'_token':'<?php echo csrf_token() ?>',id:select_service.attr('id')},
                            success:function(data){
                                if(data['success'] == 'success'){
                                    Swal.fire({
                                        text: "Delete successfully",
                                        type: 'success',
                                        confirmButtonColor: '#3085d6',
                                        confirmButtonText: 'Done'
                                    }).then((result) => {
                                        select_service.remove();
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
        $('.service_edit').click(function(){
            $('#chosen_serviceid').val($(this).parent().parent().attr('id'));
            $.ajax({
                type:'POST',
                url:"{{ url('/admin/chosenservice') }}",
                data:{'_token':'<?php echo csrf_token() ?>',id:$(this).parent().parent().attr('id')},
                dataType:"json",
                success:function(data){
                    $('#eservice').val(data[0]['name']);
                    $('#eservice_status').val(data[0]['status']);
                    $('#eservice_status').selectpicker('refresh');
                    $('#eservice_type').val(data[0]['type']);
                    $('#eservice_type').selectpicker('refresh');
                    $('#service_edit_modal').modal('show');
                    
                }
            });	
        });
        $('.serviceeditbtn').click(function(){
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
                        $('#serviceeditform').submit();
                    }
                });
        });
        $('#servicetable').DataTable({
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