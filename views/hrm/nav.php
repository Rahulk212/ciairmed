<header class="main-header">   
    <style>
        .dropdown-submenu {
            position: relative;
        }

        .dropdown-submenu>.dropdown-menu {
            top: 0;
            left: 100%;
            margin-top: -6px;
            margin-left: -1px;
            -webkit-border-radius: 0 6px 6px 6px;
            -moz-border-radius: 0 6px 6px;
            border-radius: 0 6px 6px 6px;
        }

        .dropdown-submenu:hover>.dropdown-menu {
            display: block;
        }

        .dropdown-submenu>a:after {
            display: block;
            content: " ";
            float: right;
            width: 0;
            height: 0;
            border-color: transparent;
            border-style: solid;
            border-width: 5px 0 5px 5px;
            border-left-color: #ccc;
            margin-top: 5px;
            margin-right: -10px;
        }

        .dropdown-submenu:hover>a:after {
            border-left-color: #fff;
        }

        .dropdown-submenu.pull-left {
            float: none;
        }

        .dropdown-submenu.pull-left>.dropdown-menu {
            left: -100%;
            margin-left: 10px;
            -webkit-border-radius: 6px 0 6px 6px;
            -moz-border-radius: 6px 0 6px 6px;
            border-radius: 6px 0 6px 6px;
        }
        .skin-red .main-header li.user-header{background: #f9f9f9; border-bottom: 1px solid #ccc;}
        .navbar-nav > .user-menu > .dropdown-menu > li.user-header > p{color:#444; font-size:22px;font-weight:500;}
        .navbar-nav > .user-menu > .dropdown-menu > li.user-header > h3{color:#444; font-size:18px; font-weight:normal !important;text-transform:uppercase;}
        .navbar-nav > .user-menu > .dropdown-menu > li.user-header{height:130px;}
        .user-footer a i{margin-right:10px;}
        .user-footer .prfl_red_btn{background-color: #dd4b39; border-color: #dd4b39; color: #fff !important;}
        .user-footer .prfl_red_btn:hover{background-color: #C64536 !important; border-color: #C64536;}
        .navbar-nav > li > .dropdown-menu{ box-shadow: 1px 1px 10px #000 !important;}


    </style>
    <nav class="navbar navbar-static-top">
        <div class="">
            <div class="navbar-header">
                <a href="<?php echo base_url(); ?>hrm/admin/dashboard" class="navbar-brand" style="
                   padding-top: 0px;
                   padding-bottom: 0px;
                   "><img src="<?php echo base_url(); ?>user_assets/images/logo1.png" class="logo" alt="AirmedLabs" style="width:187px;height:50px"></a>
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse">
                    <i class="fa fa-bars"></i>
                </button>
            </div>

            <!-- Collect the nav links, forms, and other content for toggling -->

            <div class="collapse navbar-collapse pull-left" id="navbar-collapse">
                <ul class="nav navbar-nav">
                    <li><a href="<?php echo base_url(); ?>hrm/admin/dashboard">Dashboard</a></li>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown"> HRM<span class="caret"></span></a>
                            <ul class="dropdown-menu" role="menu">
                                <li><a href="<?php echo base_url(); ?>hrm/department"><i class="fa fa-briefcase"></i> Department </a></li>
                                <li><a href="<?php echo base_url(); ?>hrm/employee"><i class="fa fa-users"></i> Employee </a></li>
                                <li><a href="<?php echo base_url(); ?>hrm/award"><i class="fa fa-trophy"></i> Award </a></li>
                                <li><a href="<?php echo base_url(); ?>hrm/expense"><i class="fa fa-trophy"></i> Expense </a></li>
                                <li><a href="<?php echo base_url(); ?>hrm/holiday"><i class="fa fa-send"></i> Holiday </a></li>
                                <li><a href="<?php echo base_url(); ?>hrm/notice_board"><i class="fa fa-clipboard"></i> Notice Board </a></li>
                                <li><a href="<?php echo base_url(); ?>hrm/leave_application"><i class="fa fa-rocket"></i> Leave Application </a></li>
                                <li class="dropdown-submenu">
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user"></i> Attendance</a>
                                    <ul class="dropdown-menu" style="float:left;">
                                        <li><a href="<?php echo base_url(); ?>hrm/attendance/mark_attendance"><i class="fa fa-check"></i> Mark Attendance </a></li>
                                        <li><a href="<?php echo base_url(); ?>hrm/attendance/leave_types"><i class="fa fa-sitemap"></i> Leave Types </a></li>
                                    </ul>
                                </li>
                            </ul>
                        </li>
                        <li><a href="<?php echo base_url(); ?>login/logout">Sign out</a></li>
            </div><!-- /.navbar-collapse -->
            <!-- Navbar Right Menu -->
            <div class="navbar-custom-menu">
                <ul class="nav navbar-nav">
                    <!-- Messages: style can be found in dropdown.less-->



                    <!-- User Account Menu -->
                       <li class="dropdown user user-menu">
                        <!-- Menu Toggle Button -->
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <!-- The user image in the navbar-->
                            <img src="<?php echo base_url(); ?>user_assets/images/user.png"" class="user-image" alt="User Image"/>
                            <!-- hidden-xs hides the username on small devices so only the image appears. -->
                            <span class="hidden-xs"><?php echo ucfirst($login_data["name"]); ?></span>
                        </a>
                        <ul class="dropdown-menu">
                            <!-- The user image in the menu -->
                            <li class="user-header">
                                <!----<img src="<?php echo base_url(); ?>user_assets/images/logo1.png" class="" style="  width: 100%;" alt="User Image" />---->
                                <p>
                                    <?php echo ucfirst($login_data["name"]); ?>
                                </p>
                            </li>
                            <!-- Menu Body -->

                            <!-- Menu Footer-->
                            <li class="user-footer">
<!--                                <div class="pull-left">
                                    <a href="<?php echo base_url(); ?>admin-profile-update" class="btn btn-default btn-flat prfl_red_btn"><i class="fa fa-briefcase"></i>Profile</a>
                                </div>-->
                                <div class="pull-right">
                                    <a href="<?php echo base_url(); ?>hrm_login/logout" class="btn btn-default btn-flat prfl_red_btn"><i class="fa fa-power-off"></i>Sign out</a>
                                </div>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div><!-- /.navbar-custom-menu -->
        </div><!-- /.container-fluid -->
    </nav>
</header>
<!-- Full Width Column -->
<div class="content-wrapper">
    <div class="">
