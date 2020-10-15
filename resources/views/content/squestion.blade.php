<div class = 'row'>
    <div class="col-md-12">
        <div class="card">
        <div class="card-header card-header-icon card-header-primary no-print">
            <div class="card-icon">
            <i class="material-icons">record_voice_over</i>
            </div>
            <div class = 'row'>
                <div class = 'col-md-6'><h4 class="card-title ">Question Information</h4></div>
                <div class = 'col-md-6 text-right'><span class = 'question_add' ><i class = 'fa fa-plus'></i></span></div>

            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
            <table class="table table-striped table-no-bordered table-hover" id = "questiontable">
                <thead class=" text-primary">
                    <th>Question</th>
                    <th>Status</th>
                    <th>Action</th>
                </thead>
                <tbody>
                    @foreach($questionlist as $questionlist_array)
                    <tr id = "{{$questionlist_array->id}}">
                        <td>{{$questionlist_array->question}}</td>
                        <td>
                            <?php if($questionlist_array->status == 1): ?>
                                <span class = 'question_active'>Active</span>
                            <?php elseif($questionlist_array->status == 0): ?>
                                <span class = 'question_deactive'>Inactive</span>
                            <?php endif ?>
                        </td>
                        <td><span class = 'question_edit'><i class = 'fa fa-cog'></i></span>&nbsp;<span class = 'question_delete'><i class = 'fa fa-trash'></i></span></td>
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
<div class="modal fade" id="question_add_modal">
    <div class="modal-dialog">
            <div class="modal-content">
            
            <!-- Modal Header -->
            <div class="modal-header">
                    <h4 class="modal-title" style="font-weight:500;">Add Question</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            
            <!-- Modal body -->
            <div class="modal-body">
                <form id = 'questionaddform' action="{{url('admin/squestionaddForm')}}" method="POST">
                    {{ csrf_field() }}
                    <div class = 'row'>
                        <div class = 'col-md-12'>
                            <div class="form-group">
                                <label for="question">Question</label>
                                <textarea class="form-control" name = 'question' id="question" rows="5"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class = 'row mt-2'>
                        <div class = 'col-md-12'>
                            <div class="form-group">
                                <label for="question_status">Status</label>
                                <select name = 'question_status' id = 'question_status' class="form-control selectpicker">
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
                    <button type="button" class="btn btn-primary btn-link questionaddbtn" data-dismiss="modal">Done</button>
                    <button type="button" class="btn btn-danger btn-link" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!--- end modal -->
    <!-- The Modal -->
<div class="modal fade" id="question_edit_modal">
    <div class="modal-dialog">
        <div class="modal-content">
        
            <!-- Modal Header -->
            <div class="modal-header">
                    <h4 class="modal-title" style="font-weight:500;">Edit Question</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
        
            <!-- Modal body -->
            <div class="modal-body">
                <form id = 'questioneditform' action="{{url('admin/squestioneditForm')}}" method="POST">
                    {{ csrf_field() }}
                    <div class = 'row'>
                        <div class = 'col-md-12'>
                            <div class="form-group">
                                <label for="question">Question</label>
                                <textarea class="form-control" name = 'equestion' id="equestion" rows="5"></textarea>
                            </div>
                        </div>
                    </div>
                    <input name = 'chosen_questionid' id = 'chosen_questionid' type="hidden" class="form-control">
                    <div class = 'row mt-2'>
                        <div class = 'col-md-12'>
                            <div class="form-group">
                                <label for="question_status">Status</label>
                                <select name = 'equestion_status' id = 'equestion_status' class="form-control selectpicker">
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
                    <button type="button" class="btn btn-primary btn-link questioneditbtn">Done</button>
                    <button type="button" class="btn btn-danger btn-link" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        $('.question_add').click(function(){
            $('#question_add_modal').modal('show');
        });
        $('.questionaddbtn').click(function(){
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
                                $('#questionaddform').submit();
                        }
                });
        });
        $('.question_delete').click(function(){
            select_question = $(this).parent().parent();
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
                            url:"{{ url('/admin/deletequestion') }}",
                            data:{'_token':'<?php echo csrf_token() ?>',id:select_question.attr('id')},
                            success:function(data){
                                if(data['success'] == 'success'){
                                    Swal.fire({
                                        text: "Delete successfully",
                                        type: 'success',
                                        confirmButtonColor: '#3085d6',
                                        confirmButtonText: 'Done'
                                    }).then((result) => {
                                        select_question.remove();
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
        $('.question_edit').click(function(){
            $('#chosen_questionid').val($(this).parent().parent().attr('id'));
            $.ajax({
                type:'POST',
                url:"{{ url('/admin/chosenquestion') }}",
                data:{'_token':'<?php echo csrf_token() ?>',id:$(this).parent().parent().attr('id')},
                dataType:"json",
                success:function(data){
                    $('#equestion').val(data[0]['question']);
                    $('#equestion_status').val(data[0]['status']);
                    $('#equestion_status').selectpicker('refresh');
                    $('#question_edit_modal').modal('show');
                    
                }
            });	
        });
        $('.questioneditbtn').click(function(){
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
                        $('#questioneditform').submit();
                    }
                });
        });
        $('#questiontable').DataTable({
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