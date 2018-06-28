<style>
    .chosen-container {
        display: inline-block;
        font-size: 14px;
        position: relative;
        vertical-align: middle;
        width: 100%;
    }
</style>
<link href="<?php echo base_url(); ?>user_assets/chosen/select.css" rel="stylesheet" type="text/css" media="all" />

<select class="form-control chosen-container"  data-live-search="true" id="test" data-placeholder="Select Test">
    <option value="">--Select Test--</option>
    <?php foreach ($test as $ts) { ?>
        <option value="t-<?php echo $ts['id']; ?>" <?php ?>> <?php echo ucfirst($ts['test_name']); ?> (Rs.<?php echo $ts['price']; ?>)</option>
    <?php } ?>
    <?php foreach ($package as $p_key) { ?>
        <option value="p-<?php echo $p_key['id']; ?>" <?php ?>> <?php echo ucfirst($p_key['title']); ?> (Rs.<?php echo $p_key['d_price']; ?>)</option>
    <?php } ?>
</select>
<script type="text/javascript" src="<?php echo base_url(); ?>user_assets/chosen/chosen.jquery.js"></script>
<script  type="text/javascript">

    jQuery("#test").chosen({
        search_contains: true
    });
</script> 