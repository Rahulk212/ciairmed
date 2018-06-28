<!-- Add fancyBox main JS and CSS files -->
<script type="text/javascript" src="<?php echo base_url(); ?>source/jquery.fancybox.pack.js?v=2.1.5"></script>
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>source/jquery.fancybox.css?v=2.1.5" media="screen" />
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<!-- Add Button helper (this is optional) -->
<link href="<?php echo base_url(); ?>user_assets/chosen/select.css" rel="stylesheet" type="text/css" media="all" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>source/helpers/jquery.fancybox-buttons.css?v=1.0.5" />
<script type="text/javascript" src="<?php echo base_url(); ?>source/helpers/jquery.fancybox-buttons.js?v=1.0.5"></script>

<!-- Add Thumbnail helper (this is optional) -->
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>source/helpers/jquery.fancybox-thumbs.css?v=1.0.7" />
<script type="text/javascript" src="<?php echo base_url(); ?>source/helpers/jquery.fancybox-thumbs.js?v=1.0.7"></script>

<!-- Add Media helper (this is optional) -->
<script type="text/javascript" src="<?php echo base_url(); ?>source/helpers/jquery.fancybox-media.js?v=1.0.6"></script>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<script type="text/javascript" src="<?php echo base_url(); ?>source/jquery.fancybox.pack.js?v=2.1.5"></script>
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>source/jquery.fancybox.css?v=2.1.5" media="screen" />
<script src="//cdn.ckeditor.com/4.5.8/full/ckeditor.js"></script>
<style>
    body{width:90%;}
</style>
<?php
$city_id = '';
if ($job_details[0]["test_city"] == 4) {
    $city_id = 1;
}
if ($job_details[0]["test_city"] == 5) {
    $city_id = 9;
}
//$city_id = 1;
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "http://beta.labforsure.com/api/v3/slots");
curl_setopt($ch, CURLOPT_POST, 1);
//curl_setopt($ch, CURLOPT_HEADER, "Authorization=95e84166c3097122cb4ef144a68626f6");
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'Authorization:95e84166c3097122cb4ef144a68626f6'
));
curl_setopt($ch, CURLOPT_POSTFIELDS, "Date=" . $date . "&localityId=" . $localityId . "&cityId=" . $city_id);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
echo $server_output = curl_exec($ch);
curl_close($ch);

function get_time($n1, $minutes) {
    $n2 = 60;
    $n = (int) ($n1 / $n2);
    $minu = fmod($n1, $n2);
    $date = $n . ":" . $minu . ":00";
    $endTime = date("h:i A", strtotime('+' . $minutes . ' minutes', strtotime($date)));
    return date('h:i A', strtotime($date)) . " TO " . $endTime;
}

