
<div class = 'row justify-content-center' style = "z-index: 1000;position: relative;">
    <div class="col-md-6">
        <form id = 'pushmessageform' action="{{url('admin/pushmessage')}}" method="POST">
        {{ csrf_field() }}
        <div class="card">
            <div class="card-body">
                <div class = "row">
                    <div class = 'col-md-12 mt-2 searchUsersdiv'>
                        <!-- Split dropup button -->
                        <div class="form-group mt-0">
                            <select class="form-control selectpicker" name = "userlist" id="userlist" data-live-search="true">
                                <option value = "1">All Users</option>
                                <option value = "2">All Providers</option>
                                <optgroup label="Specific Recipient">
                                    @foreach($users as $users_array)
                                    <option value = "{{$users_array->userid}}_specific">{{$users_array->fname}} {{$users_array->lname}}</option>
                                    @endforeach
                                </optgroup>
                            </select>
                        </div>
                    </div>
                    <div class = "col-md-12 mt-3">
                        <div class="form-group">
                            <label for="desc">Please enter message</label>
                            <textarea class="form-control" name = "desc" id="desc" rows="5"></textarea>
                        </div>
                    </div>
                </div>
                <div class = "row">
                    <div class = "col-md-12 text-center">
                        <button type="submit" class="btn btn-success pushbtn">Send</button>
                        <button type="button" class="btn btn-danger clearmessage">Clear</button>
                    </div>
                </div>
            </div>
        </div>
        </form>
    </div>
</div>
<div class = 'row'>
    <div class="col-md-12">
        <div class="card">
        <div class="card-header card-header-icon card-header-primary no-print">
            <div class="card-icon">
            <i class="material-icons">notifications</i>
            </div>
            <div class = 'row'>
                <div class = 'col-md-3'><h4 class="card-title">Message List</h4></div>
                <div class = "col-md-6 mt-3">
                    <div class="form-check form-check-radio form-check-inline">
                        <label class="form-check-label">
                            <input class="form-check-input filterusers" type="radio" name="filterusers" value="0" <?php echo $chosenfilter == 0?'checked':''; ?>> All
                            <span class="circle">
                                <span class="check"></span>
                            </span>
                        </label>
                    </div>
                    <div class="form-check form-check-radio form-check-inline">
                        <label class="form-check-label">
                            <input class="form-check-input filterusers" type="radio" name="filterusers" value="1" <?php echo $chosenfilter == 1?'checked':''; ?>> Users
                            <span class="circle">
                                <span class="check"></span>
                            </span>
                        </label>
                    </div>
                    <div class="form-check form-check-radio form-check-inline disabled">
                        <label class="form-check-label">
                            <input class="form-check-input filterusers" type="radio" name="filterusers" value="2" <?php echo $chosenfilter == 2?'checked':''; ?>> Providers
                            <span class="circle">
                                <span class="check"></span>
                            </span>
                        </label>
                    </div>
                </div>
                <div class = 'col-md-3 text-right downloaddiv'>
                    <select name = '' id = '' class="form-control selectpicker downloadtype">
                        <option value="0">Download</option>
                        <option value="1">CSV</option>
                        <option value="2">PDF</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
            <table class="table table-striped table-no-bordered table-hover" id = "messagetable">
                <thead class=" text-primary">
                    <th>Sent Date</th>
                    <th>Sent To</th>
                    <th>Message ID</th>
                    <th>Text</th>
                    <th>Action</th>
                </thead>
                <tbody>
                    @foreach($messagelist as $messagelist_array)
                    <tr id = "{{$messagelist_array->id}}">
                        <td>{{date("D, M j Y",strtotime($messagelist_array->created_at))}}</td>
                        <td>
                            <?php if($messagelist_array->user == 1): ?>
                            All Users
                            <?php elseif($messagelist_array->user == 2): ?>
                            All Providers
                            <?php else: ?>
                            {{$messagelist_array->fname." ".$messagelist_array->lname}}
                            <?php endif ?>
                        </td>
                        <td>{{$messagelist_array->msgid}}</td>
                        <td>{{$messagelist_array->description}}</td>
                        <td><span class = 'message_view'><i class = 'fa fa-eye'></i></span>&nbsp;<span class = 'message_delete'><i class = 'fa fa-trash'></i></span></td>
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
<div class="modal fade" id="message_detail_modal">
    <div class="modal-dialog">
            <div class="modal-content">
            
            <!-- Modal Header -->
            <div class="modal-header">
                    <h4 class="modal-title messagenamefordetail" style="font-weight:500;"></h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            
            <!-- Modal body -->
            <div class="modal-body">
                <div class = "row">
                    <div class = "col-md-12 text-right text-success"><span class = "msgdate" style = "font-size: .75rem;"></span></div>
                </div>
                <div class = "row">
                    <div class = "col-md-12">
                        <div class = "msgdesc"></div>
                    </div>
                </div>
            </div>
            <!-- Modal footer -->
            <div class="modal-footer">
                    <button type="button" class="btn btn-danger btn-link" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!--- end modal -->
<script>
    $(document).ready(function() {
        
        $('.message_delete').click(function(){
            select_message = $(this).parent().parent();
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
                            url:"{{ url('/admin/deletemessage') }}",
                            data:{'_token':'<?php echo csrf_token() ?>',id:select_message.attr('id')},
                            success:function(data){
                                if(data['success'] == 'success'){
                                    Swal.fire({
                                        text: "Delete successfully",
                                        type: 'success',
                                        confirmButtonColor: '#3085d6',
                                        confirmButtonText: 'Done'
                                    }).then((result) => {
                                        select_message.remove();
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
        $('#messagetable').DataTable({
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
        $(document).on('click',".message_view",function(){
            select_message = $(this).parent().parent();
            $(".messagenamefordetail").html("Msg ID : "+$(this).parent().parent().children().eq(2).html()+" - "+$(this).parent().parent().children().eq(1).html());
            $(".msgdate").html($(this).parent().parent().children().eq(0).html());
            $.ajax({
                type:'POST',
                url:"{{ url('/admin/chosenmessage') }}",
                data:{'_token':'<?php echo csrf_token() ?>',id:select_message.attr('id')},
                dataType:"json",
                success:function(data){
                    $(".msgdesc").empty();
                    $(".msgdesc").html(data['description']);
                }
            });
            $("#message_detail_modal").modal("show");
        });
        // $(".pushbtn").click(function(){
        //     var myContent = tinymce.get("writearea").getContent();
        //     console.log(myContent);
        // });
        $(".clearmessage").click(function(){
            $("#desc").val('');
            $("#userlist").val(0);
            $("#userlist").selectpicker('refresh');
        });
        $(".filterusers").click(function(){
            $('body').append("<form id = 'filterview' action='{{ url('/admin/filterviewformessage') }}' method='post'>"+'{{ csrf_field() }}'+"<input type = 'hidden' name = 'filtervalue' value = '"+$(this).val()+"' /></form>");
            $('#filterview').submit();
        });
        $(".downloadtype").change(function(){
            if($(this).val() == 1){
                $('body').append("<form id = 'expertCSV' action='{{ url('/admin/expertCSVformsg') }}' method='get'>"+'{{ csrf_field() }}'+"</form>");
                $('#expertCSV').submit();
            }
            else if($(this).val() == 2){
                $('body').append("<form id = 'downloadPDF' action='{{ url('/admin/downloadPDFformsg') }}' method='get'>"+'{{ csrf_field() }}'+"</form>");
                $('#downloadPDF').submit();
            }
            $(this).val(0);
            $(this).selectpicker('refresh');
        });
    });
</script>
