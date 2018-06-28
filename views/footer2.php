<style>
    /*******************************
* MODAL AS LEFT/RIGHT SIDEBAR
* Add "left" or "right" in modal parent div, after class="modal".
* Get free snippets on bootpen.com
*******************************/
    .modal.left .modal-dialog,
    .modal.right .modal-dialog {
        position: fixed;
        margin: auto;
        width: 320px;
        height: 100%;
        -webkit-transform: translate3d(0%, 0, 0);
        -ms-transform: translate3d(0%, 0, 0);
        -o-transform: translate3d(0%, 0, 0);
        transform: translate3d(0%, 0, 0);
    }

    .modal.left .modal-content,
    .modal.right .modal-content {
        height: 100%;
        overflow-y: auto;
    }

    .modal.left .modal-body,
    .modal.right .modal-body {
        padding: 15px 15px 80px;
    }

    /*Left*/
    .modal.left.fade .modal-dialog{
        left: -320px;
        -webkit-transition: opacity 0.3s linear, left 0.3s ease-out;
        -moz-transition: opacity 0.3s linear, left 0.3s ease-out;
        -o-transition: opacity 0.3s linear, left 0.3s ease-out;
        transition: opacity 0.3s linear, left 0.3s ease-out;
    }

    .modal.left.fade.in .modal-dialog{
        left: 0;
    }

    /*Right*/
    .modal.right.fade .modal-dialog {
        right: -320px;
        -webkit-transition: opacity 0.3s linear, right 0.3s ease-out;
        -moz-transition: opacity 0.3s linear, right 0.3s ease-out;
        -o-transition: opacity 0.3s linear, right 0.3s ease-out;
        transition: opacity 0.3s linear, right 0.3s ease-out;
    }

    .modal.right.fade.in .modal-dialog {
        right: 0;
    }

    /* ----- MODAL STYLE ----- */
    .modal-content {
        border-radius: 0;
        border: none;
    }

    .modal-header {
        border-bottom-color: #EEEEEE;
        background-color: #FAFAFA;
    }

    /* ----- v CAN BE DELETED v ----- */
    body {
        background-color: #78909C;
    }

    .demo {
        padding-top: 60px;
        padding-bottom: 110px;
    }

    .btn-demo {
        margin: 15px;
        padding: 10px 15px;
        border-radius: 0;
        font-size: 16px;
        background-color: #FFFFFF;
    }

    .btn-demo:focus {
        outline: 0;
    }

    .demo-footer {
        position: fixed;
        bottom: 0;
        width: 100%;
        padding: 15px;
        background-color: #212121;
        text-align: center;
    }

    .demo-footer > a {
        text-decoration: none;
        font-weight: bold;
        font-size: 16px;
        color: #fff;
    }
    .content_details_users label input {
        color: #000000;
    }
    .rgt_sidebar.modal {background: transparent;}
    body .modal-backdrop.in {opacity: 0;}
    .mrgn_btm_25px {margin-bottom: 25px;}
    .login_light_back .input-group {float: left; width: 100%;}
    .login_light_back .input-group .input-group-addon {float: left; width: 8%;}
    .input-group-addon {background-color: #eeeeee !important; border: 1px solid #cccccc !important; border-radius: 4px; color: #555555; font-size: 14px; font-weight: 400; line-height: 1; padding: 9px 12px; text-align: center;}
    .login_light_back .input-group input.form-control {float: left; width: 92%;}
    .regstr_gendr_full {float: left; width: 100%;}
    .spn_red {color: #ff0000;}
    .regstr_male {float: left; margin-right: 6%; width: auto;}
    .regstr_male input[type="radio"] {margin-right: 8px;}
    .fillInfoClass{display:none;}
    .token-input-dropdown-facebook{  bottom: 19% !important; height:180px !important; width: 260px !important; z-index: 300000 !important;}
    #telicaller_test_list ul li.token-input-input-token-facebook{border:1px solid #ddd; border-radius:3px;}
    div.token-input-dropdown-facebook ul{height:100% !important;}
    #telicaller_test_list{height:200px}
    .token-input-token-facebook.token-input-selected-token-facebook > p {
        color: #737374;
    }
</style>
<!-- Modal -->
<div class="modal right fade rgt_sidebar" id="call_details" tabindex="-1" role="dialog" aria-labelledby="myModalLabel2">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <!--<button type="button" class="close" id="hidemenu" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>-->
                <button type="button" class="close" id="hidemenu" data-dismiss="modal" onclick="showMenu = false;change_exotell_status();" >Hide</button>

                <h4 class="modal-title" id="myModalLabel2">Incoming Call</h4>
            </div>

            <div class="modal-body content_details_users" id="content_details_call">

            </div>

        </div><!-- modal-content -->
    </div><!-- modal-dialog -->
</div><!-- modal -->
<!-- Modal -->
<div id="show_register_user_details" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">User Details</h4>
            </div>
            <div class="modal-body" id="user_details_body" style="height:250px;">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>

    </div>
</div>
<!--Modal-->
<!--Register Model-->
<div id="register_model" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">User Register</h4>
            </div>
            <div class="modal-body" id="register_get_details">

            </div>
            <div class="modal-footer">
                <button class="btn btn-info" type="button" onclick="insert_user_register();">Register</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>

    </div>
</div>
<input type='hidden' id='submit_details' value='0'/>
<!--Register Model-->
<footer class="main-footer">
    <div class="container">
        <div class="pull-right hidden-xs">

        </div>
        <strong>Copyright &copy; 2016 <a href="">AirmedLabs</a>.</strong> All rights reserved.
    </div><!-- /.container -->
</footer>
</div><!-- ./wrapper -->
<!-- ChartJS 1.0.1 -->
<script src="<?= base_url(); ?>plugins/jQuery/jQuery-2.1.4.min.js"></script>
<script src="<?= base_url(); ?>plugins/chartjs/Chart.min.js" type="text/javascript"></script>
<!-- FastClick -->

<!-- AdminLTE App -->

<!-- AdminLTE for demo purposes -->


<!-- Bootstrap 3.3.2 JS -->
<script src="<?php echo base_url(); ?>bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
<!-- SlimScroll -->
<script src="<?php echo base_url(); ?>plugins/slimScroll/jquery.slimscroll.min.js" type="text/javascript"></script>
<!-- FastClick -->
<script src='<?php echo base_url(); ?>plugins/fastclick/fastclick.min.js'></script>
<!-- AdminLTE App -->
<script src="<?php echo base_url(); ?>dist/js/app.min.js" type="text/javascript"></script>
<!-- AdminLTE for demo purposes -->
<script src="<?php echo base_url(); ?>dist/js/demo.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>js/admin.js" type="text/javascript"></script>
<script src="<?= base_url(); ?>assets/summernote/summernote.js"></script>
<script src="<?= base_url(); ?>assets/js/pages/forms.js"></script>

<!-- Bootstrap 3.3.2 JS -->

<!-- InputMask -->

<script src="<?php echo base_url(); ?>plugins/input-mask/jquery.inputmask.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>plugins/input-mask/jquery.inputmask.extensions.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>plugins/input-mask/jquery.inputmask.date.extensions.js" type="text/javascript"></script>

<link rel="stylesheet" type="text/css" media="all" href="<?php echo base_url(); ?>user_assets/css/auto_input/styles.css">
<link rel="stylesheet" type="text/css" media="all" href="<?php echo base_url(); ?>user_assets/css/auto_input/token-input.css">
<link rel="stylesheet" type="text/css" media="all" href="<?php echo base_url(); ?>user_assets/css/auto_input/token-input-facebook.css">
<!-- Bootstrap WYSIHTML5 -->
<script src="<?php echo base_url(); ?>plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js" type="text/javascript"></script>

<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/js/bootstrap-datepicker.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/css/bootstrap-datepicker3.css"/>
<!-- Include the plugin's CSS and JS: -->
<script type="text/javascript" src="<?php echo base_url(); ?>js/bootstrap-multiselect.js"></script>
<link rel="stylesheet" href="<?php echo base_url(); ?>css/bootstrap-multiselect.css" type="text/css"/>

<script>
                    $(document).ready(function () {
                        var date_input = $('input[name="date"]'); //our date input has the name "date"

                        var container = $('.bootstrap-iso form').length > 0 ? $('.bootstrap-iso form').parent() : "body";
                        date_input.datepicker({
                            format: 'dd/mm/yyyy',
                            container: container,
                            todayHighlight: true,
                            autoclose: true,
                        })
                    })

                    $(document).ready(function () {
                        var date_input = $('.datepicker-input'); //our date input has the name "date"

                        var container = $('.bootstrap-iso form').length > 0 ? $('.bootstrap-iso form').parent() : "body";
                        date_input.datepicker({
                            format: 'dd/mm/yyyy',
                            container: container,
                            todayHighlight: true,
                            autoclose: true,
                        })
                    })
</script>

<!-- FastClick -->

<!-- AdminLTE App -->

<!-- AdminLTE for demo purposes -->

<!-- Page script -->
<script type="text/javascript">
    $(function () {
        //Datemask dd/mm/yyyy
        $("#datemask").inputmask("dd/mm/yyyy", {"placeholder": "dd/mm/yyyy"});

        $("[data-mask]").inputmask();


    });
</script>
<!-- DATA TABES SCRIPT -->
<script src="<?php echo base_url(); ?>plugins/datatables/jquery.dataTables.min.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>plugins/datatables/dataTables.bootstrap.min.js" type="text/javascript"></script>
<script type="text/javascript">
    $(function () {
        $("#example1").dataTable();
        $('#example2').dataTable({
            "bPaginate": true,
            "bLengthChange": false,
            "bFilter": true,
            "bSort": false,
            "bInfo": false,
            "bAutoWidth": false,
            "iDisplayLength": 10
        });
    });
</script>
<script src="https://cdn.ckeditor.com/4.4.3/standard/ckeditor.js"></script>
<script type="text/javascript">
    $(function () {

        setInterval(function () {
            console.log(showMenu);
            if (showMenu) {
                checkregisternumber();
            }
        }, 3000);
        // Replace the <textarea id="editor1"> with a CKEditor
// instance, using default configuration.
        CKEDITOR.replace('editor1');
        CKEDITOR.replace('editor2');
//bootstrap WYSIHTML5 - text editor
        $(".textarea").wysihtml5();
    });
</script>
<script type="text/javascript">


    function get_pending_count() {

        $.ajax({
            url: "<?php echo base_url(); ?>job_master/pending_count/",
            error: function (jqXHR, error, code) {
                // alert("not show");
            },
            success: function (data) {
                //     console.log("data"+data);
                //var jsonparse = JSON.Parse(data);
                var obj = $.parseJSON(data);
                //console.log(obj.job_count);
                if (obj.inquiry_total != '0') {
                    document.getElementById('test_package_count').innerHTML = obj.all_inquiry;
                } else {
                    document.getElementById('test_package_count').innerHTML = '0';
                }
                if (obj.inquiry_total != '0') {
                    document.getElementById('inquiry_total').innerHTML = obj.inquiry_total;
                } else {
                    document.getElementById('inquiry_total').innerHTML = '0';
                }
                if (obj.tickepanding != '0') {
                    document.getElementById('supportpanding').innerHTML = obj.tickepanding;
                } else {
                    document.getElementById('supportpanding').innerHTML = '0';
                }
                if (obj.job_count != '0') {
                    document.getElementById('pending_count').innerHTML = obj.job_count;
                    document.getElementById('total_pending_count').innerHTML = obj.job_count;
                } else {
                    document.getElementById('pending_count').innerHTML = '0';
                    document.getElementById('total_pending_count').innerHTML = '0';
                }
                if (obj.prescription_upload != '0') {
                    document.getElementById('prescription_count').innerHTML = obj.prescription_upload;
                } else {
                    document.getElementById('prescription_count').innerHTML = '0';
                }
                if (obj.contact_us_count != '0') {
                    document.getElementById('contact_us').innerHTML = obj.contact_us_count;
                } else {
                    document.getElementById('contact_us').innerHTML = '0';
                }
                if (obj.package_inquiry != '0') {
                    document.getElementById('package_inquiry').innerHTML = obj.package_inquiry;
                } else {
                    document.getElementById('package_inquiry').innerHTML = '0';
                }
            }
        });

    }

    get_pending_count();
    showMenu = true;
    callrunning = false;
    function checkregisternumber() {
        $.ajax({
            url: "<?php echo base_url(); ?>user_call_master/show_call_detail_popup/",
            error: function (jqXHR, error, code) {
            },
            success: function (data) {
                if (data != '') {
                    if (data == 'no') {
                        callrunning = false;
                        $("#call_details").modal('hide');

                    } else {
                        callrunning = true;
                        var temp = $("#submit_details").val();
                        if (temp == 1) {
                            $("#call_details").modal('show');
                        } else {
                            document.getElementById('content_details_call').innerHTML = "";
                            document.getElementById('content_details_call').innerHTML = data;
                            $("#submit_details").val("1");
                            if (showMenu)
                            {
                                $("#call_details").modal('show');
                            }
                        }
                    }
                } else {
                    $("#call_details").modal('hide');
                }
            }
        });
    }
    function contact_info() {
        var name1 = $("#name12").val();
        var mobile = $("#mobile").val();
        var email1 = $("#email123").val();
        $.ajax({
            url: "<?php echo base_url(); ?>user_call_master/insert_contact_info/",
            type: "POST",
            data: {namen: name1, mobile: mobile, emaile: email1},
            error: function (jqXHR, error, code) {
                // alert("not show");
            },
            success: function (data) {
                if (data) {
                    $("#submit_details").val('0');
                    checkregisternumber();
                    //location.reload();
                }
            }
        });
    }
    function show_caller_reg_details(val) {
        $.ajax({
            url: "<?php echo base_url(); ?>user_call_master/show_user_detail_popup/",
            type: "POST",
            data: {mobile: val},
            error: function (jqXHR, error, code) {
            },
            success: function (data) {
                if (data != '') {
                    document.getElementById('user_details_body').innerHTML = "";
                    document.getElementById('user_details_body').innerHTML = data;
                    $("#show_register_user_details").modal('show');

                } else {
                    $("#show_register_user_details").modal('hide');
                }
            }
        });
    }
    function show_user_register(val) {
        $.ajax({
            url: "<?php echo base_url(); ?>user_call_master/show_user_register_modal/",
            type: "POST",
            data: {mobile: val},
            error: function (jqXHR, error, code) {
            },
            success: function (data) {
                if (data != '') {
                    document.getElementById('register_get_details').innerHTML = "";
                    document.getElementById('register_get_details').innerHTML = data;
                    $("#register_model").modal('show');

                } else {
                    $("#register_model").modal('hide');
                }
            }
        });
    }
    function insert_user_register() {
        $("#reg_name_call_error").html('');
        $("#reg_email_call_error").html('');
        $("#reg_mobile_call_error").html('');
        $("#reg_city_call_error").html('');
        $("#reg_gender_call_error").html('');
        var name = $("#re_name_call").val();
        var email = $("#re_email_call").val();
        var mobile = $("#re_mobile_call").val();
        var city = $("#re_city_call").val();
        var refercode = $("#re_refer_code_call").val();
        var gender = $('#re_gender_call:checked').val();
        if (name == '') {
            $("#reg_name_call_error").html('Name Required!');
            return false;
        }
        if (email == '') {
            $("#reg_email_call_error").html('Email Required!');
            return false;
        } else if (checkemail(email) == false) {
            $("#reg_email_call_error").html('Invalid Email!');
            return false;
        }
        if (mobile == '') {
            $("#reg_mobile_call_error").html('Mobile Required!');
            return false;
        } else if (checkmobile(mobile) == false) {
            $("#reg_mobile_call_error").html('Invalid Mobile Number!');
            return false;
        }
        if (city == '') {
            $("#reg_city_call_error").html('Please Select City!');
            return false;
        }
        if (gender == '') {
            $("#reg_gender_call_error").html('Please Select Gender!');
            return false;
        }
        if (name != '' && email != '' && mobile != '' && city != '' && gender != '') {
            $.ajax({
                url: "<?php echo base_url(); ?>user_call_master/insert_user_register_info/",
                type: "POST",
                data: {name: name, email: email, mobile: mobile, city: city, gender: gender, refercode: refercode},
                error: function (jqXHR, error, code) {
                    // alert("not show");
                },
                success: function (data) {
                    if (data) {
                        //$("#submit_details").val('0');
                        $("#register_model").modal('hide');
                        checkregisternumber();
                        //location.reload();
                    }
                }
            });
        }
    }
    function checkemail(mail) {
        var filter = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;
        if (filter.test(mail)) {
            return true;
        } else {
            return false;
        }
    }
    function checkmobile(mobile) {
        var filter = /^[0-9-+]+$/;
        var pattern = /^\d{10,11}$/;
        if (filter.test(mobile)) {
            if (pattern.test(mobile)) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }


    function fillinfo() {

    }
    /*Nishit code start*/
    function change_exotell_status() {
        var ids = $("#caller_id").val();
        $.ajax({
            url: "<?php echo base_url(); ?>user_call_master/change_call_status?ids=" + ids,
            type: "POST",
            data: {ids: ids},
            error: function (jqXHR, error, code) {
            },
            success: function (data) {
            }
        });
    }
    function get_packages123(city_fk) {
        $.ajax({
            url: '<?= base_url(); ?>User_call_master/package_list1',
            type: 'post',
            tryCount: 0,
            retryLimit: 3,
            data: {city: city_fk},
            beforeSend: function () {
                $("#loader_div2").attr("style", "");
                $("#send_quotte_btn").attr("disabled", "disabled");
                $("#send_quote_test_city").attr("disabled", "disabled");
            },
            success: function (data) {
                $('#telicaller_test_list').html(data);
            },
            complete: function () {
                $("#loader_div2").attr("style", "display:none;");
                $("#send_quotte_btn").removeAttr("disabled");
                $("#send_quote_test_city").removeAttr("disabled");
            },
            error: function (xhr, textStatus, errorThrown) {
            }
        });
    }
        $.ajax({
        url: "<?php echo base_url(); ?>Collection/todays_collection/",
        error: function (jqXHR, error, code) {
        },
        success: function (data) {
            $("#todays_collection").html(data);
        }
    });
    /*Nishit code end*/
</script>
<script type="text/javascript" src="<?php echo base_url(); ?>user_assets/chosen/chosen.jquery.js"></script>

</body>
</html>
