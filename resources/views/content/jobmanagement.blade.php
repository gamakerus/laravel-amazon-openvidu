
<div class = "row justify-content-between">
    <div class="col-lg-3 col-md-6 col-sm-6">
        <div class="card card-stats">
            <div class="card-header card-header-primary card-header-icon">
                <div class="card-icon">
                    <i class="material-icons">work</i>
                </div>
                <p class="card-category">Number of Jobs</p>
                <h3 class="card-title"><?php echo $jobsCnt; ?>
                </h3>
            </div>
            <div class="card-footer">
                
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 col-sm-6">
        <div class="card card-stats">
            <div class="card-header card-header-info card-header-icon">
                <div class="card-icon">
                    <i class="material-icons">work</i>
                </div>
                <p class="card-category">Ongoing</p>
                <h3 class="card-title"><?php echo $ongoingjobs; ?>
                </h3>
            </div>
            <div class="card-footer">
                
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 col-sm-6">
        <div class="card card-stats">
            <div class="card-header card-header-warning card-header-icon">
                <div class="card-icon">
                    <i class="material-icons">work</i>
                </div>
                <p class="card-category">Finished</p>
                <h3 class="card-title"><?php echo $finishedjobs; ?>
                </h3>
            </div>
            <div class="card-footer">
               
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 col-sm-6">
        <div class="card card-stats">
            <div class="card-header card-header-danger card-header-icon">
                <div class="card-icon">
                    <i class="material-icons">work</i>
                </div>
                <p class="card-category">Canceled</p>
                <h3 class="card-title"><?php echo $canceledjobs; ?>
                </h3>
            </div>
            <div class="card-footer">
                
            </div>
        </div>
    </div>