$lfs_time_slot = json_decode($server_output);
?>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <!--<div class="box-header">
                        <h3 class="box-title">Job Details</h3>
                    </div>-->
                    <div class="box-body">
                        <div class="col-sm-12">
                            <div id="edit_div">
                                <h3>Booking Details</h3>
                                <table class="table table-bordered table-striped" id="city_wiae_price">
                                    <thead>
                                        <tr>
                                            <th>Parameters</th>
                                            <th>Value</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        ?>
                                        <tr>
                                            <td>
                                                Sample Collection Date
                                            </td>
                                            <td>
                                                <?= $date; ?>&nbsp;<small>(Y-M-D)</small>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                Time
                                            </td>
                                            <td>
                                                <?= ucfirst($booking_info[0]["start_time"]); ?>-<?= ucfirst($booking_info[0]["end_time"]); ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                Address
                                            </td>
                                            <td>
                                                <?= ucfirst($booking_info[0]["address"]); ?>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>



                                <h3>Job Test</h3>
                                <form id="get_time_slot">
                                    <table class="table table-bordered table-striped" id="city_wiae_price">
                                        <thead>
                                            <tr>
                                                <th>Name</th>
                                                <th>Price</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $cnt = 0;
                                            $selected_test = array();
                                            $main_price = 0;
                                            foreach ($job_details[0]["book_test"] as $value) {
                                                $selected_test[] = array("cnt" => $cnt, "id" => "t-" . $value["id"]);
                                                $main_price = $main_price + $value["price"];
                                                ?>
                                                <tr id="tr_<?= $cnt ?>">
                                                    <td>
                                                        <?= ucfirst($value["test_name"]); ?>
                                                        <input type="hidden" name="tests[<?= $cnt ?>][lab_id]" value="1029"/>
                                                        <input type="hidden" name="tests[<?= $cnt ?>][dos_code]" value="<?= $value["TEST_CODE"]; ?>"/>
                                                        <input type="hidden" name="tests[<?= $cnt ?>][mrp]" value="<?= $value["price"]; ?>"/>
                                                        <input type="hidden" name="tests[<?= $cnt ?>][offered_price]" value="<?= $value["price"]; ?>"/>
                                                        <input type="hidden" name="tests[<?= $cnt ?>][booking_reference_id]" value="<?= rand(1111111, 999999999); ?>"/>
                                                        <input type="hidden" name="tests[<?= $cnt ?>][preffered_time]" value="<?= rand(1111111, 999999999); ?>"/>
                                                        
                                                    </td>
                                                    <td>
                                                        Rs.<?= ucfirst($value["price"]); ?>
                                                    </td>
                                                </tr>
                                                <?php
                                                $cnt++;
                                            }
                                            ?>
                                            <?php
                                            foreach ($job_details[0]["book_package"] as $value) {
                                                $selected_test[] = array("cnt" => $cnt, "id" => "p-" . $value["id"]);
                                                $main_price = $main_price + $value["d_price"];
                                                ?>
                                                <tr id="tr_<?= $cnt ?>">
                                                    <td>
                                                        <?= ucfirst($value["title"]); ?>
                                                    </td>
                                                    <td>
                                                        Rs.<?= ucfirst($value["d_price"]); ?>
                                                    </td>
                                                </tr>
                                                <?php
                                                $cnt++;
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                    <div id="hidden_test">
                                        <?php foreach ($selected_test as $key) { ?>
                                            <input id="tr1_<?= $key["cnt"] ?>" name="test[]" value="<?= $key["id"] ?>" type="hidden">
                                        <?php } ?>
                                    </div>
                                    <div class="form-controll col-sm-6">

                                        <input type="hidden" name="amount_collected" value="<?= $main_price; ?>"/>
                                        <input type="hidden" name="reporting_charge" value="<?= 0; ?>"/>
                                        <input type="hidden" name="age" value="<?= 24; ?>"/>
                                        <input type="hidden" name="email" value="<?= $user_info[0]["email"]; ?>"/>
                                        <input type="hidden" name="cityId" value="<?= $city_id; ?>"/>
                                        <input type="hidden" name="pincode" value="<?= $main_price; ?>"/>
                                        <input type="hidden" name="amount_collected" value="<?= $main_price; ?>"/>
                                        <input type="hidden" name="mobile" value="<?= $user_info[0]["mobile"]; ?>"/>
                                        <input type="hidden" name="landmark" value="<?= ""; ?>"/>
                                        <input type="hidden" name="gender" value="<?= ucfirst($user_info[0]["gender"]); ?>"/>
                                        <input type="hidden" name="hard_copy_required" value="<?= 1; ?>"/>
                                        <input type="hidden" name="name" value="<?= $user_info[0]["full_name"]; ?>"/>
                                        <input type="hidden" name="locality" value="<?= 12345; ?>"/>
                                        <input type="hidden" name="localityId" value="<?= 12345; ?>"/>
                                        <input type="hidden" name="address" value="<?= $job_details[0]["address"]; ?>"/>
                                        <input type="hidden" name="order_reference_id" value="<?= $job_details[0]["order_id"]; ?>"/>
                                        <input type="hidden" name="collection_charge" value="<?= 0; ?>"/>
                                        <input type="hidden" value="<?= $jid ?>" name="jid"/>
                                        <div class="form-group">
                                            <label for="email">Date:</label>
                                            <input type="text" name="date" class="form-control" value="<?= $date; ?>">
                                        </div>
                                        <div class="form-group">
                                            <label for="pwd">Locality:</label>
                                            <select class="form-conrol" id="locality" name="localityId" onchange="$('#get_time_slot').submit();">
                                                <?php
                                                echo '<option value="">--Select--</option>';
                                                foreach ($locality_list as $key) {
                                                    echo '<option value="' . $key["id"] . '"';
                                                    if ($localityId == $key["id"]) {
                                                        echo "selected";
                                                    }
                                                    echo '>' . $key["name"] . '</option>';
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="pwd">Time slot:</label> 
                                            <?php if ($lfs_time_slot->status == 'success') { ?>
                                            <select class="form-conrol" id="time_slot" name="slot_id">
                                                    <?php
                                                    foreach ($lfs_time_slot->slots as $key) {
                                                        echo '<option value="' . $key->id . '">' . get_time($key->starttime, $key->duration) . '</option>';
                                                    }
                                                    ?>
                                                </select>
                                                <?php
                                            } else {
                                                echo "Not available.";
                                            }
                                            ?>
                                        </div>
                                        <!--                                        <button type="submit" class="btn btn-default">Submit</button>-->

                                    </div>
                                </form>
                            </div>
                        </div>

                        <div class="box-footer">
                            <input style="" class="btn btn-primary" value="Send To LFS" id="add_sub_parameter" type="button" onclick="SendToLfs();">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<script type="text/javascript">
    function SendToLfs() {
        /* var amount_collected =<?= $main_price; ?>;
         var reporting_charge =<?= $main_price; ?>;
         var age = 24;
         var email = '<?= $user_info[0]["email"]; ?>';
         var cityId =<?= $city_id; ?>;
         var pincode = 122001;
         var mobile = '<?= $user_info[0]["mobile"]; ?>';
         var landmark = '<?= $job_details[0]["landmark"]; ?>';
         var gender = '<?= ucfirst($job_details[0]["gender"]); ?>';
         var hard_copy_required = 1;
         var name = '<?= $user_info[0]["full_name"]; ?>';
         var locality = $("#locality option:selected").text();
         var localityId = $("#locality").val();
         var address = '<?= $job_details[0]["address"]; ?>';
         var order_reference_id = '';
         var collection_charge = 0;
         values1 = $("input[name='tests[]']").map(function () {
         return $(this).val();
         }).get();
         console.log(values1); 
         alert("Hii");
         var slot_id = $("#time_slot").val(); */
        /*var tests[0][lab_id] = 1029;
         var tests[0][dos_code] = AIR - BC270;
         var tests[0][mrp] = 2925;
         var tests[0][offered_price] = 2486;
         var tests[0][booking_reference_id] = aaaaaaaaaaa111;
         var tests[0][preffered_time] = 016 - 03 - 22 7:00 AM;*/
        var frm_data = $("#get_time_slot");
        $.ajax({
            url: "<?=base_url();?>job_master/test12345",
            type: "POST",
            data: frm_data.serializeArray(),
            error: function (jqXHR, error, code) {
            },
            /*headers: {
             'Access-Control-Allow-Origin': '*',
             'Authorization': '95e84166c3097122cb4ef144a68626f6',
             'Origin': 'http://beta.labforsure.com'
             },*/
            success: function (data) {
                console.log(data);
            }
        });
    }
</script>
<div class="modal fade" id="myModal_view" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content srch_popup_full">
            <div class="modal-header srch_popup_full srch_head_clr">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title clr_fff">Package Detail</h4>
            </div>
            <div class="modal-body srch_popup_full">
                <div class="srch_popup_full srch_popup_acco">
                    <div id="accordion1" class="panel-group accordion transparent">
                        <div class="panel">
                            <div class="panel-collapse collapse in" role="tablist" aria-expanded="true">
                                <div class="panel-content" id="description">

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>