<!--<style>
    .chosen-container {
        display: inline-block;
        font-size: 14px;
        position: relative;
        vertical-align: middle;
        width: 100%;
    }
</style>
-->
<select class="chosen-select" data-live-search="true" id="test" data-placeholder="Select Test" onchange="get_test_price();">
    <option value="">--Select Test--</option>
    <?php foreach ($test as $ts) { ?>
        <option value="t-<?php echo $ts['id']; ?>" <?php ?>><?php echo ucfirst($ts['test_name']); ?> (Rs.<?php echo $ts['price']; ?>)</option>
    <?php } ?>
    <?php foreach ($package as $p_key) { ?>
        <option value="p-<?php echo $p_key['id']; ?>" <?php ?>><?php echo ucfirst($p_key['title']); ?> (Rs.<?php echo $p_key['d_price1']; ?>)</option>
    <?php } ?>
</select>
<script  type="text/javascript">

    jQuery(".chosen-select").chosen({
    });
</script>