</div>
<div class = 'row'>
    <div class="col-md-12">
        <div class="card">
        <div class="card-header card-header-icon card-header-primary no-print">
            <div class="card-icon">
            <i class="material-icons">work</i>
            </div>
            <div class = 'row'>
                <div class = 'col-md-6'><h4 class="card-title ">Job List</h4></div>
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
            <table class="table table-striped table-no-bordered table-hover" id = "jobtable">
                <thead class=" text-primary">
                    <th>Starting Date</th>
                    <th>Job ID</th>
                    <th>Patient Name</th>
                    <th>Provider Name</th>
                    <th>Service Type</th>
                    <th>Status</th>
                    <th>Action</th>
                </thead>
                <tbody>
                    @foreach($jobslist as $jobslist_array)
                        <tr id = "{{$jobslist_array->id}}">
                            <td>{{date("D, M j Y",strtotime($jobslist_array->created_at))}}</td>
                            <td>{{$jobslist_array->jobid}}</td>
                            <td>{{$jobslist_array->cfname." ".$jobslist_array->clname}}</td>
                            <td>{{$jobslist_array->pfname." ".$jobslist_array->plname}}</td>
                            <td>
                                <?php if($jobslist_array->service == 1): ?>
                                    <span class = 'jobservice'>Caregiver</span>
                                <?php elseif($jobslist_array->service == 2): ?>
                                    <span class = 'jobservice'>Nursing</span>
                                <?php else: ?>
                                    <span class = 'jobservice'>Therapy</span>
                                <?php endif ?>
                            </td>
                            <td>
                                <?php if($jobslist_array->status == 1): ?>
                                    <span class = 'jobstatus'>Ongoing&nbsp;&nbsp;<i class="fa fa-hourglass" aria-hidden="true"></i></span>
                                <?php elseif($jobslist_array->status == 2): ?>
                                    <span class = 'jobstatus'>Finished&nbsp;&nbsp;<i class="fa fa-check-circle" aria-hidden="true"></i></span>
                                <?php else: ?>
                                    <span class = 'jobstatus'>Canceled&nbsp;&nbsp;<i class="fa fa-times-circle-o" aria-hidden="true"></i></span>
                                <?php endif ?>
                            </td>
                            <td>
                                <span class = 'job_view'><i class = 'fa fa-eye'></i></span>&nbsp;<span class = 'job_delete'><i class = 'fa fa-trash'></i></span>
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
<div class="modal fade" id="job_detail_modal">
    <div class="modal-dialog">
            <div class="modal-content">
            
            <!-- Modal Header -->
            <div class="modal-header">
                    <h4 class="modal-title jobnamefordetail" style="font-weight:500;"></h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            
            <!-- Modal body -->
            <div class="modal-body">
                <div class = "row">
                    <div class = "col-md-12">
                        <div class = "row">
                            <div class = "col-md-6">
                                <span class = 'job_active jobtypeview'></span>
                            </div>
                        </div>
                        <div class = "row">
                            <div class = "col-md-12">
                                <h6>Job ID : <span class = "jobidtitle"></span></h6>
                                <h6>Start Date : <span class = "chosensdate"></span></h6>
                                <h6>End Date : <span class = "chosenedate"></span></h6>
                                <h6>Available Time : <span class = "chosentime"></span></h6>
                                <h6>Excluded Days : <span class = "chosenexdays"></span></h6>
                                <h6>Client : <span class = "chosenclient"></span></h6>
                                <h6>Provider : <span class = "chosenprovider"></span></h6>
                            </div>
                        </div>
                    </div>
                </div>
                <div class = "row mt-3">
                    <div class = "col-md-12">
                        <div class = "row">
                            <div class = "col-md-6">
                                <span class = 'job_active jobserviceview'></span>
                            </div>
                        </div>
                        <div class = "row">
                            <div class = "col-md-12">
                                <h6>Expertise & Specializations : <span class = "chosenexspec"></span></h6>
                                <h6>Licenses : <span class = "chosenlicense"></span></h6>
                                <h6>Provided activities : <span class = "chosenactivities"></span></h6>
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
    function MakeTime(value){
        value = parseInt(value) + 1;
        if(parseInt(value) == 12)
            return "12 PM";
        if(parseInt(value) == 24)
            return "12 AM";
        if(parseInt(value) > 12)
            return (parseInt(value) - 12)+" PM";
        return parseInt(value)+" AM";
    }
    $(document).ready(function() {
        
        $('.job_delete').click(function(){
            select_job = $(this).parent().parent();
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
                            url:"{{ url('/admin/deletejob') }}",
                            data:{'_token':'<?php echo csrf_token() ?>',id:select_job.attr('id')},
                            success:function(data){
                                if(data['success'] == 'success'){
                                    Swal.fire({
                                        text: "Delete successfully",
                                        type: 'success',
                                        confirmButtonColor: '#3085d6',
                                        confirmButtonText: 'Done'
                                    }).then((result) => {
                                        select_job.remove();
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
       
        $('#jobtable').DataTable({
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
        $(".job_view").click(function(){
            select_job = $(this).parent().parent();
            $(".jobnamefordetail").html($(this).parent().parent().children().eq(4).html()+" - "+$(this).parent().parent().children().eq(5).html());
            $(".jobidtitle").html($(this).parent().parent().children().eq(1).html());
            $(".chosenclient").html($(this).parent().parent().children().eq(2).html());
            $(".chosenprovider").html($(this).parent().parent().children().eq(3).html());
            
            $.ajax({
                type:'POST',
                url:"{{ url('/admin/chosenjob') }}",
                data:{'_token':'<?php echo csrf_token() ?>',id:select_job.attr('id')},
                dataType:"json",
                success:function(data){
                    var sdate = new Date(data[0]['start']);
                    $(".chosensdate").html(((sdate.getMonth() > 8) ? (sdate.getMonth() + 1) : ('0' + (sdate.getMonth() + 1))) + '/' + ((sdate.getDate() > 9) ? sdate.getDate() : ('0' + sdate.getDate())) + '/' + sdate.getFullYear());
                    var edate = new Date(data[0]['end']);
                    $(".chosenedate").html(((edate.getMonth() > 8) ? (edate.getMonth() + 1) : ('0' + (edate.getMonth() + 1))) + '/' + ((edate.getDate() > 9) ? edate.getDate() : ('0' + edate.getDate())) + '/' + edate.getFullYear());
                    $(".chosentime").html((data[0]['starttime'] == "24"?"Live in":MakeTime(data[0]['starttime'])+" ~ "+MakeTime(data[0]['endtime'])));
                    $(".chosenexspec").empty();
                    for(var i = 0;i < data[2].length;i++){
                        $(".chosenexspec").append("<span class = 'item_property'>"+data[1][i]['name']+"</span>");
                    }
                    $(".chosenactivities").empty();
                    for(var i = 0;i < data[3].length;i++){
                        $(".chosenactivities").append("<span class = 'item_property'>"+data[2][i]['name']+"</span>");
                    }
                    $(".chosenlicense").empty();
                    for(var i = 0;i < data[3].length;i++){
                        $(".chosenlicense").append("<span class = 'item_property'>"+data[3][i]['name']+"</span>");
                    }
                    $(".chosenexdays").empty();
                    if(data[4].length == 0){
                        $(".chosenexdays").append("No days");
                    }
                    else{
                        for(var i = 0;i < data[4].length;i++){
                            $(".chosenexdays").append("<span class = 'item_property'>"+data[4][i]+"</span>");
                        }
                    }
                }
            });
            $("#job_detail_modal").modal("show");
        });
        $(".downloadtype").change(function(){
            if($(this).val() == 1){
                $('body').append("<form id = 'expertCSV' action='{{ url('/admin/expertCSVforjob') }}' method='get'>"+'{{ csrf_field() }}'+"</form>");
                $('#expertCSV').submit();
            }
            else if($(this).val() == 2){
                $('body').append("<form id = 'downloadPDF' action='{{ url('/admin/downloadPDFforjob') }}' method='get'>"+'{{ csrf_field() }}'+"</form>");
                $('#downloadPDF').submit();
            }
            $(this).val(0);
            $(this).selectpicker('refresh');
        });
    });
</script>