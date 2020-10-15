<div class="sidebar" data-color="purple" data-background-color="white" data-image="../adminassets/img/sidebar-1.jpg">
    <div class="logo"><a href="{{route('dashboard')}}" class="simple-text logo-normal">
        <img class = "logo_img" src = "{{asset('adminassets/img/logo.png') }}" />
    </a></div>
    <div class="sidebar-wrapper">
    <ul class="nav">
        <li class="nav-item <?php echo $pagename=='dashboard'?'active':'';?>">
        <a class="nav-link" href="{{route('dashboard')}}">
            <i class="material-icons">dashboard</i>
            <p>Dashboard</p>
        </a>
        </li>
        <li class="nav-item <?php echo $pagename=='user'?'active':'';?>">
        <a class="nav-link" href="{{route('usermanagement')}}">
            <i class="material-icons">people_alt</i>
            <p>Users Management</p>
        </a>
        </li>
        <li class="nav-item <?php echo $pagename=='provider'?'active':'';?>">
        <a class="nav-link" href="{{route('providermanagement')}}">
            <i class="material-icons">person</i>
            <p>Providers Management</p>
        </a>
        </li>
        <li class="nav-item <?php echo $pagename=='job'?'active':'';?>">
        <a class="nav-link" href="{{route('jobmanagement')}}">
            <i class="material-icons">work</i>
            <p>Jobs Management</p>
        </a>
        </li>
        <li class="nav-item <?php echo $pagename=='transaction'?'active':'';?>">
        <a class="nav-link" href="{{route('transaction')}}">
            <i class="material-icons">monetization_on</i>
            <p>Transactions</p>
        </a>
        </li>
        <li class="nav-item <?php echo $pagename=='cancelrefund'?'active':'';?>">
        <a class="nav-link" href="{{route('cancelrefund')}}">
            <i class="material-icons">redo</i>
            <p>Cancellation & Refunds</p>
        </a>
        </li>
        <li class="nav-item ">
            <a class="nav-link <?php echo ($pagename=='notification' || $pagename=='message' || $pagename=='email')?"subactive":"";?>" data-toggle="collapse" href="#pushMmenu" aria-expanded="<?php echo ($pagename=='notification' || $pagename=='message' || $pagename=='email')?"true":"false";?>">
            <i class="material-icons">notifications</i>
            <p> Push Management
                <b class="caret"></b>
            </p>
            </a>
            <div class="collapse <?php echo ($pagename=='notification' || $pagename=='message' || $pagename=='email')?"show":"";?>" id="pushMmenu">
                <ul class="nav">
                    <li class="nav-item <?php echo $pagename=='notification'?'active':'';?>">
                        <a class="nav-link subnav-item" style = "box-shadow: unset!important;" href="{{route('notification')}}">
                            <span class="sidebar-mini">N</span>
                            <span class="sidebar-normal">Notifications</span>
                        </a>
                    </li>
                    <li class="nav-item <?php echo $pagename=='message'?'active':'';?>">
                        <a class="nav-link subnav-item" style = "box-shadow: unset!important;" href="{{route('message')}}">
                            <span class="sidebar-mini">M</span>
                            <span class="sidebar-normal">Messages</span>
                        </a>
                    </li>
                    <li class="nav-item <?php echo $pagename=='email'?'active':'';?>">
                        <a class="nav-link subnav-item" style = "box-shadow: unset!important;" href="{{route('email')}}">
                            <span class="sidebar-mini">E</span>
                            <span class="sidebar-normal">Emails</span>
                        </a>
                    </li>
                </ul>
            </div>
        </li>
        @if (Session::get('roles') == 1)
        <li class="nav-item <?php echo $pagename=='manager'?'active':'';?>" style = "margin-top: 50px;border-top: 1px solid #eee;">
        <a class="nav-link" href="{{route('manager')}}">
            <i class="material-icons">people_outline</i>
            <p>Managers</p>
        </a>
        </li>
        <li class="nav-item ">
            <a class="nav-link <?php echo ($pagename=='squestion' || $pagename=='services' || $pagename=='ptrelation' || $pagename=='language' || $pagename=='exspec' || $pagename=='licenses' || $pagename=='schedule' || $pagename=='osettings')?"subactive":"";?>" data-toggle="collapse" href="#settingmenu" aria-expanded="<?php echo ($pagename=='squestion' || $pagename=='services' || $pagename=='ptrelation' || $pagename=='language' || $pagename=='exspec' || $pagename=='licenses' || $pagename=='schedule' || $pagename=='osettings')?"true":"false";?>">
            <i class="material-icons">settings</i>
            <p> Settings
                <b class="caret"></b>
            </p>
            </a>
            <div class="collapse <?php echo ($pagename=='squestion' || $pagename=='services' || $pagename=='ptrelation' || $pagename=='language' || $pagename=='exspec' || $pagename=='licenses' || $pagename=='schedule' || $pagename=='osettings')?"show":"";?>" id="settingmenu">
                <ul class="nav">
                    <li class="nav-item <?php echo $pagename=='squestion'?'active':'';?>">
                        <a class="nav-link subnav-item" style = "box-shadow: unset!important;" href="{{route('squestion')}}">
                            <span class="sidebar-mini">SQ</span>
                            <span class="sidebar-normal">Security Question</span>
                        </a>
                    </li>
                    <li class="nav-item <?php echo $pagename=='services'?'active':'';?>">
                        <a class="nav-link subnav-item" style = "box-shadow: unset!important;" href="{{route('services')}}">
                            <span class="sidebar-mini">S</span>
                            <span class="sidebar-normal">Services</span>
                        </a>
                    </li>
                    <li class="nav-item <?php echo $pagename=='ptrelation'?'active':'';?>">
                        <a class="nav-link subnav-item" style = "box-shadow: unset!important;" href="{{route('ptrelation')}}">
                            <span class="sidebar-mini">PR</span>
                            <span class="sidebar-normal">Patient Relation</span>
                        </a>
                    </li>
                    <li class="nav-item <?php echo $pagename=='language'?'active':'';?>">
                        <a class="nav-link subnav-item" style = "box-shadow: unset!important;" href="{{route('language')}}">
                            <span class="sidebar-mini">L</span>
                            <span class="sidebar-normal">Language</span>
                        </a>
                    </li>
                    <li class="nav-item <?php echo $pagename=='exspec'?'active':'';?>">
                        <a class="nav-link subnav-item" style = "box-shadow: unset!important;" href="{{route('exspec')}}">
                            <span class="sidebar-mini">ES</span>
                            <span class="sidebar-normal">Expertise & Specializations</span>
                        </a>
                    </li>
                    <li class="nav-item <?php echo $pagename=='licenses'?'active':'';?>">
                        <a class="nav-link subnav-item" style = "box-shadow: unset!important;" href="{{route('licenses')}}">
                            <span class="sidebar-mini">L</span>
                            <span class="sidebar-normal">Licenses</span>
                        </a>
                    </li>
                    <!-- <li class="nav-item <?php echo $pagename=='schedule'?'active':'';?>">
                        <a class="nav-link subnav-item" style = "box-shadow: unset!important;" href="{{route('schedule')}}">
                            <span class="sidebar-mini">SS</span>
                            <span class="sidebar-normal">Schedule Settings</span>
                        </a>
                    </li> -->
                    <li class="nav-item <?php echo $pagename=='osettings'?'active':'';?>" style = "padding-bottom:100px">
                        <a class="nav-link subnav-item" style = "box-shadow: unset!important;" href="{{route('osettings')}}">
                            <span class="sidebar-mini">OS</span>
                            <span class="sidebar-normal">Other Settings</span>
                        </a>
                    </li>
                </ul>
            </div>
        </li>
        @endif
    </ul>
    </div>
</div>
<input class = "base_url" type = "hidden" value = "{{asset('/') }}" />
<script>
    $('.sidebar .sidebar-wrapper, .main-panel').perfectScrollbar();
</script>