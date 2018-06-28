<div class="main-content">
    <!-- Section: home -->
    <section>
		<div class="container">
		<div class="col-md-12 col-sm-12">
			<div class="row">
				<?php foreach($health_feed as $key){
				?>
				<div class="col-md-6 col-sm-6 helth_feed">
					<div class="main_helth_feed helth_feed_content">
						<div class="col-md-12 col-sm-12 pdng_0">
						<?php
date_default_timezone_set('Asia/Calcutta'); 
$datetime1 = date("Y-m-d H:i:s");
$datetime1 = new DateTime($datetime1);
$datetime2 = new DateTime($key['created_date']);
$interval = $datetime1->diff($datetime2);
if ($interval->format('%d') < 1) {
if ($interval->format('%h') > 1) {
$elapsed = $interval->format(' %h hours');
} else if ($interval->format('%i') > 1) {
$elapsed = $interval->format(' %i minutes');
} else {
$elapsed = "Just Now";
}
} else {
$elapsed = date("Y-m-d", strtotime($key['created_date']));
}
?>
							<p>Posted <?php echo $elapsed;?> ago </p>
							<h3><?php echo ucfirst($key['title']); ?></h3>
						</div>
						<div class="col-md-12 col-sm-12 helth_feed pdng_0">
							<img src="<?php echo base_url();?>upload/health_feed/<?php echo $key['image']; ?>">
						</div>
						<div class="col-md-12 col-sm-12 start_quiz pdng_0">
							<h3><?php echo substr($key['desc'],0,100); ?>..</h3>
							<button class="btn start_quiz" type="submit">Read More</button>
						</div>
					</div>
				</div>
				<?php } ?>
				
				
				
			</div>
			</div>	
		</div>
    </section>