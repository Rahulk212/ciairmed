<section class="mukam-waypoint" data-animate-down="mukam-header-small header-3" data-animate-up="mukam-header-large header-3">
      <div class="caption-out fadein scaleInv anim_2">
    <div class="container">
      <div class="row">
        <div class="col-md-8 caption">
          <h3>المشاريع تحت الانشاء</h3>
        </div>
        <div class="col-md-4 breadcrumb fadein scaleInv anim_5">
        <ul><li><a href="index.html">الصفحة الرئيسية</a></li><li>/تقدم البناء</li></ul>
        </div>
      </div>
    </div>
    </div>
	<div class="bg-color white">
		<div class="container">
		<!--<h3 class="mukam-title mrgn_top_0">Completed <span>Projects</span></h3>-->
			<div class="row">
				<div class="col-sm-12">
					 <div id="wrapper">
                <div id="steps">
                    <form id="formElem" name="formElem" action="<?=base_url();?>user/calculation" method="post">
                        <fieldset class="step">
                            <legend>تقدم البناء</legend>
                            <div class="radio1">

							<span class="first_radio">
                      
							</span>

                            
								
                            
							<p class="submit" style="font-size:40px">
                                                            <b>قريبا...</b>
                                                            <br>
                                                            
                            </p>

							</div><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
                        </fieldset>
                        
                    </form>
                </div>
                <div id="navigation" style="display:none;">
                    <ul style="display:none;">
                        <li class="selected">
                            <a href="#">شكرا</a>
                        </li>
                        <li>
                            <a href="#" id="location1">موقع</a>
                        </li>
                        <li>
                            <a href="#" id="land">حجم الأرض</a>
                        </li>
                        <li>
                            <a href="#" id="Structural">الأعمال الإنشائية</a>
                        </li>
						<li>
                            <a href="#" id="Finishing">الانتهاء من العمل</a>
                        </li>
			<li>
                            <a href="#" id="Exterior">الخارج العمل</a>
                        </li>
						<li>
                            <a href="#" id="Information">معلومات</a>
                        </li>
                    </ul>
                </div>
            </div>
				</div>
			</div>
		</div>
	</div>
	
</section>
<script>
function get_house_subcat(val){
$.ajax({
   url: '<?=base_url();?>user/get_house_subcat/'+val,
   
   error: function() {
      alert("Try again!.");
   },
      success: function(data) {
   	$("#home_select").html(data);
	
   },
});
}

function get_town(val){
$.ajax({
   url: '<?=base_url();?>user/get_town_cat/'+val,
   
   error: function() {
      alert("Try again!.");
   },
      success: function(data) {
   	$("#select_town").html(data);
$("#select_ne").html("<label for='region'>Neighbourhood</label><select name='town' onchange='get_town_subcat(this.value);'><option value=''>--Select--</option></select>");
   },
});
}
function get_town_subcat(val){
//alert("hiii");
$.ajax({
   url: '<?=base_url();?>user/get_town_subcat/'+val,
   
   error: function() {
  //    alert("Try again!.");
   },
      success: function(data) {
   	$("#select_ne").html(data);
   },
});
}


</script>