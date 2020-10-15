<?php 
    function MakeTime($value){
        $value = $value + 1;
        if($value == 12)
            return "12 PM";
        if($value == 24)
            return "12 AM";
        if($value > 12)
            return ($value - 12)." PM";
        return $value." AM";
    }
?>
<div class = 'row'>
    <div class = "col-md-6">
        <h3><?php echo $name." - All Requested Services"; ?></h3>
    </div>
    <div class = "col-md-6 text-right">
        <a href="{{route('providermanagement')}}"><button class = "btn btn-info">Back</button></a>
    </div>
</div>
<div class = "row">
    @foreach($jobdetail as $jobdetail_array)
    <div class = 'col-md-12'>
        <div class = "servicepanel">
            <div class = 'row'>
                <div class = 'col-md-6'>
                    <div class = 'requestservice'>
                        Job ID: {{$jobdetail_array->jobid}}
                    </div>
                </div>
                <div class = 'col-md-6'>
                    <div class = 'requestservicedate text-right'>
                        {{date("D, M j Y",strtotime($jobdetail_array->created_at))}}
                    </div>
                </div>
            </div>
            <div class = 'row'>
                <div class = 'col-md-2'>
                    <h6 class = 'item_property'>{{date("m/d/Y",strtotime($jobdetail_array->start))}} ~ {{date("m/d/Y",strtotime($jobdetail_array->end))}}</h6>
                </div>
                <div class = 'col-md-2'>
                    <h6 class = 'item_property'>{{MakeTime($jobdetail_array->starttime)}} ~ {{MakeTime($jobdetail_array->endtime)}}</h6>
                </div>
                <div class = 'col-md-2'>
                    <h6 class = 'item_property'>Amount: $ {{$jobdetail_array->amount}}</h6>
                </div>
                <div class = 'col-md-2'>
                    <?php 
                        if($jobdetail_array->status == 1){
                            $jobtype = "Ongoing Job";
                        }
                        else if($jobdetail_array->status == 2){
                            $jobtype = "Finished Job";
                        }
                        else if($jobdetail_array->status == 3){
                            $jobtype = "Canceled Job";
                        }
                        else{
                            $jobtype = "";
                        }
                    ?>
                    <h6 class = 'item_property'><?php echo $jobtype; ?></h6>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>