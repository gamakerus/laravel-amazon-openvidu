
<div class = "row">
    <div class="col-lg-3 col-md-6 col-sm-6">
        <div class="card card-stats">
            <div class="card-header card-header-primary card-header-icon">
                <div class="card-icon">
                    <i class="material-icons">monetization_on</i>
                </div>
                <p class="card-category">Number</p>
                <h3 class="card-title"><?php echo $number; ?>
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
            <div class="card-header card-header-info card-header-icon">
                <div class="card-icon">
                    <i class="material-icons">monetization_on</i>
                </div>
                <p class="card-category">Amount</p>
                <h3 class="card-title"><?php echo $totalamonut; ?>
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
<div class = 'row'>
    <div class="col-md-12">
        <div class="card">
        <div class="card-header card-header-icon card-header-primary no-print">
            <div class="card-icon">
            <i class="material-icons">monetization_on</i>
            </div>
            <div class = 'row'>
                <div class = 'col-md-6'><h4 class="card-title ">Transactions List</h4></div>
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
            <table class="table table-striped table-no-bordered table-hover" id = "transactiontable">
                <thead class=" text-primary">
                    <th>Payment Date</th>
                    <th>Transaction ID</th>
                    <th>Patient Name</th>
                    <th>Provider Name</th>
                    <th>Job ID</th>
                    <th>Amount</th>
                    <th>Action</th>
                </thead>
                <tbody>
                    @foreach($trans as $trans_array)
                        <tr id = "{{$trans_array->id}}">
                            <td>{{date("D, M j Y",strtotime($trans_array->created_at))}}</td>
                            <td>{{$trans_array->tranid}}</td>
                            <td>{{$trans_array->cfname." ".$trans_array->clname}}</td>
                            <td>{{$trans_array->pfname." ".$trans_array->plname}}</td>
                            <td>{{$trans_array->jobid}}</td>
                            <td>{{$trans_array->amount}}</td>
                            <td>
                                <span class = 'transaction_view'><i class = 'fa fa-eye'></i></span>&nbsp;<span class = 'transaction_delete'><i class = 'fa fa-trash'></i></span>
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
<div class="modal fade" id="transaction_detail_modal">
    <div class="modal-dialog">
            <div class="modal-content">
            
            <!-- Modal Header -->
            <div class="modal-header">
                    <h4 class="modal-title transactionnamefordetail" style="font-weight:500;"></h4>
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
                                <h6>Payment Date : <span class = "chosenpdate"></span></h6>
                                <h6>Amount : <span class = "chosenamount"></span></h6>
                                <h6>Client : <span class = "chosenclient"></span></h6>
                                <h6>Provider : <span class = "chosenprovider"></span></h6>
                                <h6>Job ID : <span class = "chosenjobid"></span></h6>
                            </div>
                        </div>
                    </div>
                </div>
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
<script>
    $(document).ready(function() {
        
        $('.transaction_delete').click(function(){
            select_transaction = $(this).parent().parent();
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
                            url:"{{ url('/admin/deletetransaction') }}",
                            data:{'_token':'<?php echo csrf_token() ?>',id:select_transaction.attr('id')},
                            success:function(data){
                                if(data['success'] == 'success'){
                                    Swal.fire({
                                        text: "Delete successfully",
                                        type: 'success',
                                        confirmButtonColor: '#3085d6',
                                        confirmButtonText: 'Done'
                                    }).then((result) => {
                                        select_transaction.remove();
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
        $('#transactiontable').DataTable({
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
        $(".transaction_view").click(function(){
            select_transaction = $(this).parent().parent();
            $(".transactionnamefordetail").html($(this).parent().parent().children().eq(1).html());
            $(".chosenpdate").html($(this).parent().parent().children().eq(0).html());
            $(".chosenamount").html($(this).parent().parent().children().eq(5).html());
            $(".chosenclient").html($(this).parent().parent().children().eq(2).html());
            $(".chosenprovider").html($(this).parent().parent().children().eq(3).html());
            $(".chosenjobid").html($(this).parent().parent().children().eq(4).html());
            
            $("#transaction_detail_modal").modal("show");
        });
        $(".downloadtype").change(function(){
            if($(this).val() == 1){
                $('body').append("<form id = 'expertCSV' action='{{ url('/admin/expertCSVfortrans') }}' method='get'>"+'{{ csrf_field() }}'+"</form>");
                $('#expertCSV').submit();
            }
            else if($(this).val() == 2){
                $('body').append("<form id = 'downloadPDF' action='{{ url('/admin/downloadPDFfortrans') }}' method='get'>"+'{{ csrf_field() }}'+"</form>");
                $('#downloadPDF').submit();
            }
            $(this).val(0);
            $(this).selectpicker('refresh');
        });
    });
</script>