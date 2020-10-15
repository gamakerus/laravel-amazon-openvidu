<nav class="navbar navbar-expand-lg navbar-transparent navbar-absolute fixed-top text-white">
        <div class="container">
            <div class="navbar-wrapper">
                <a class="navbar-brand" href="{{url('/admin')}}"><img class = "logo_img" src = "{{asset('adminassets/img/logo.png') }}" /></a>
            </div>
        </div>
    </nav>
    <!-- End Navbar -->
    <div class="wrapper wrapper-full-page">
        <div class="page-header squestion-page header-filter" filter-color="black">
        <!--   you can change the color of the filter page using: data-color="blue | purple | green | orange | red | rose " -->
        <div class="container">
            <div class="row">
                <div class="col-lg-4 col-md-6 col-sm-8 ml-auto mr-auto">
                    <form class="form" action="{{url('admin/squestionForm')}}" method="POST" id="squestionForm">
                    {{ csrf_field() }}
                        <div class="card card-squestion card-hidden">
                            <div class="card-header card-header-primary">
                                <h4 class="card-title">Security Question</h4>
                                <p class = "card-category">Please Enter Your Answer</p>
                            </div>
                            <div class="card-body">
                                <p class="card-description text-center text-primary">{{$question->question}}</p>
                                <span class="bmd-form-group">
                                    <div class="input-group mt-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                            <i class="material-icons">record_voice_over</i>
                                            </span>
                                        </div>
                                        <input name = "answer" id = "answer" type="text" class="form-control" placeholder="Answer..." required>
                                    </div>
                                </span>
                                <input type = "hidden" name = "remember_token" value = "{{Session::get('remember_token')}}" />
                                <input type = "hidden" name = "questionid" value = "{{$question->id}}" />
                            </div>
                            <div class="card-footer justify-content-center">
                                <button type = "button" class="btn btn-primary btn-link btn-lg squestionbtn">Lets Go</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            md.checkFullPageBackgroundImage();
            setTimeout(function() {
                $('.card').removeClass('card-hidden');
            }, 700);
            $(".squestionbtn").click(function(){
                if($("#answer").val() == ""){
                    $.notify({
                        icon: "add_alert",
                        message: "Please Enter Your Answer."

                        }, {
                        type: 'info',
                        timer: 1000,
                        placement: {
                            from: 'top',
                            align: 'center'
                        }
                    });
                }
                else{
                    $("#squestionForm").submit();
                }
            });
        });
    </script>
