<script type="text/javascript" src="https://code.jquery.com/jquery-1.11.3.min.js"></script>
<script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
<script src="<?= base_url(); ?>plugins/jQuery/jQuery-2.1.4.min.js" type="text/javascript"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>user_assets/js/auto_input/jquery.tokeninput.js"></script>
<input type="text" id="home_page_search" />
<b>Total price</b> - Rs.<sapn id="total_price">0</sapn>
<script type="text/javascript">
    $(document).ready(function () {
        total_price = 0;
        $(function () {
            $testAjax = $('#home_page_search').tokenInput(
<?php
$new_ary = array();
$cnt12 = 0;
foreach ($test as $ts) {
    $new_ary[$cnt12]["id"] = "t-" . $ts['id'];
    $new_ary[$cnt12]["name"] = $ts['test_name'] . "(Rs." . $ts['price'] . ")";
    $new_ary[$cnt12]["price"] = $ts['price'];
    ?>

    <?php
    $cnt12++;
} echo json_encode($new_ary);
?>
            , {
                theme: "facebook",
                noResultsText: "Nothing found.",
                preventDuplicates: true,
                onAdd: function (item) {
                    console.log("add ok");
                    var id = item.id;
                    /*setTimeout(function () {
                     if ($('#li_id_' + id).length){
                     document.getElementById('li_id_' + id).setAttribute("onClick", "remove_token('" + id + "', '" + item.name + "');");
                     }
                     }, 500);
                     $('#' + id + '_add').css("display", "none");
                     $('#' + id + '_close').css("display", "block");
                     $('#btn_search').html('Book');*/
                    //alert(item.price);
                    total_price = +total_price + +item.price;
                    $("#total_price").html(total_price);
                    setTimeout(function(){save_quote_changes();},1000);
                    return false;
                },
                onDelete: function (item) {
                    var delete_id = item.id;
                    /*setTimeout(function () {
                     if ($('#li_id_' + item.id).length){
                     document.getElementById('li_id_' + item.id).setAttribute("onClick", "add_token('" + item.id + "', '" + item.name + "');");
                     }
                     }, 500);
                     document.getElementById(delete_id + "_close").setAttribute("style", "display:none;");
                     document.getElementById(delete_id + "_add").setAttribute("style", "");*/
                    //alert(item.price);
                    total_price = total_price - item.price;
                    $("#total_price").html(total_price);
                    setTimeout(function(){save_quote_changes();},1000);
                    return false;
                }, });
        });
    });
        function save_quote_changes() {
        var selectedValues = $testAjax.tokenInput("get");
        var ids = [];
        var pid = [];
        var names = [];
        for (var key in selectedValues) {
            var value12 = selectedValues[key];
            var value1 = $.map(value12, function (value, index) {
                return [value];
            });
            var id = value1[0];
            var name = value1[1];
            var type = value1[2];
            ids.push(id);
            names.push(name);
        }
        var test_city = $("#send_quote_test_city").val();
        var total_price = $("#total_price").html();
        var mobile_no = $("#popup_mobile").val();
        var caller_id = $("#caller_id").val();
        //alert(ids+" "+test_city+" "+total_price+" "+mobile_no); die();
        if (ids != "") {
            $.ajax({
                url: "<?php echo base_url(); ?>user_call_master/save_quote_chages",
                type: 'post',
                data: {id: ids, test_city: test_city, total_price: total_price, mobile_no: mobile_no, caller_id: caller_id},
                beforeSend: function () {
                    $("#loader_div2").attr("style", "");
                    $("#send_quotte_btn").attr("disabled", "disabled");
                },
                success: function (data) {
                   
                }, complete: function () {
                    $("#loader_div2").attr("style", "display:none;");
                    $("#send_quotte_btn").removeAttr("disabled");
                }
            });
        } else {

        }
    }
    function get_select_value1() {
        var selectedValues = $testAjax.tokenInput("get");
        var ids = [];
        var pid = [];
        var names = [];
        for (var key in selectedValues) {
            var value12 = selectedValues[key];
            var value1 = $.map(value12, function (value, index) {
                return [value];
            });
            var id = value1[0];
            var name = value1[1];
            var type = value1[2];
            ids.push(id);
            names.push(name);
        }
        var test_city = $("#send_quote_test_city").val();
        var total_price = $("#total_price").html();
        var mobile_no = $("#popup_mobile").val();
        var caller_id = $("#caller_id").val();
        //alert(ids+" "+test_city+" "+total_price+" "+mobile_no); die();
        if (ids != "") {
            $.ajax({
                url: "<?php echo base_url(); ?>user_call_master/tele_caller_send_quote",
                type: 'post',
                data: {id: ids, test_city: test_city, total_price: total_price, mobile_no: mobile_no, caller_id: caller_id},
                beforeSend: function () {
                    $("#loader_div2").attr("style", "");
                    $("#send_quotte_btn").attr("disabled", "disabled");
                },
                success: function (data) {
                    var json_data = JSON.parse(data);
                    if (json_data.status == 1) {
                        $("#alert_msg").html('<div class="alert alert-success alert-dismissable" style="margin-top: 20px;"><a aria-hidden="true" data-dismiss="alert" class="close">×</a>Quote successfully send.</div>');
                        $testAjax.tokenInput("clear");
                        $("#send_quotte_btn").attr("disabled", "");
                    } else {
                        $("#alert_msg").html('<div class="alert alert-danger alert-dismissable" style="margin-top: 20px;"><a aria-hidden="true" data-dismiss="alert" class="close">×</a>Try Again.</div>');
                    }
                }, complete: function () {
                    $("#loader_div2").attr("style", "display:none;");
                    $("#send_quotte_btn").removeAttr("disabled");
                }
            });
        } else {

        }
    }

    function add_new_test() {

        var selectedValues = $testAjax.tokenInput("get");
        var ids = [];
        var pid = [];
        var names = [];
        for (var key in selectedValues) {
            var value12 = selectedValues[key];
            console.log(value12);
            //var value1 = Object.values(value12);
            var value1 = $.map(value12, function (value, index) {
                return [value];
            });
            var id = value1[0];
            var name = value1[1];
            var type = value1[2];
            console.log(id + " ids");
            ids.push(id);
            names.push(name);
            var oldids = $('#selectedid').val();
            var oldname = $('#selectedname').val();
            var x = oldids.concat(',', id);
            var y = oldname.concat('#', name);
            $('#selectedid').val(x);
            $('#test_package_id').val(x);
            $('#selectedname').val(y);
        }
        var x1 = x.split(',');
        var y1 = y.split('#');
        var x11 = [];
        $.each(x1, function (i, el) {
            if ($.inArray(el, x11) === -1)
                x11.push(el);
        });
        var y11 = [];
        $.each(y1, function (i, el) {
            if ($.inArray(el, y11) === -1)
                y11.push(el);
        });
        var newArray1 = x11.filter(function (v) {
            return v !== ''
        });
        var newArray2 = y11.filter(function (v) {
            return v !== ''
        });
        $.ajax({
            url: "<?php echo base_url(); ?>user_master/searching_test",
            type: 'post',
            data: {id: newArray1, name: newArray2},
            success: function (data) {
                window.location = "<?php echo base_url(); ?>user_master/order_search";
            }
        });
    }
