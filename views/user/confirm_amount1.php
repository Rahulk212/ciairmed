<!-- Start main-content -->
<link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
<script type="text/javascript" src="https://code.jquery.com/jquery-1.11.3.min.js"></script>
<script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
<style>
.modal2 {
position: fixed;
z-index: 100000;
top: 0;
left: 0;
height: 100%;
width: 100%;
background: rgba(0, 0, 0, .5) url('<?php echo base_url(); ?>img/small-loading.gif') 50% 50% no-repeat;
}
</style>
<div class="modal2" id="arrowLoader" style="display: block;"></div>
<div class="main-content">
    <!-- Section: home -->
    <section>
        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                    <div class="checkbox">
                        <label><input type="checkbox" onclick="wallet_show();"  id="myCheck" <?php
                            if ($wallet != 0) {
                                echo "checked";
                            }
                            ?> <?php
                            if ($wallet == 0) {
                                echo "disabled"; 
                            }
                            ?>>Use Wallet Amount</label>
                    </div>
                </div>
            </div>
            <div class="row">

                <div class="col-sm-12">
                    <div class="main_wallet" style="background:#F5F8F9;">
                        <div class="col-sm-12 pdng_0">
                            <div class="col-sm-3">
                                <div class="wbalance">
                                    <p>Payment To be made</p>
                                    <p>Rs  <?php echo $price; ?></p>
                                </div>
                            </div>
                            <div class="col-sm-9 cmfrm_res_cl9_pdng" id="hide_div" style="<?php
                            if ($wallet == 0) {
                                echo "display:none";
                            }
                            ?>" >
                                <div class="col-sm-1">
                                    <span style="font-size:50px;">-</span>
                                </div>
                                <div class="col-sm-5">
                                    <div class="wbalance">
                                        <p>Your Wallet Balance</p>
                                        <p>Rs <?php echo $wallet; ?></p>
                                    </div> 
                                </div>
                                <div class="col-sm-1">
                                    <span style="font-size:50px;">=</span>
                                </div>
                                <div class="col-sm-5">
                                    <div class="wbalance">
                                        <p>Remaining Amount in wallet</p>
                                        <p style="font-weight:bold;">Rs <?php echo $payamount; ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12 pdng_0">
                            <div class="col-sm-12">
                                </br>
                                <form id="frm" role="form" action="<?php echo base_url(); ?>user_test_master/book_test1/<?php echo $testid; ?>/wallet"  method="post" enctype="multipart/form-data">
                                    <label> My Referral Doctor </label>
                                    </br>
                                    <select name="doctor" id="doctor">
                                        <option value="">-Select Doctor-</option>
                                        <?php foreach ($doctor as $key) { ?>
                                            <option value="<?php echo $key['id']; ?>"><?php echo ucfirst($key['full_name']); ?></option>
                                        <?php } ?>
                                    </select>
                                    </br>
                                    <label> Reference</label>
                                    </br>
                                    <input type="radio" id="r6" checked="checked" name="reference" value="doctor" onclick="show_text();"> Doctor</br>
                                    <input type="radio" id="r1" name="reference" value="newspaper" onclick="show_text();"> Newspaper</br>
                                    <input type="radio" id="r2" name="reference" value="hoarding" onclick="show_text();"> Hoarding</br>
                                    <input type="radio" id="r3" name="reference" value="socialmedia" onclick="show_text();"> Social Media</br>
                                    <input type="radio" id="r4" name="reference" value="radio" onclick="show_text();"> Radio</br>
                                    <input type="radio" id="r5" name="reference" value="other" onclick="show_text();"> Other</br>
                                    <input type="text" id="other_txt" style="display:none;" />

                            </div>
                        </div>
                        <div class="col-sm-12 pdng_0">
                            <div class="col-sm-12">

                                <input type="hidden" name="amount" value="<?php echo $payamount; ?>" id="frm_wallet" />
                                <input type="hidden" name="ptype" value="wallet" id="frm_type" />
                                <div class="col-sm-6 cnfrm_cl3_offst_3" style="margin-top:20px; float: left;">
                                        <!--<button type="submit" class="cnfrm_btn_noback col-xs-5 col-sm-4 pdng_0"><img class="cnfrm_two_img" src="<?php echo base_url(); ?>user_assets/images/new/payumoney.png"/></button>
                                        <span class="cnfrm_amnt_spn col-xs-1">OR</span>-->
                                    <button class="cnfrm_btn_noback col-xs-5 col-sm-4 pdng_0" id="crop_model" data-target="#myModal1" data-keyboard="false" data-backdrop="static" data-toggle="modal" type="button"><img class="cnfrm_two_img" src="<?php echo base_url(); ?>user_assets/images/new/cash_on_dlvry.png"/></button>
                                </div>
                                </form>
                            </div>
                        </div>
                    </div>



                </div>


            </div>
            <div role="dialog" id="myModal1" class="modal fade">
                <div class="modal-dialog">

                    <!-- Modal content-->
                    <div class="modal-content">
                        <div class="modal-header set_red_modl_hdr">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">Cash On Delivery Details</h4>
                        </div>
                        <div class="modal-body">
                            <?php echo form_open("user_test_master/cash_on_delivery/", array("role" => "form", "method" => "POST", "id" => "cash_on_delivery_id")); ?>
                            <input type="hidden" name="test_ids" value="<?= $test_ids ?>"/>
                            <input type="hidden" name="price" value="<?= $price ?>"/>
                            <input type="hidden" id="method_id" name="method" value="yes"/>
                            <div class="form-group">
                                <label for="recipient-name" class="form-control-label">Mobile:</label>
                                <input type="text" class="form-control" id="mobile_id" name="mobile1" value="<?=$user_info[0]["mobile"];?>" disabled="">
                                <input type="hidden" name="mobile" value="<?=$user_info[0]["mobile"];?>"/>
                                <input type="hidden" name="test_city" value="<?=$test_city_session;?>"/>
                                <span style="color:red" id="mobile_error"><?= form_error('mobile'); ?></span>
                            </div>                           
                            <!--<div class="form-group">
         <label for="message-text" class="form-control-label">Address:</label>
         <textarea class="form-control" id="address_id" name="address"></textarea>
         <span style="color:red" id="address_error"><?= form_error('address'); ?></span>
     </div>-->
                            <!-- <div class="form-group">
                                 <label for="recipient-name" class="form-control-label">State:</label>
                                 <select class="form-control" name="state" id="state_id" onchange="get_city(this);">
                                     <option value="">--Select--</option>
                            <?php foreach ($state as $key): ?>
                                                         <option value="<?= $key["id"] ?>"><?= ucfirst($key["state_name"]); ?></option>
                            <?php endforeach; ?>
                                 </select>
                                 <span style="color:red" id="state_error"><?= form_error('state'); ?></span>
                             </div>
                             <div class="form-group">
                                 <label for="recipient-name" class="form-control-label">City:</label>
                                 <select class="form-control" name="city" id="city_id">
                                     <option value="">--Select--</option>
                                 </select>
                                 <span style="color:red" id="city_error"><?= form_error('city'); ?></span>
                             </div>
                            <!--<div class="form-group">
                                <label for="recipient-name" class="form-control-label">Pin Code:</label>
                                <input type="text" class="form-control" id="pin_id" name="pin">
                                <span style="color:red" id="pin_error"><?= form_error('pin'); ?></span>
                            </div>-->
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary btn_red" onclick="Validation();">Submit</button>
                        </div>
                    </div>
                    <script>

                        function Validation() {
                            var cnt = 0;
                            // $("#address_error").html('');
                            $("#state_error").html('');
                            $("#city_error").html('');
                            // $("#pin_error").html('');
                            // var adddress = $("#address_id").val();
                            // adddress = adddress.trim();
                            //  if (adddress == ''){
                            //     cnt=cnt+1;
                            // $("#address_error").html('Address is required.');
                            //  }



                            // var pin_id = $("#pin_id").val();
                            // pin_id = pin_id.trim();
                            // if (pin_id == ''){
                            //    cnt=cnt+1;
                            //$("#pin_error").html('Pin is required.');
                            //   }
                            var mobile_id = $("#mobile_id").val();
                            mobile_id = mobile_id.trim();
                            if (mobile_id == '') {
                                cnt = cnt + 1;
                                $("#mobile_error").html('Mobile is required.');
                            }

                            if (cnt == 0) {
                                $("#cash_on_delivery_id").submit();
                            }

                        }
                    </script>
                </div>
            </div>

    </section>


    <!-- Footer Scripts -->

    <script src="<?php echo base_url(); ?>user_assets/js/jquery-2.2.0.min.js"></script>
    <script src="<?php echo base_url(); ?>user_assets/js/jquery-ui.min.js"></script>
    <script src="<?php echo base_url(); ?>user_assets/js/bootstrap.min.js"></script>

    <script>
                        function show_text() {
                            if (document.getElementById("r5").checked == true) {
                                document.getElementById("other_txt").setAttribute('style', 'display:block');
                            } else {
                                document.getElementById("other_txt").setAttribute('style', 'display:none');
                            }

                        }
                        function doctor_ref() {

                            $('#myModal1').show();

                        }
                        function wallet_show() {

                            if (document.getElementById("myCheck").checked == true) {


                                document.getElementById("hide_div").setAttribute('style', 'display:block');

                                document.getElementById("frm_wallet").setAttribute('value', '<?php echo $payamount; ?>');
                                document.getElementById("frm_type").setAttribute('value', 'wallet');
                                document.getElementById("frm").setAttribute('action', '<?php echo base_url(); ?>user_test_master/book_test1/<?php echo $testid; ?>/wallet');
                                document.getElementById("method_id").setAttribute('value', 'yes');

                            } else if (document.getElementById("myCheck").checked == false) {

                                var ref = "";
                                if (document.getElementById('r1').checked) {
                                    ref = document.getElementById('r1').value;
                                }
                                if (document.getElementById('r2').checked) {
                                    ref = document.getElementById('r2').value;
                                }
                                if (document.getElementById('r3').checked) {
                                    ref = document.getElementById('r3').value;
                                }
                                if (document.getElementById('r4').checked) {
                                    ref = document.getElementById('r4').value;
                                }
                                if (document.getElementById('r5').checked) {
                                    ref = document.getElementById('r5').value;
                                }
                                if (document.getElementById('r6').checked) {
                                    ref = document.getElementById('r6').value;
                                }
                                var doc = document.getElementById('doctor').value;

                                document.getElementById("hide_div").setAttribute('style', 'display:none');


                                document.getElementById("frm_wallet").setAttribute('value', '<?php echo $price; ?>');
                                document.getElementById("frm_type").setAttribute('value', 'price');
                                document.getElementById("frm").setAttribute('action', '<?php echo base_url(); ?>user_test_master/payumoney/<?php echo $testid; ?>/price/' + ref + '/' + doc + '');
                                document.getElementById("method_id").setAttribute('value', 'no');
                            }

                        }
                        function get_city(val) {
                            var val = val.value;
                            val = val.trim();
                            if (val != '') {
                                $.ajax({
                                    url: "<?= base_url(); ?>User_test_master/get_city/" + val,
                                    success: function (data) {
                                        $("#city_id").html(data);
                                    },
                                });
                            } else {
                                $("#city_id").html('<option value="">--Select--</option>');
                            }
                        }

$("#arrowLoader").hide();
    </script>
    <!-- end main-content -->
