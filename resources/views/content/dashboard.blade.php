<div class = "row">
    <div class="col-lg-4 col-md-6 col-sm-6">
        <div class="card card-stats">
            <div class="card-header card-header-primary card-header-icon">
                <div class="card-icon">
                    <i class="material-icons">people</i>
                </div>
                <p class="card-category">Number of Users</p>
                <h3 class="card-title"><?php echo $users; ?>
                </h3>
            </div>
            <div class="card-footer d-block p-0">
                <div class = "row">
                    <div class = "col-md-6">
                        <div class="card text-center">
                            <h6 class = "mt-2">Approved Users</h6>
                            <div><?php echo $approved_users; ?></div>
                        </div>
                    </div>
                    <div class = "col-md-6">
                        <div class="card text-center">
                            <h6 class = "mt-2">Not approved Users</h6>
                            <div><?php echo $users-$approved_users; ?></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-md-6 col-sm-6">
        <div class="card card-stats">
            <div class="card-header card-header-info card-header-icon">
                <div class="card-icon">
                    <i class="material-icons">person</i>
                </div>
                <p class="card-category">Number of Providers</p>
                <h3 class="card-title"><?php echo $providers; ?>
                </h3>
            </div>
            <div class="card-footer d-block p-0">
                <div class = "row">
                    <div class = "col-md-6">
                        <div class="card text-center">
                            <h6 class = "mt-2">Flexhealth</h6>
                            <div><?php echo $flexhealth_providers; ?></div>
                        </div>
                    </div>
                    <div class = "col-md-6">
                        <div class="card text-center">
                            <h6 class = "mt-2">Direct hire</h6>
                            <div><?php echo $providers-$flexhealth_providers; ?></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-md-6 col-sm-6">
        <div class="card card-stats">
            <div class="card-header card-header-danger card-header-icon">
                <div class="card-icon">
                    <i class="material-icons">work</i>
                </div>
                <p class="card-category">Number of Jobs</p>
                <h3 class="card-title">0
                </h3>
            </div>
            <div class="card-footer d-block p-0">
                <div class = "row">
                    <div class = "col-md-4">
                        <div class="card text-center">
                            <h6 class = "mt-2">Ongoing</h6>
                            <div>0</div>
                        </div>
                    </div>
                    <div class = "col-md-4">
                        <div class="card text-center">
                            <h6 class = "mt-2">Finished</h6>
                            <div>0</div>
                        </div>
                    </div>
                    <div class = "col-md-4">
                        <div class="card text-center">
                            <h6 class = "mt-2">Canceled</h6>
                            <div>0</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
</div>
<div class = "row">
    <div class = "col-md-4">
        <div class="card">
            <div class="card-header card-header-rose">
                <h3 class="card-title">Transactions</h3>
                <p class="card-category">All Transaction Value</p>
            </div>
            <div class="card-body">
                <p class="">Number:&nbsp;&nbsp;<span><?php echo $transCnt; ?></span></p>
                <p class="">Amount:&nbsp;&nbsp;<span><?php echo $transAmount; ?></span></p>
            </div>
        </div>
    </div>
    <div class = "col-md-4">
        <div class="card">
            <div class="card-header card-header-warning">
                <h3 class="card-title">Refund Requests</h3>
                <p class="card-category">All Refund Requests</p>
            </div>
            <div class="card-body">
                <p class="">Solved:&nbsp;&nbsp;<span><?php echo $refundsolved; ?></span></p>
                <p class="">Pending:&nbsp;&nbsp;<span><?php echo $refundpending; ?></span></p>
            </div>
        </div>
    </div>
    <div class = "col-md-4">
        <div class="card">
            <div class="card-header card-header-info">
                <h3 class="card-title">Scheduled Interviews</h3>
                <p class="card-category">All Interviews</p>
            </div>
            <div class="card-body">
                <p class="">Number:&nbsp;&nbsp;<span><?php echo $interview; ?></span></p>
            </div>
        </div>
    </div>
</div>
<div class = "row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header card-header-icon card-header-rose">
                <div class="card-icon">
                    <i class="material-icons">assignment</i>
                </div>
                <h4 class="card-title ">User Requests</h4>
            </div>
            <div class="card-body">
            <div class="table-responsive">
                <table class="table" id = "requesttable">
                    <thead class=" text-primary">
                        <th>User</th>
                        <th>Request Comment</th>
                        <th>Action</th>
                    </thead>
                    <tbody>
                        @foreach($requests as $requests_array)
                        <tr id = "{{$requests_array->id}}">
                            <td>{{$requests_array->fname}} {{$requests_array->lname}}</td>
                            <td>{{$requests_array->othertext}}</td>
                            <td><span class = 'reply_request <?php echo $requests_array->flag == 1?'requestdone':'requestbefore' ?>' userid = "{{$requests_array->client}}"><i class = 'fa fa-envelope'></i></span></td>
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
<div class="modal fade" id="reply_modal">
    <div class="modal-dialog">
        <div class="modal-content">
        
            <!-- Modal Header -->
            <div class="modal-header">
                    <h4 class="modal-title" style="font-weight:500;">Reply To User</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form id = 'replyrequest' action="{{url('admin/replyrequest')}}" method="POST">
            <!-- Modal body -->
            <div class="modal-body">
                    {{ csrf_field() }}
                    <div class = "row">
                        <div class = "col-md-12">   
                            <div class="form-group">
                                <label for="desc">Please write comment</label>
                                <textarea class="form-control" name = "desc" id="desc" rows="5"></textarea>
                            </div>
                        </div>
                    </div>
                    <input type = "hidden" name = 'userid' id = "userid" />
                    <input type = "hidden" name = 'requestid' id = "requestid" />
            </div>
            <!-- Modal footer -->
            <div class="modal-footer">
                    <button type="submit" class="btn btn-primary btn-link">Done</button>
                    <button type="button" class="btn btn-danger btn-link" data-dismiss="modal">Close</button>
            </div>
            </form>
        </div>
    </div>
</div>
<script>
    $('#requesttable').DataTable({
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
    $(document).ready(function(){
        $('.reply_request').click(function(){
            $("#userid").val($(this).attr('userid'));
            $("#requestid").val($(this).parent().parent().attr('id'));
            $("#reply_modal").modal('show');
        });
    });
</script>