</script>
<script>
    function remove_test(val) {
        if (confirm('Are you sure want to remove?')) {
            $("#li_id_" + val).remove();
            setTimeout(function () {
                var hidden_sting = '';
                var hidden_string_name = '';
                var elems = document.getElementsByClassName('myAvailableTest');
                for (var i = 0; i < elems.length; i++) {
                    var new_id = elems[i].id;
                    new_id = new_id.split("_");
                    var tag_name = elems[i].title;
                    if (i == 0) {
                        hidden_sting = hidden_sting + new_id[1];
                        hidden_string_name = hidden_string_name + tag_name;
                    } else {
                        hidden_sting = hidden_sting + "," + new_id[1];
                        hidden_string_name = hidden_string_name + "#" + tag_name;
                    }
                }
                $('#selectedid').val(hidden_sting);
                $('#selectedname').val(hidden_string_name);
                var x1 = hidden_sting.split(',');
                var y1 = hidden_string_name.split('#');
                $.ajax({
                    url: "<?php echo base_url(); ?>user_master/searching_test",
                    type: 'post',
                    data: {id: x1, name: y1},
                    success: function (data) {
                        window.location = "<?php echo base_url(); ?>user_master/order_search";
                    }
                });
            }, 1000);
        }
        ;
    }
</script>
<script>
    function add_token(id, name) {
        $testAjax.tokenInput("add", {id: id, name: name});
        $('#' + id + '_add').css("display", "none");
        $('#' + id + '_close').css("display", "block");
        $('#btn_search').html('Book');
        return false;
    }
    function remove_token(id, name) {
        $testAjax.tokenInput("remove", {id: id, name: name});
        $('#' + id + '_close').css("display", "none");
        $('#' + id + '_add').css("display", "block");
        return false;
    }
    function get_select_value() {

        var selectedValues = $testAjax.tokenInput("get");
        var ids = [];
        var pid = [];
        var names = [];
        for (var key in selectedValues) {
            var value12 = selectedValues[key];
            console.log(value12);
            //var value1 = Object.values(value12);
            var value1 = $.map(value12, function (value, index) {
                return [value];
            });
            var id = value1[0];
            var name = value1[1];
            var type = value1[2];
            ids.push(id);
            names.push(name);
        }
        console.log(value1);
        if (ids != "") {
            $.ajax({
                url: "<?php echo base_url(); ?>user_master/searching_test",
                type: 'post',
                data: {id: ids, name: names, all: selectedValues},
                success: function (data) {
                    window.location = "<?php echo base_url(); ?>user_master/order_search";
                }
            });
        } else {
        }
    }
</script> 