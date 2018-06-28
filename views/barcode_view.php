<div id="printBarcodePage" style="margin-left:60px;font-size:12px;">
    <style>
        div.b128{
            border-left: 1px black solid; 
            height:20px;
        }

    </style>
    <span style="font-size:10px"><b>  <?= $patient_name; ?></b></span></br>
	 <span style="font-size:8px"><b>  <?= $patient_gender; ?>/<?= $patient_age; ?>&nbsp;<?= $age_type ?> </b></span>
    </br> 
   <span style="font-size:8px"><?= $barcode; ?></<span >
    <span style="font-size:8px"><?= ucfirst($doctor); ?></span >
</div>
<script>
    window.print();
    window.close();
</script>