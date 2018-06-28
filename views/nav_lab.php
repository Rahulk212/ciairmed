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
    </style>
    <nav class="navbar navbar-static-top">
        <div class="">
            <div class="navbar-header">
                <a href="<?php echo base_url(); ?>Dashboard" class="navbar-brand">Dashboard</a>
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse">
                    <i class="fa fa-bars"></i>
                </button>
            </div>
            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse pull-left" id="navbar-collapse">
                <ul class="nav navbar-nav"> 
                        <li><a href="<?php echo base_url(); ?>Lab/sample_list">Sample Master</a></li>    
<!--                        <li><a href="<?php echo base_url(); ?>Logistic/lab_list">Lab Master</a></li>
                        <li><a href="<?php echo base_url(); ?>logistic_test_master/test_list">Test Master</a></li>-->
<!--                        <li><a href="<?php echo base_url(); ?>phlebo_master/phlebo_list">Logistic Master</a></li>
                        <li><a href="<?php echo base_url(); ?>test-master/price-list"> Price List</a></li>-->
                </ul>
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
                            <img src="<?php echo base_url(); ?>upload/01461246201nobody_m_original.jpg" class="user-image" alt="User Image"/>
                            <!-- hidden-xs hides the username on small devices so only the image appears. -->
                            <span class="hidden-xs"><?php echo ucfirst($login_data["name"]); ?></span>
                        </a>
                        <ul class="dropdown-menu">
                            <!-- The user image in the menu -->
                            <li class="user-header">
                                <img src="<?php echo base_url(); ?>upload/01461246201nobody_m_original.jpg" class="img-circle" alt="User Image" />
                                <p>
                                    <?php echo ucfirst($login_data["name"]); ?>
                                </p>
                            </li>
                            <!-- Menu Body -->
                            <!-- Menu Footer-->
                            <li class="user-footer">
                                <div class="pull-left">
<!--                                    <a href="<?php echo base_url(); ?>admin-profile-update" class="btn btn-default btn-flat">Profile</a>-->
                                </div>
                                <div class="pull-right">
                                    <a href="<?php echo base_url(); ?>Lab_login/logout" class="btn btn-default btn-flat">Sign out</a>
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
