
<div class = 'row' style = "z-index: 1000;position: relative;">
    <div class="col-md-12">
        <form id = 'pushnotificationform' action="{{url('admin/pushnotification')}}" method="POST" enctype="multipart/form-data">
        {{ csrf_field() }}
        <div class="card">
            <div class="card-body">
                <div class = "row">
                    <div class = "col-md-4">
                        <div class = 'row'>
                            <div class = 'col-md-12 mt-3'>
                                <div class="form-group bmd-form-group">
                                    <label class="bmd-label-static mt-1">Enter Title</label>
                                    <input name = 'title' id = 'title' type="text" class="form-control">
                                </div>
                            </div>
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
                            
                            <div class="col-md-12 mt-2">
                                <div class="form-group">
                                    <div class="custom-file form-group bmd-form-group dg-input">
                                        <input type="file" class="custom-file-input form-control" id="customFile" name="file">
                                        <label class="custom-file-label" for="customFile">Add Attachment</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class = "col-md-8">
                        <div class = "row">
                            <div class = "col-md-12">
                                <textarea name="writearea" id="writearea"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class = "row">
                    <div class = "col-md-12 text-center">
                        <button type="submit" class="btn btn-success pushbtn">Send</button>
                        <button type="button" class="btn btn-danger clearnotification">Clear</button>
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
                <div class = 'col-md-3'><h4 class="card-title">Notifications List</h4></div>
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
            <table class="table table-striped table-no-bordered table-hover" id = "notificationtable">
                <thead class=" text-primary">
                    <th>Sent Date</th>
                    <th>Sent To</th>
                    <th>Notification ID</th>
                    <th>Title</th>
                    <th>Action</th>
                </thead>
                <tbody>
                    @foreach($notificationlist as $notificationlist_array)
                    <tr id = "{{$notificationlist_array->id}}">
                        <td>{{date("D, M j Y",strtotime($notificationlist_array->created_at))}}</td>
                        <td>
                            <?php if($notificationlist_array->user == 1): ?>
                            All Users
                            <?php elseif($notificationlist_array->user == 2): ?>
                            All Providers
                            <?php else: ?>
                            {{$notificationlist_array->fname." ".$notificationlist_array->lname}}
                            <?php endif ?>
                        </td>
                        <td>{{$notificationlist_array->notid}}</td>
                        <td>{{$notificationlist_array->title}}</td>
                        <td><span class = 'notification_view'><i class = 'fa fa-eye'></i></span>&nbsp;<span class = 'notification_delete'><i class = 'fa fa-trash'></i></span></td>
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
<div class="modal fade" id="notification_detail_modal">
    <div class="modal-dialog">
            <div class="modal-content">
            
            <!-- Modal Header -->
            <div class="modal-header">
                    <h4 class="modal-title notificationnamefordetail" style="font-weight:500;"></h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            
            <!-- Modal body -->
            <div class="modal-body">
                <div class = "row">
                    <div class = "col-md-12">
                        <h6 class = "text-center nottitle"></h6>
                    </div>
                    <div class = "col-md-12 text-right text-success"><span class = "notdate" style = "font-size: .75rem;"></span></div>
                </div>
                <div class = "row">
                    <div class = "col-md-12">
                        <div class = "notdesc"></div>
                    </div>
                </div>
                <div class = "row">
                    <div class = "col-auto">
                        <h6>Attached File : </h6>
                    </div>
                    <div class = "col fileexisted">
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
    tinymce.init({
        selector: 'textarea#writearea',
        skin: 'material-classic',
        content_css: [
            'material-classic',
            '//www.tiny.cloud/css/codepen.min.css'
    ],
        icons: 'material',
        plugins: 'code image link lists',
        toolbar: 'undo redo | bold italic underline forecolor backcolor | align | bullist numlist',
        menubar: false
    });
    $(".custom-file-input").on("change", function() {
        var fileName = $(this).val().split("\\").pop();
        $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
    });
</script>
<script>
    $(document).ready(function() {
        
        $('.notification_delete').click(function(){
            select_notification = $(this).parent().parent();
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
                            url:"{{ url('/admin/deletenotification') }}",
                            data:{'_token':'<?php echo csrf_token() ?>',id:select_notification.attr('id')},
                            success:function(data){
                                if(data['success'] == 'success'){
                                    Swal.fire({
                                        text: "Delete successfully",
                                        type: 'success',
                                        confirmButtonColor: '#3085d6',
                                        confirmButtonText: 'Done'
                                    }).then((result) => {
                                        select_notification.remove();
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
        $('#notificationtable').DataTable({
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
        $(document).on('click',".notification_view",function(){
            select_notification = $(this).parent().parent();
            $(".notificationnamefordetail").html("Not ID : "+$(this).parent().parent().children().eq(2).html()+" - "+$(this).parent().parent().children().eq(1).html());
            $(".nottitle").html($(this).parent().parent().children().eq(3).html());
            $(".notdate").html($(this).parent().parent().children().eq(0).html());
            
            $.ajax({
                type:'POST',
                url:"{{ url('/admin/chosennotification') }}",
                data:{'_token':'<?php echo csrf_token() ?>',id:select_notification.attr('id')},
                dataType:"json",
                success:function(data){
                    $(".notdesc").empty();
                    $(".fileexisted").empty();
                    $(".notdesc").append(data['description']);
                    if(data['filename'] != null)
                        $(".fileexisted").append("<a href = 'https://operations-flexhealth-me.s3.us-east-1.amazonaws.com/notification/"+data['filename']+"' download='"+data['filename']+"'><h6>"+data['filename']+"</h6></a>")
                    else
                    $(".fileexisted").append("<h6 class = 'text-grey'>Nothing uploaded</h6>")
                }
            });
            $("#notification_detail_modal").modal("show");
        });
        // $(".pushbtn").click(function(){
        //     var myContent = tinymce.get("writearea").getContent();
        //     console.log(myContent);
        // });
        $(".clearnotification").click(function(){
            tinymce.get("writearea").setContent("");
            $("#title").val("");
            $("#userlist").val(0);
            $("#userlist").selectpicker('refresh');
        });
        $(".filterusers").click(function(){
            $('body').append("<form id = 'filterview' action='{{ url('/admin/filterviewfornotification') }}' method='post'>"+'{{ csrf_field() }}'+"<input type = 'hidden' name = 'filtervalue' value = '"+$(this).val()+"' /></form>");
            $('#filterview').submit();
        });
        $(".downloadtype").change(function(){
            if($(this).val() == 1){
                $('body').append("<form id = 'expertCSV' action='{{ url('/admin/expertCSVfornot') }}' method='get'>"+'{{ csrf_field() }}'+"</form>");
                $('#expertCSV').submit();
            }
            else if($(this).val() == 2){
                $('body').append("<form id = 'downloadPDF' action='{{ url('/admin/downloadPDFfornot') }}' method='get'>"+'{{ csrf_field() }}'+"</form>");
                $('#downloadPDF').submit();
            }
            $(this).val(0);
            $(this).selectpicker('refresh');
        });
    });
</script>
