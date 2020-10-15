<div class = 'row'>
    <div class="col-md-12">
        <div class="card">
        <div class="card-header card-header-icon card-header-primary no-print">
            <div class="card-icon">
            <i class="material-icons">fact_check</i>
            </div>
            <div class = 'row'>
                <div class = 'col-md-6'><h4 class="card-title ">License Information</h4></div>
                <div class = 'col-md-6 text-right'><span class = 'license_add' ><i class = 'fa fa-plus'></i></span></div>

            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
            <table class="table table-striped table-no-bordered table-hover" id = "licensetable">
                <thead class=" text-primary">
                    <th>License</th>
                    <th>Status</th>
                    <th>Action</th>
                </thead>
                <tbody>
                    @foreach($licenselist as $licenselist_array)
                    <tr id = "{{$licenselist_array->id}}">
                        <td>{{$licenselist_array->name}}</td>
                        <td>
                            <?php if($licenselist_array->status == 1): ?>
                                <span class = 'license_active'>Active</span>
                            <?php elseif($licenselist_array->status == 0): ?>
                                <span class = 'license_deactive'>Inactive</span>
                            <?php endif ?>
                        </td>
                        <td><span class = 'license_edit'><i class = 'fa fa-cog'></i></span>&nbsp;<span class = 'license_delete'><i class = 'fa fa-trash'></i></span></td>
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
<div class="modal fade" id="license_add_modal">
    <div class="modal-dialog">
            <div class="modal-content">
            
            <!-- Modal Header -->
            <div class="modal-header">
                    <h4 class="modal-title" style="font-weight:500;">Add License</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            
            <!-- Modal body -->
            <div class="modal-body">
                <form id = 'licenseaddform' action="{{url('admin/licenseaddForm')}}" method="POST" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class = 'row'>
                        <div class = 'col-md-12'>
                            <div class="form-group">
                                <label for="license">License</label>
                                <textarea class="form-control" name = 'license' id="license" rows="5"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class = 'row mt-2'>
                        <div class = "col-md-6">
                            <div class="form-group">
                                <label for="license_status">Choose License Image</label>
                                <div class="custom-file form-group bmd-form-group dg-input">
                                    <input type="file" class="custom-file-input form-control" id="editfile" name="file">
                                    <label class="custom-file-label" for="editfile">Choose file</label>
                                </div>
                            </div>
                        </div>
                        <div class = 'col-md-6'>
                            <div class="form-group">
                                <label for="license_status">Status</label>
                                <select name = 'license_status' id = 'license_status' class="form-control selectpicker">
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
                    <button type="button" class="btn btn-primary btn-link licenseaddbtn" data-dismiss="modal">Done</button>
                    <button type="button" class="btn btn-danger btn-link" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!--- end modal -->
    <!-- The Modal -->
<div class="modal fade" id="license_edit_modal">
    <div class="modal-dialog">
        <div class="modal-content">
        
            <!-- Modal Header -->
            <div class="modal-header">
                    <h4 class="modal-title" style="font-weight:500;">Edit License</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
        
            <!-- Modal body -->
            <div class="modal-body">
                <form id = 'licenseeditform' action="{{url('admin/licenseeditForm')}}" method="POST" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class = 'row'>
                        <div class = 'col-md-12'>
                            <div class="form-group">
                                <label for="license">License</label>
                                <textarea class="form-control" name = 'elicense' id="elicense" rows="5"></textarea>
                            </div>
                        </div>
                    </div>
                    <input name = 'chosen_licenseid' id = 'chosen_licenseid' type="hidden" class="form-control">
                    <div class = 'row mt-2'>
                        <div class = "col-md-6">
                            <div class="form-group">
                                <label for="license_status">Choose License Image</label>
                                <div class="custom-file form-group bmd-form-group dg-input">
                                    <input type="file" class="custom-file-input form-control" id="customFile" name="file">
                                    <label class="custom-file-label" for="customFile">Choose file</label>
                                </div>
                            </div>
                        </div>
                        <div class = 'col-md-6'>
                            <div class="form-group">
                                <label for="license_status">Status</label>
                                <select name = 'elicense_status' id = 'elicense_status' class="form-control selectpicker">
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
                    <button type="button" class="btn btn-primary btn-link licenseeditbtn">Done</button>
                    <button type="button" class="btn btn-danger btn-link" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        $(".custom-file-input").on("change", function() {
			var fileName = $(this).val().split("\\").pop();
			$(this).siblings(".custom-file-label").addClass("selected").html(fileName);
		});
        $('.license_add').click(function(){
            $('#license_add_modal').modal('show');
        });
        $('.licenseaddbtn').click(function(){
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
                                $('#licenseaddform').submit();
                        }
                });
        });
        $('.license_delete').click(function(){
            select_license = $(this).parent().parent();
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
                            url:"{{ url('/admin/deletelicense') }}",
                            data:{'_token':'<?php echo csrf_token() ?>',id:select_license.attr('id')},
                            success:function(data){
                                if(data['success'] == 'success'){
                                    Swal.fire({
                                        text: "Delete successfully",
                                        type: 'success',
                                        confirmButtonColor: '#3085d6',
                                        confirmButtonText: 'Done'
                                    }).then((result) => {
                                        select_license.remove();
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
        $('.license_edit').click(function(){
            $('#chosen_licenseid').val($(this).parent().parent().attr('id'));
            $.ajax({
                type:'POST',
                url:"{{ url('/admin/chosenlicense') }}",
                data:{'_token':'<?php echo csrf_token() ?>',id:$(this).parent().parent().attr('id')},
                dataType:"json",
                success:function(data){
                    $('#elicense').val(data[0]['name']);
                    $('#elicense_status').val(data[0]['status']);
                    $('#elicense_status').selectpicker('refresh');
                    $('#license_edit_modal').modal('show');
                    
                }
            });	
        });
        $('.licenseeditbtn').click(function(){
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
                        $('#licenseeditform').submit();
                    }
                });
        });
        $('#licensetable').DataTable({
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