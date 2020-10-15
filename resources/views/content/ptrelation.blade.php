<div class = 'row'>
    <div class="col-md-12">
        <div class="card">
        <div class="card-header card-header-icon card-header-primary no-print">
            <div class="card-icon">
            <i class="material-icons">people</i>
            </div>
            <div class = 'row'>
                <div class = 'col-md-6'><h4 class="card-title ">Relation Information</h4></div>
                <div class = 'col-md-6 text-right'><span class = 'ptrelation_add' ><i class = 'fa fa-plus'></i></span></div>

            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
            <table class="table table-striped table-no-bordered table-hover" id = "ptrelationtable">
                <thead class=" text-primary">
                    <th>Relation</th>
                    <th>Status</th>
                    <th>Action</th>
                </thead>
                <tbody>
                    @foreach($ptrelationlist as $ptrelationlist_array)
                    <tr id = "{{$ptrelationlist_array->id}}">
                        <td>{{$ptrelationlist_array->name}}</td>
                        <td>
                            <?php if($ptrelationlist_array->status == 1): ?>
                                <span class = 'ptrelation_active'>Active</span>
                            <?php elseif($ptrelationlist_array->status == 0): ?>
                                <span class = 'ptrelation_deactive'>Inactive</span>
                            <?php endif ?>
                        </td>
                        <td><span class = 'ptrelation_edit'><i class = 'fa fa-cog'></i></span>&nbsp;<span class = 'ptrelation_delete'><i class = 'fa fa-trash'></i></span></td>
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
<div class="modal fade" id="ptrelation_add_modal">
    <div class="modal-dialog">
            <div class="modal-content">
            
            <!-- Modal Header -->
            <div class="modal-header">
                    <h4 class="modal-title" style="font-weight:500;">Add Relation</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            
            <!-- Modal body -->
            <div class="modal-body">
                <form id = 'ptrelationaddform' action="{{url('admin/ptrelationaddForm')}}" method="POST">
                    {{ csrf_field() }}
                    <div class = 'row'>
                        <div class = 'col-md-12'>
                            <div class="form-group">
                                <label for="ptrelation">Relation</label>
                                <input class="form-control" name = 'ptrelation' id="ptrelation" />
                            </div>
                        </div>
                    </div>
                    <div class = 'row mt-2'>
                        <div class = 'col-md-12'>
                            <div class="form-group">
                                <label for="ptrelation_status">Status</label>
                                <select name = 'ptrelation_status' id = 'ptrelation_status' class="form-control selectpicker">
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
                    <button type="button" class="btn btn-primary btn-link ptrelationaddbtn" data-dismiss="modal">Done</button>
                    <button type="button" class="btn btn-danger btn-link" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!--- end modal -->
    <!-- The Modal -->
<div class="modal fade" id="ptrelation_edit_modal">
    <div class="modal-dialog">
        <div class="modal-content">
        
            <!-- Modal Header -->
            <div class="modal-header">
                    <h4 class="modal-title" style="font-weight:500;">Edit Relation</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
        
            <!-- Modal body -->
            <div class="modal-body">
                <form id = 'ptrelationeditform' action="{{url('admin/ptrelationeditForm')}}" method="POST">
                    {{ csrf_field() }}
                    <div class = 'row'>
                        <div class = 'col-md-12'>
                            <div class="form-group">
                                <label for="ptrelation">Relation</label>
                                <input class="form-control" name = 'eptrelation' id="eptrelation" />
                            </div>
                        </div>
                    </div>
                    <input name = 'chosen_ptrelationid' id = 'chosen_ptrelationid' type="hidden" class="form-control">
                    <div class = 'row mt-2'>
                        <div class = 'col-md-12'>
                            <div class="form-group">
                                <label for="ptrelation_status">Status</label>
                                <select name = 'eptrelation_status' id = 'eptrelation_status' class="form-control selectpicker">
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
                    <button type="button" class="btn btn-primary btn-link ptrelationeditbtn">Done</button>
                    <button type="button" class="btn btn-danger btn-link" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        $('.ptrelation_add').click(function(){
            $('#ptrelation_add_modal').modal('show');
        });
        $('.ptrelationaddbtn').click(function(){
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
                                $('#ptrelationaddform').submit();
                        }
                });
        });
        $('.ptrelation_delete').click(function(){
            select_ptrelation = $(this).parent().parent();
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
                            url:"{{ url('/admin/deleteptrelation') }}",
                            data:{'_token':'<?php echo csrf_token() ?>',id:select_ptrelation.attr('id')},
                            success:function(data){
                                if(data['success'] == 'success'){
                                    Swal.fire({
                                        text: "Delete successfully",
                                        type: 'success',
                                        confirmButtonColor: '#3085d6',
                                        confirmButtonText: 'Done'
                                    }).then((result) => {
                                        select_ptrelation.remove();
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
        $('.ptrelation_edit').click(function(){
            $('#chosen_ptrelationid').val($(this).parent().parent().attr('id'));
            $.ajax({
                type:'POST',
                url:"{{ url('/admin/chosenptrelation') }}",
                data:{'_token':'<?php echo csrf_token() ?>',id:$(this).parent().parent().attr('id')},
                dataType:"json",
                success:function(data){
                    $('#eptrelation').val(data[0]['name']);
                    $('#eptrelation_status').val(data[0]['status']);
                    $('#eptrelation_status').selectpicker('refresh');
                    $('#ptrelation_edit_modal').modal('show');
                    
                }
            });	
        });
        $('.ptrelationeditbtn').click(function(){
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
                        $('#ptrelationeditform').submit();
                    }
                });
        });
        $('#ptrelationtable').DataTable({
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