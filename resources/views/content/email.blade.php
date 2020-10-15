
<div class = 'row' style = "z-index: 1000;position: relative;">
    <div class="col-md-12">
        <form id = 'pushemailform' action="{{url('admin/pushemail')}}" method="POST" enctype="multipart/form-data">
        {{ csrf_field() }}
        <div class="card">
            <div class="card-body">
                <div class = "row">
                    <div class = "col-md-4">
                        <div class = 'row'>
                            <div class = 'col-md-12 mt-3'>
                                <div class="form-group bmd-form-group">
                                    <label class="bmd-label-static mt-1">Enter Subject</label>
                                    <input name = 'subject' id = 'subject' type="text" class="form-control">
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
                        <button type="button" class="btn btn-danger clearemail">Clear</button>
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
                <div class = 'col-md-3'><h4 class="card-title">Emails List</h4></div>
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
            <table class="table table-striped table-no-bordered table-hover" id = "emailtable">
                <thead class=" text-primary">
                    <th>Sent Date</th>
                    <th>Sent To</th>
                    <th>Email ID</th>
                    <th>Title</th>
                    <th>Action</th>
                </thead>
                <tbody>
                    @foreach($emaillist as $emaillist_array)
                    <tr id = "{{$emaillist_array->id}}">
                        <td>{{date("D, M j Y",strtotime($emaillist_array->created_at))}}</td>
                        <td>
                            <?php if($emaillist_array->user == 1): ?>
                            All Users
                            <?php elseif($emaillist_array->user == 2): ?>
                            All Providers
                            <?php else: ?>
                            {{$emaillist_array->fname." ".$emaillist_array->lname}}
                            <?php endif ?>
                        </td>
                        <td>{{$emaillist_array->emailid}}</td>
                        <td>{{$emaillist_array->subject}}</td>
                        <td><span class = 'email_view'><i class = 'fa fa-eye'></i></span>&nbsp;<span class = 'email_delete'><i class = 'fa fa-trash'></i></span></td>
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
<div class="modal fade" id="email_detail_modal">
    <div class="modal-dialog">
            <div class="modal-content">
            
            <!-- Modal Header -->
            <div class="modal-header">
                    <h4 class="modal-title emailnamefordetail" style="font-weight:500;"></h4>
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
                        <div class = "emaildesc"></div>
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
        
        $('.email_delete').click(function(){
            select_email = $(this).parent().parent();
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
                            url:"{{ url('/admin/deleteemail') }}",
                            data:{'_token':'<?php echo csrf_token() ?>',id:select_email.attr('id')},
                            success:function(data){
                                if(data['success'] == 'success'){
                                    Swal.fire({
                                        text: "Delete successfully",
                                        type: 'success',
                                        confirmButtonColor: '#3085d6',
                                        confirmButtonText: 'Done'
                                    }).then((result) => {
                                        select_email.remove();
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
        $('#emailtable').DataTable({
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
        $(document).on('click',".email_view",function(){
            select_email = $(this).parent().parent();
            $(".emailnamefordetail").html("Email ID : "+$(this).parent().parent().children().eq(2).html()+" - "+$(this).parent().parent().children().eq(1).html());
            $(".nottitle").html($(this).parent().parent().children().eq(3).html());
            $(".notdate").html($(this).parent().parent().children().eq(0).html());
            
            $.ajax({
                type:'POST',
                url:"{{ url('/admin/chosenemail') }}",
                data:{'_token':'<?php echo csrf_token() ?>',id:select_email.attr('id')},
                dataType:"json",
                success:function(data){
                    $(".emaildesc").empty();
                    $(".fileexisted").empty();
                    $(".emaildesc").append(data['description']);
                    if(data['filename'] != null)
                        $(".fileexisted").append("<a href = 'https://operations-flexhealth-me.s3.us-east-1.amazonaws.com/email/"+data['filename']+"' download='"+data['filename']+"'><h6>"+data['filename']+"</h6></a>")
                    else
                    $(".fileexisted").append("<h6 class = 'text-grey'>Nothing uploaded</h6>")
                }
            });
            $("#email_detail_modal").modal("show");
        });
        // $(".pushbtn").click(function(){
        //     var myContent = tinymce.get("writearea").getContent();
        //     console.log(myContent);
        // });
        $(".clearemail").click(function(){
            tinymce.get("writearea").setContent("");
            $("#subject").val("");
            $("#userlist").val(0);
            $("#userlist").selectpicker('refresh');
        });
        $(".filterusers").click(function(){
            $('body').append("<form id = 'filterview' action='{{ url('/admin/filterviewforemail') }}' method='post'>"+'{{ csrf_field() }}'+"<input type = 'hidden' name = 'filtervalue' value = '"+$(this).val()+"' /></form>");
            $('#filterview').submit();
        });
        $(".downloadtype").change(function(){
            if($(this).val() == 1){
                $('body').append("<form id = 'expertCSV' action='{{ url('/admin/expertCSVforemail') }}' method='get'>"+'{{ csrf_field() }}'+"</form>");
                $('#expertCSV').submit();
            }
            else if($(this).val() == 2){
                $('body').append("<form id = 'downloadPDF' action='{{ url('/admin/downloadPDFforemail') }}' method='get'>"+'{{ csrf_field() }}'+"</form>");
                $('#downloadPDF').submit();
            }
            $(this).val(0);
            $(this).selectpicker('refresh');
        });
    });
</script>
