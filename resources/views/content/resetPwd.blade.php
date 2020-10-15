
    <style>
        @media (max-width: 991px){
            .navbar-collapse:after {
                background-color: transparent!important;
            }
        }
        
    </style>
<nav class="navbar navbar-expand-lg navbar-transparent navbar-absolute fixed-top text-white">
    <div class="container">
        <div class="navbar-wrapper">
            <a class="navbar-brand" href="{{url('/admin')}}"><img class = "logo_img" src = "{{asset('adminassets/img/logo.png') }}" /></a>
        </div>
        <button class="navbar-toggler" type="button" data-toggle="collapse" aria-controls="navigation-index" aria-expanded="false" aria-label="Toggle navigation">
            <span class="sr-only">Toggle navigation</span>
            <span class="navbar-toggler-icon icon-bar"></span>
            <span class="navbar-toggler-icon icon-bar"></span>
            <span class="navbar-toggler-icon icon-bar"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-end">
        <ul class="navbar-nav">
            <li class="nav-item  active ">
            <a href="{{url('/admin/register')}}" class="nav-link">
                <i class="material-icons">person_add</i>
                Register
            </a>
            </li>
            <li class="nav-item ">
            <a href="{{url('/admin')}}" class="nav-link">
                <i class="material-icons">fingerprint</i>
                Login
            </a>
            </li>
        </ul>
        </div>
    </div>
</nav>
<div class="wrapper wrapper-full-page">
    <div class="page-header register-page header-filter" filter-color="black">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 col-md-6 col-sm-8 ml-auto mr-auto">
                    <form class="form" action="{{url('admin/resetPwdForm')}}" method="POST" id = "resetPwdForm">
                    {{ csrf_field() }}
                        <input type = "hidden" name = "token" value="<?php echo $token; ?>" />
                        <div class="card card-register card-hidden">
                            <div class="card-header card-header-primary">
                                <h4 class="card-title">Reset Password</h4>
                                <p class = "card-category">Please Enter Your Password</p>
                            </div>
                            <div class="card-body">
                                <span class="bmd-form-group">
                                    <div class="input-group mt-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                            <i class="material-icons">lock_outline</i>
                                            </span>
                                        </div>
                                        <input name = "password" id = "password" type="password" class="form-control" placeholder="Password..." required>
                                    </div>
                                </span>
                                <span class="bmd-form-group">
                                    <div class="input-group mt-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                            <i class="material-icons">lock_outline</i>
                                            </span>
                                        </div>
                                        <input name = "cpassword" id = "cpassword" type="password" class="form-control" placeholder="Confirm Password..." required>
                                    </div>
                                </span>
                            </div>
                            <div class="card-footer justify-content-center">
                                <button type = "button" class="btn btn-primary btn-link btn-lg resetpwdbtn">Get Started</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@if ($errors->any())
    <script>
        $.notify({
            icon: "add_alert",
            message: "This Email has been already used in our system. Please try again."

            }, {
            type: 'info',
            timer: 1000,
            placement: {
                from: 'top',
                align: 'center'
            }
        });
    </script>
@endif
<script>
    $(document).ready(function() {
        md.checkFullPageBackgroundImage();
        setTimeout(function() {
            $('.card').removeClass('card-hidden');
        }, 700);
        $(".resetpwdbtn").click(function(){
            if($("#password").val().length < 8){
                $.notify({
                    icon: "add_alert",
                    message: "Password must be at least 8 characters."

                    }, {
                    type: 'info',
                    timer: 1000,
                    placement: {
                        from: 'top',
                        align: 'center'
                    }
                });
            }
            else if($("#password").val() != $("#cpassword").val()){
                $.notify({
                    icon: "add_alert",
                    message: "Please Confirm Your Password."

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
                $("#resetPwdForm").submit();
            }
        });
    });
</script>
