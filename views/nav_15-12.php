<header class="main-header">               
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

                    <?php if ($login_data["type"] == 1) { ?>
                        <li class="active"><a href="<?php echo base_url(); ?>job-master/pending-list"> All Jobs <span id="pending_count" class="label label-danger"> </span> <span class="sr-only">(current)</span></a></li>
                        <li class="active"><a href="<?php echo base_url(); ?>job-master/prescription-report"> Prescription <span id="prescription_count" class="label label-danger"> <span class="sr-only">(current)</span></a></li>
                        <?php /* <li ><a href="<?php echo base_url(); ?>job_master/Package_test_inquiry_list">All Inquiries <span id="test_package_count" class="label label-danger"> </span><span class="sr-only">(current)</span></a></li> */ ?>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown"> Web Inquiries <span id="inquiry_total" class="label label-danger"> </span> <span class="caret"></span></a>
                            <ul class="dropdown-menu" role="menu">
                                <li ><a href="<?php echo base_url(); ?>job_master/Package_test_inquiry_list">All Inquiries <span id="test_package_count" class="label label-danger"> </span><span class="sr-only">(current)</span></a></li>
                                <li ><a href="<?php echo base_url(); ?>customer_master/Package_inquiry_list"> Package Inquiry  <span id="package_inquiry" class="label label-danger"> </span><span class="sr-only">(current)</span></a></li>

                            </ul>

                        </li>

                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown"> Master <span class="caret"></span></a>
                            <ul class="dropdown-menu" role="menu">
                                <li><a href="<?php echo base_url(); ?>Admin_manage/user_list"> Admin User Manage </a></li>
                                <li><a href="<?php echo base_url(); ?>test-master/test-list"> Test Master </a></li>
                                <li><a href="<?php echo base_url(); ?>parameter_master/parameter_list"> Test Parameter </a></li>
                                <li><a href="<?php echo base_url(); ?>Branch_Master/Branch_list">Branch Master</a></li>
                                <li><a href="<?php echo base_url(); ?>location-master/state-list">State</a></li>
                                <li><a href="<?php echo base_url(); ?>location-master/city-list">City</a></li>
                                <!--<li><a href="<?php echo base_url(); ?>banner-group/group-list"> Banner Group</a></li>
                                <li><a href="<?php echo base_url(); ?>slider/slider_list">Banner</a></li>-->
                                <li><a href="<?php echo base_url(); ?>package_master/package_list"> Packages </a></li>
                                <!--<li><a href="<?php echo base_url(); ?>creative_master/creative_list"> Creative </a></li>-->
                                <li><a href="<?php echo base_url(); ?>doctor_master/doctor_list"> Doctor </a></li>
                                <li><a href="<?php echo base_url(); ?>source_master/source_list"> Source </a></li>
                                <li><a href="<?php echo base_url(); ?>health_feed_master/health_feed_list"> Health Feed </a></li>
                                <li><a href="<?php echo base_url(); ?>cms_master/index"> Site Setting </a></li>
                                <li><a href="<?php echo base_url(); ?>cms_master/sms"> SMS Setting </a></li>
                                <li><a href="<?php echo base_url(); ?>testimonials_master/index"> Testimonials </a></li>
                                <li><a href="<?php echo base_url(); ?>offer_master">Offer</a></li>
                                <li><a href="<?php echo base_url(); ?>phlebo_master/phlebo_list"> Phlebo </a></li>
                                <li><a href="<?php echo base_url(); ?>backup/backup_list"> Database Backup <span class="sr-only">(current)</span></a></li>
                            </ul>
                        </li> 


                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown"> Report<span class="caret"></span></a>
                            <ul class="dropdown-menu" role="menu">
                                <li><a href="<?php echo base_url(); ?>support_system"> Support System <span id="supportpanding" class="label label-danger"></span><span class="sr-only">(current)</span></a></li>
                                <li ><a href="<?php echo base_url(); ?>wallet_master/account_history"> Wallet History</a></li>
                                <li ><a href="<?php echo base_url(); ?>customer_master/partner_with_us"> Partner with us
                                    </a></li>
                                <li ><a href="<?php echo base_url(); ?>contact_us_master"> Contact Us <span id="contact_us" class="label label-danger"></span><span class="sr-only">(current)</span></a></li>

                            </ul>

                        </li>



                        <li class="active"><a href="<?php echo base_url(); ?>customer-master/customer-list"> Customer Manage <span class="sr-only">(current)</span></a></li>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown"> Phlebo <span class="caret"></span></a>
                            <ul class="dropdown-menu" role="menu">
                                <li><a href="<?php echo base_url(); ?>Phlebo"> Manage <span id="supportpanding" class="label label-danger"></span><span class="sr-only">(current)</span></a></li>
                                <li><a href="<?php echo base_url(); ?>Timeslot_master"> Time Slot <span id="supportpanding" class="label label-danger"></span><span class="sr-only">(current)</span></a></li>
                                <li><a href="<?php echo base_url(); ?>Day_master/day_list"> Days <span id="supportpanding" class="label label-danger"></span><span class="sr-only">(current)</span></a></li>
                            </ul>

                        </li>
            <!--<li class="active"><a href="<?php echo base_url(); ?>issue_master"> Queries and Issues <span class="sr-only">(current)</span></a></li> -->




                        <li ><a href="<?php echo base_url(); ?>job_master/all_pushnotification"> Send New Notification</a></li>
                        <li ><a href="<?php echo base_url(); ?>user_call_master"> User Call</a></li>
                        <li ><a href="<?php echo base_url(); ?>Admin/TelecallerCallBooking"> Tele Caller</a></li>
                        <li ><a href="<?php echo base_url(); ?>test-master/price-list"> Price List</a></li>
                        <li><a href="<?php echo base_url(); ?>registration_admin">Register & Test</a></li>
                        <li><a href="<?php echo base_url(); ?>remains_book_master">Remains Book User</a></li>
                        <li><a href="<?php echo base_url(); ?>SupportDoc_system">Query</a></li>
                    <?php } ?>
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
                                    <a href="<?php echo base_url(); ?>admin-profile-update" class="btn btn-default btn-flat">Profile</a>
                                </div>
                                <div class="pull-right">
                                    <a href="<?php echo base_url(); ?>login/logout" class="btn btn-default btn-flat">Sign out</a>
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
