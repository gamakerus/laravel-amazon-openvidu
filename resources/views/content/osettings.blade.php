<style>
    #coverimg {
        display: block;
        max-width: 100%;
    }
    .cropper-container{
        max-width:100%!important;
        overflow: hidden;
        max-height: 350px;
    }
    .modal-lg{
        max-width: 1000px !important;
    }
</style>
<div class = 'row'>
    <div class="col-md-4">
        <div class = 'row'>
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header card-header-icon card-header-primary no-print">
                        <div class="card-icon">
                        <i class="material-icons">search</i>
                        </div>
                        <div class = 'row'>
                            <div class = 'col-md-6'><h4 class="card-title ">Search Radius</h4></div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group bmd-form-group">
                                    <label class="bmd-label-static">Radius (Mile)</label>
                                    <input type="text" class="form-control radiusvalue" disabled="" value = "<?php echo $radiusvalue; ?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <button type="button" class="btn btn-info btn-link resetbtn" data-dismiss="modal">Reset</button>
                                <button type="button" class="btn btn-success btn-link radiussavebtn d-none" data-dismiss="modal">Save</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header card-header-icon card-header-primary no-print">
                        <div class="card-icon">
                        <i class="material-icons">image</i>
                        </div>
                        <div class = 'row'>
                            <div class = 'col-md-6'><h4 class="card-title ">Ads Cover Image</h4></div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class = "col-md-12">
                                <div class="form-group">
                                    <label for="license_status">Choose Cover Image</label>
                                    <div class="custom-file form-group bmd-form-group dg-input">
                                        <input type="file" class="custom-file-input form-control coverimg" id="editfile" name="file">
                                        <label class="custom-file-label" for="editfile">Choose file</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class = 'row'>
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header card-header-icon card-header-primary no-print">
                        <div class="card-icon">
                        <i class="material-icons">descriptions</i>
                        </div>
                        <div class = 'row'>
                            <div class = 'col-md-6'><h4 class="card-title ">Hire Type Description</h4></div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                            <form id = 'updateflexdesc' action="{{url('admin/updateflexdesc')}}" method="POST">
                                {{ csrf_field() }}
                                <div class="form-group bmd-form-group">
                                    <label for="desc" class="bmd-label-static">FLEXHEALTH</label>
                                    <textarea class="form-control" name="desc" rows="10"><?php echo $flexdesc; ?></textarea>
                                </div>
                                <button type="submit" class="btn btn-primary" >Save</button>
                            </form>
                            </div>
                            <div class="col-md-6">
                            <form id = 'updateflexdesc' action="{{url('admin/updatedirectdesc')}}" method="POST">
                                {{ csrf_field() }}
                                <div class="form-group bmd-form-group">
                                    <label for="desc" class="bmd-label-static">DIRECT HIRE</label>
                                    <textarea class="form-control" name="desc" rows="10"><?php echo $directdesc; ?></textarea>
                                </div>
                                <button type="submit" class="btn btn-primary">Save</button>
                            </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="coverimg_model" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="img-container">
            <div class="row">
                <div class="col-md-12">
                    <img id="coverimage" src="https://avatars0.githubusercontent.com/u/3456749">
                </div>
            </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-primary" id="crop">Save</button>
      </div>
    </div>
  </div>
</div>
<script>
    $(document).ready(function() {
        var tmpcnt = 0;
        $(".resetbtn").click(function(){
            if(tmpcnt == 0){
                $(".radiusvalue").prop('disabled',false);
                $(".radiussavebtn").removeClass('d-none');
                $(this).addClass('d-none');
                tmpcnt = 1;
            }
        });
        $(".radiussavebtn").click(function(){
            if(tmpcnt == 1){
                $(".radiusvalue").prop('disabled',true);
                $(".resetbtn").removeClass('d-none');
                $(this).addClass('d-none');
                tmpcnt = 0;
            }
            if($(".radiusvalue").val() == ""){
                Swal.fire({
                    text: "Please enter radius value",
                    type: 'warning',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'Done'
                });
            }
            else{
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, Save it!'
                }).then((result) => {
                    if (result.value) {
                        $.ajax({
                            type:'POST',
                            url:"{{ url('/admin/setRadius') }}",
                            data:{'_token':'<?php echo csrf_token() ?>',value:$(".radiusvalue").val()},
                            dataType:"json",
                            success:function(data){
                                if(data['success'] == 'success'){
                                    Swal.fire({
                                        text: "Save successfully",
                                        type: 'success',
                                        confirmButtonColor: '#3085d6',
                                        confirmButtonText: 'Done'
                                    });
                                }
                                else{
                                    Swal.fire({
                                        text: "Save failed",
                                        type: 'warning',
                                        confirmButtonColor: '#3085d6',
                                        confirmButtonText: 'Done'
                                    });
                                }
                                
                            }
                        });	  
                    }
                });
            }   
            
        });

        var image = document.getElementById('coverimage');
        var cropper;
        
        $("body").on("change", ".coverimg", function(e){
            var files = e.target.files;
            var done = function (url) {
            image.src = url;
            $('#coverimg_model').modal('show');
            };
            var reader;
            var file;
            var url;

            if (files && files.length > 0) {
            file = files[0];

            if (URL) {
                done(URL.createObjectURL(file));
            } else if (FileReader) {
                reader = new FileReader();
                reader.onload = function (e) {
                done(reader.result);
                };
                reader.readAsDataURL(file);
            }
            }
        });

        $('#coverimg_model').on('shown.bs.modal', function () {
            cropper = new Cropper(image, {
                preview: '.preview',
                ready: function (e) { 
                    $(this).cropper('setData', { 
                        height: 467,
                        rotate: 0,
                        scaleX: 1,
                        scaleY: 1,
                        width:  573,
                        x:      0,
                        y:      0
                    });
                }
            });
        }).on('hidden.bs.modal', function () {
            cropper.destroy();
            cropper = null;
        });
        $("#crop").click(function(){
            canvas = cropper.getCroppedCanvas({
                width: 1170,
                height: 380,
            });

            canvas.toBlob(function(blob) {
                url = URL.createObjectURL(blob);
                var reader = new FileReader();
                reader.readAsDataURL(blob); 
                reader.onloadend = function() {
                    var base64data = reader.result;	

                    $.ajax({
                        type: "POST",
                        dataType: "json",
                        url: "{{ url('/admin/updatecoverimg') }}",
                        data: {'_token': '<?php echo csrf_token() ?>', 'coverimg': base64data},
                        success: function(data){
                            $('#coverimg_model').modal('hide');
                            $.notify({
                                icon: "add_alert",
                                message: "Image uploaded successfully."

                                }, {
                                type: 'info',
                                timer: 1000,
                                placement: {
                                    from: 'top',
                                    align: 'center'
                                }
                            });
                        }
                    });
                }
            });
        })
    });
</script>