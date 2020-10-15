
<div class = 'row'>
    <div class = "col-md-6">
        <h3><?php echo $name." - All Requested Serives"; ?></h3>
    </div>
    <div class = "col-md-6 text-right">
        <a href="{{route('usermanagement')}}"><button class = "btn btn-info">Back</button></a>
    </div>
</div>
<div class = "row">
    @foreach($requestservice as $requestservice_array)
    <div class = 'col-md-12'>
        <div class = "servicepanel">
            <div class = 'row'>
                <div class = 'col-md-6'>
                    <div class = 'requestservice'>
                        <?php if($requestservice_array->type == 1): ?>
                            Caregiver
                        <?php elseif($requestservice_array->type == 2): ?>
                            Nursing
                        <?php else: ?>
                            Therapy
                        <?php endif ?>
                    </div>
                </div>
                <div class = 'col-md-6'>
                    <div class = 'requestservicedate text-right'>
                        {{date("D, M j Y",strtotime($requestservice_array->created_at))}}
                    </div>
                </div>
            </div>
            <div class = 'row'>
                <div class = 'col-md-12'>
                    <?php 
                    if($requestservice_array->service == "0"){
                        echo "<h6 class = 'item_property'>Other Request - ".$requestservice_array->othertext."</h6>";
                    }
                    else{
                        $serviceitems = explode("<||>",$requestservice_array->sname);
                        $service = "";
                        for($i = 0;$i < count($serviceitems); $i++){
                            $service .= "<h6 class = 'item_property'>".$serviceitems[$i]."</h6>";
                        }
                        echo $service;
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>