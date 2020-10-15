
<div class = "row">
    <div class="col-lg-3 col-md-6 col-sm-6">
        <div class="card card-stats">
            <div class="card-header card-header-primary card-header-icon">
                <div class="card-icon">
                    <i class="material-icons">redo</i>
                </div>
                <p class="card-category">Solved</p>
                <h3 class="card-title"><?php echo $solved; ?>
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
                    <i class="material-icons">redo</i>
                </div>
                <p class="card-category">Pending</p>
                <h3 class="card-title"><?php echo $pending; ?>
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
<div class = "row">
    <div class = "col-md-12">
        <div class="form-check form-check-radio form-check-inline">
            <label class="form-check-label">
                <input class="form-check-input filtercanref" type="radio" name="filtercanref"  value="0" <?php echo $chosenfilter == 0?'checked':''; ?>> All
                <span class="circle">
                    <span class="check"></span>
                </span>
            </label>
        </div>
        <div class="form-check form-check-radio form-check-inline">
            <label class="form-check-label">
                <input class="form-check-input filtercanref" type="radio" name="filtercanref"  value="1" <?php echo $chosenfilter == 1?'checked':''; ?>> Solved
                <span class="circle">
                    <span class="check"></span>
                </span>
            </label>
        </div>
        <div class="form-check form-check-radio form-check-inline disabled">
            <label class="form-check-label">
                <input class="form-check-input filtercanref" type="radio" name="filtercanref" value="2" <?php echo $chosenfilter == 2?'checked':''; ?>> Pending
                <span class="circle">
                    <span class="check"></span>
                </span>
            </label>
        </div>
        <button style = "float:right" type="button" class="btn btn-danger btn-link cancelreasonbtn" data-dismiss="modal">Update Cancellation Reasons</button>
    </div>
</div>
<div class = 'row'>
    <div class="col-md-12">
        <div class="card">
        <div class="card-header card-header-icon card-header-primary no-print">
            <div class="card-icon">
            <i class="material-icons">redo</i>
            </div>
            <div class = 'row'>
                <div class = 'col-md-6'><h4 class="card-title ">Cancellation & Refunds List</h4></div>
                <div class = 'col-md-6 text-right downloaddiv'>
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
            <table class="table table-striped table-no-bordered table-hover" id = "cancelrefundtable">
                <thead class=" text-primary">
                    <th>Request Date</th>
                    <th>Refund ID</th>
                    <th>Patient Name</th>
                    <th>Provider Name</th>
                    <th>Job ID</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Action</th>
                </thead>
                <tbody>
                    @foreach($canref as $canref_array)
                        <tr id = "{{$canref_array->id}}">
                            <td>{{date("D, M j Y",strtotime($canref_array->created_at))}}</td>
                            <td>{{$canref_array->refid}}</td>
                            <td>{{$canref_array->cfname." ".$canref_array->clname}}</td>
                            <td>{{$canref_array->pfname." ".$canref_array->plname}}</td>
                            <td>{{$canref_array->jobid}}</td>
                            <td>{{$canref_array->amount}}</td>
                            <td>
                                <?php if($canref_array->status == 1): ?>
                                    <span class = 'canrefpending'>Pending</span>
                                <?php elseif($canref_array->status == 2): ?>
                                    <span class = 'canrefsolved'>Solved</span>
                                <?php endif ?>
                            </td>
                            <td>
                            <?php if($canref_array->status == 1): ?><span class = 'canref_refund'><i class="fa fa-exchange" aria-hidden="true"></i></span>&nbsp;<?php endif ?><span class = 'canref_view'><i class = 'fa fa-eye'></i></span>&nbsp;<span class = 'canref_delete'><i class = 'fa fa-trash'></i></span>
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
<div class="modal fade" id="updatereason">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                    <h4 class="modal-title" style="font-weight:500;">Reason List</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            
            <!-- Modal body -->
            <div class="modal-body">
                <div class = "row">
                    <div class = "col-md-12">   
                        <div class="form-group bmd-form-group">
                            <label class="bmd-label-static">Reason</label>
                            <input name = 'reason' id = 'reason' type="text" class="form-control">
                        </div>
                    </div>
                </div>
                <div class = "row mt-3">
                    <div class = "col-md-12">   
                        <div class="table-responsive">
                            <table class="table table-striped table-no-bordered table-hover reasontable">
                                <thead>
                                    <th class = "font-500">Description</th>
                                    <th class = "font-500">Action</th>
                                </thead>
                                <tbody>
                                    @foreach($reasonlist as $reasonlist_array)
                                    <tr id = "{{$reasonlist_array->id}}">
                                        <td><input class = "form-control" value='{{$reasonlist_array->name}}' disabled /></td>
                                        <td><span class = 'reason_save d-none'><i class = 'fa fa-save'></i></span>&nbsp;<span class = 'reason_edit'><i class = 'fa fa-edit'></i></span>&nbsp;<span class = 'reason_delete'><i class = 'fa fa-trash'></i></span></td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Modal footer -->
            <div class="modal-footer">
                    <button type="button" class="btn btn-primary btn-link reasonaddbtn">Add</button>
                    <button type="button" class="btn btn-danger btn-link" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!--- end modal -->
<!-- The Modal -->
<div class="modal fade" id="canref_detail_modal">
    <div class="modal-dialog">
            <div class="modal-content">
            
            <!-- Modal Header -->
            <div class="modal-header">
                    <h4 class="modal-title canrefnamefordetail" style="font-weight:500;"></h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            
            <!-- Modal body -->
            <div class="modal-body">
                <div class = "row">
                    <div class = "col-md-12">
                        <div class = "row">
                            <div class = "col-md-6">
                                <span class = 'transaction_active transactiontypeview'></span>
                            </div>
                        </div>
                        <div class = "row">
                            <div class = "col-md-12">
                                <h6>Request Date : <span class = "chosenpdate"></span></h6>
                                <h6>Amount : <span class = "chosenamount"></span></h6>
                                <h6>Client : <span class = "chosenclient"></span></h6>
                                <h6>Provider : <span class = "chosenprovider"></span></h6>
                                <h6>Job ID : <span class = "chosenjobid"></span></h6>
                                <br>
                                <h5 class = "chosenreason"></h5>
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
<!--- end modal -->
<script>
    $(document).ready(function() {
        
        $('.canref_delete').click(function(){
            select_canref = $(this).parent().parent();
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
                            url:"{{ url('/admin/deletecanref') }}",
                            data:{'_token':'<?php echo csrf_token() ?>',id:select_canref.attr('id')},
                            success:function(data){
                                if(data['success'] == 'success'){
                                    Swal.fire({
                                        text: "Delete successfully",
                                        type: 'success',
                                        confirmButtonColor: '#3085d6',
                                        confirmButtonText: 'Done'
                                    }).then((result) => {
                                        select_canref.remove();
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
        $('#cancelrefundtable').DataTable({
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
        $(".canref_view").click(function(){
            select_canref = $(this).parent().parent();
            $(".canrefnamefordetail").html($(this).parent().parent().children().eq(1).html()+" "+$(this).parent().parent().children().eq(6).html());
            $(".chosenpdate").html($(this).parent().parent().children().eq(0).html());
            $(".chosenamount").html($(this).parent().parent().children().eq(5).html());
            $(".chosenclient").html($(this).parent().parent().children().eq(2).html());
            $(".chosenprovider").html($(this).parent().parent().children().eq(3).html());
            $(".chosenjobid").html($(this).parent().parent().children().eq(4).html());
            $.ajax({
                type:'POST',
                url:"{{ url('/admin/chosencanref') }}",
                data:{'_token':'<?php echo csrf_token() ?>',id:select_canref.attr('id')},
                dataType:"json",
                success:function(data){
                    $(".chosenreason").html(data);
                    $("#canref_detail_modal").modal("show");
                    
                }
            });
        });
        $(".cancelreasonbtn").click(function(){
            $("#updatereason").modal('show');
        });
        $(".reasonaddbtn").click(function(){
            $.ajax({
                type:'POST',
                url:"{{ url('/admin/addreason') }}",
                data:{'_token':'<?php echo csrf_token() ?>',value:$("#reason").val()},
                dataType:"json",
                success:function(data){
                    $(".reasontable tbody").empty();
                    for(var i =0; i < data.length; i++){
                        $(".reasontable tbody").append("<tr id='"+data[i]['id']+"'><td><input class = 'form-control' value='"+data[i]['name']+"' disabled /></td><td><span class = 'reason_save d-none'><i class = 'fa fa-save'></i></span>&nbsp;<span class = 'reason_edit'><i class = 'fa fa-edit'></i></span>&nbsp;<span class = 'reason_delete'><i class = 'fa fa-trash'></i></span></td></tr>");
                    }
                }
            });
        });
        $(document).on('click',".reason_delete",function(){
            select_reason = $(this).parent().parent();
            Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, Delete it!'
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        type:'delete',
                        url:"{{ url('/admin/deletereason') }}",
                        data:{'_token':'<?php echo csrf_token() ?>',id:select_reason.attr('id')},
                        success:function(data){
                            if(data['success'] == 'success'){
                                Swal.fire({
                                    text: "Delete successfully",
                                    type: 'success',
                                    confirmButtonColor: '#3085d6',
                                    confirmButtonText: 'Done'
                                });
                                select_reason.remove();
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
        $(".filtercanref").click(function(){
            $('body').append("<form id = 'filterview' action='{{ url('/admin/filterviewforcanref') }}' method='post'>"+'{{ csrf_field() }}'+"<input type = 'hidden' name = 'filtervalue' value = '"+$(this).val()+"' /></form>");
            $('#filterview').submit();
        });
        $(".downloadtype").change(function(){
            if($(this).val() == 1){
                $('body').append("<form id = 'expertCSV' action='{{ url('/admin/expertCSVforcanref') }}' method='get'>"+'{{ csrf_field() }}'+"</form>");
                $('#expertCSV').submit();
            }
            else if($(this).val() == 2){
                $('body').append("<form id = 'downloadPDF' action='{{ url('/admin/downloadPDFforcanref') }}' method='get'>"+'{{ csrf_field() }}'+"</form>");
                $('#downloadPDF').submit();
            }
            $(this).val(0);
            $(this).selectpicker('refresh');
        });
        $(".canref_refund").click(function(){
            Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, refund it!'
            }).then((result) => {
                    if (result.value) {
                        $('body').append("<form id = 'refundform' action='{{ url('/admin/refundaction') }}' method='post'>"+'{{ csrf_field() }}'+"<input type = 'hidden' name = 'canrefid' value = '"+$(this).parent().parent().attr('id')+"' /></form>");
                        $('#refundform').submit();
                    }
            });
            
        });
        $(document).on('click','.reason_edit',function(){
            $(this).addClass('d-none');
            $(this).parent().parent().children().eq(0).children().eq(0).children().eq(0).prop('disabled',false);
            $(this).parent().parent().children().eq(1).children().eq(0).removeClass('d-none');
        });
        $(document).on('click','.reason_save',function(){
            $(this).addClass('d-none');
            $(this).parent().parent().children().eq(0).children().eq(0).children().eq(0).prop('disabled',true);
            $(this).parent().parent().children().eq(1).children().eq(1).removeClass('d-none');
            $.ajax({
                type:'POST',
                url:"{{ url('/admin/updatereason') }}",
                data:{'_token':'<?php echo csrf_token() ?>',id:$(this).parent().parent().attr('id'),desc:$(this).parent().parent().children().eq(0).children().eq(0).children().eq(0).val()},
                success:function(data){
                    
                }
            });
        });
    });
</script>