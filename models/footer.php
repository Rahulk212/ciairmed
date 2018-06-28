 <!-- Footer -->
  <footer id="footer" class="footer bg-black-222">
    <div class="footer-bottom bg-black-333">
      <div class="container pt-20 pb-20">
        <div class="row">
         <div class="col-md-6 col-sm-6">
            <p class="font-13 text-black-777 m-0 text-center foot_copy">Copyright @ 2016 Company Name. All rights reserved | Powered by <a href="http://www.web30india.com">web30India.</a></p>
          </div>
		  <div class="col-md-6 col-sm-6">
			<ul class="list-inline footer_menu">
			  <li><a href="privacy_policy.html">Privacy policy</a></li>
            </ul>
			</div>
        </div>
      </div>
    </div>
  </footer>
  <a class="scrollToTop" href="#"><i class="fa fa-angle-up"></i></a>
</div>
<!-- end wrapper -->

<!-- Footer Scripts -->


<!-- JS | jquery plugin collection for this theme -->
<script src="<?php echo base_url();?>user_assets/js/jquery-plugin-collection.js"></script>
<!-- JS | Custom script for all pages -->
<script src="<?php echo base_url();?>user_assets/js/custom.js"></script>

<!-- Revolution Slider 5.x SCRIPTS -->
<script src="<?php echo base_url();?>user_assets/js/revolution-slider/js/jquery.themepunch.tools.min.js"></script>
<script src="<?php echo base_url();?>user_assets/js/revolution-slider/js/jquery.themepunch.revolution.min.js"></script>



<!-- SLIDER REVOLUTION 5.0 EXTENSIONS  --> 
<script type="text/javascript" src="<?php echo base_url();?>user_assets/js/revolution-slider/js/extensions/revolution.extension.actions.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>user_assets/js/revolution-slider/js/extensions/revolution.extension.carousel.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>user_assets/js/revolution-slider/js/extensions/revolution.extension.kenburn.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>user_assets/js/revolution-slider/js/extensions/revolution.extension.layeranimation.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>user_assets/js/revolution-slider/js/extensions/revolution.extension.migration.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>user_assets/js/revolution-slider/js/extensions/revolution.extension.navigation.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>user_assets/js/revolution-slider/js/extensions/revolution.extension.parallax.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>user_assets/js/revolution-slider/js/extensions/revolution.extension.slideanims.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>user_assets/js/revolution-slider/js/extensions/revolution.extension.video.min.js"></script>


<!-- Footer Custom Scripts  -->
<script type="text/javascript">
window.fbAsyncInit = function() {
    FB.init({
    appId      : '156557021428302', // replace your app id here
    channelUrl : '<?= base_url();?>',
    status     : true,
    cookie     : true,
    xfbml      : true 
    });
};
(function(d){
    var js, id = 'facebook-jssdk', ref = d.getElementsByTagName('script')[0];
    if (d.getElementById(id)) {return;}
    js = d.createElement('script'); js.id = id; js.async = true;
    js.src = "//connect.facebook.net/en_US/all.js";
    ref.parentNode.insertBefore(js, ref);
}(document));

function FBLogin(){
    FB.login(function(response){
        if(response.authResponse){
//            window.location.href = "actions.php?action=fblogin";
//var link = document.getElementById("fblink").value;

                        window.location.href = "<?php echo base_url(); ?>user_login/fb_login/fblogin";
        }
    }, {scope: 'email,user_likes'});
}
</script>

<script>
  $(document).ready(function(e) {
    //Revolution Slider
    $(".rev_slider").revolution({
      sliderType:"standard",
      sliderLayout: "auto",
      dottedOverlay: "none",
      delay: 5000,
      navigation: {
          keyboardNavigation: "off",
          keyboard_direction: "horizontal",
          mouseScrollNavigation: "off",
          onHoverStop: "off",
          touch: {
              touchenabled: "on",
              swipe_threshold: 75,
              swipe_min_touches: 1,
              swipe_direction: "horizontal",
              drag_block_vertical: false
          },
        arrows: {
          style:"zeus",
          enable:true,
          hide_onmobile:true,
          hide_under:600,
          hide_onleave:true,
          hide_delay:200,
          hide_delay_mobile:1200,
          tmp:'<div class="tp-title-wrap">    <div class="tp-arr-imgholder"></div> </div>',
          left: {
            h_align:"left",
            v_align:"center",
            h_offset:30,
            v_offset:0
          },
          right: {
            h_align:"right",
            v_align:"center",
            h_offset:30,
            v_offset:0
          }
        },
        bullets: {
          enable:true,
          hide_onmobile:true,
          hide_under:600,
          style:"metis",
          hide_onleave:true,
          hide_delay:200,
          hide_delay_mobile:1200,
          direction:"horizontal",
          h_align:"center",
          v_align:"bottom",
          h_offset:0,
          v_offset:30,
          space:5,
          tmp:'<span class="tp-bullet-img-wrap">  <span class="tp-bullet-image"></span></span><span class="tp-bullet-title">{{title}}</span>'
        }
      },
      responsiveLevels: [1240, 1024, 778],
      visibilityLevels: [1240, 1024, 778],
      gridwidth: [1170, 1024, 778, 480],
      gridheight: [700, 768, 960, 720],
      lazyType: "none",
      parallax: {
          origo: "slidercenter",
          speed: 1000,
          levels: [5, 10, 15, 20, 25, 30, 35, 40, 45, 46, 47, 48, 49, 50, 100, 55],
          type: "scroll"
      },
      shadow: 0,
      spinner: "off",
      stopLoop: "on",
      stopAfterLoops: 0,
      stopAtSlide: -1,
      shuffle: "off",
      autoHeight: "off",
      fullScreenAutoWidth: "off",
      fullScreenAlignForce: "off",
      fullScreenOffsetContainer: "",
      fullScreenOffset: "0",
      hideThumbsOnMobile: "off",
      hideSliderAtLimit: 0,
      hideCaptionAtLimit: 0,
      hideAllCaptionAtLilmit: 0,
      debugMode: false,
      fallbacks: {
          simplifyAll: "off",
          nextSlideOnWindowFocus: "off",
          disableFocusListener: false,
      }
    });

    //Appointment Form Validation
    $("#appointment_form_at_home").validate({
      submitHandler: function(form) {
        var form_btn = $(form).find('button[type="submit"]');
        var form_result_div = '#form-result';
        $(form_result_div).remove();
        form_btn.before('<div id="form-result" class="alert alert-success" role="alert" style="display: none;"></div>');
        var form_btn_old_msg = form_btn.html();
        form_btn.html(form_btn.prop('disabled', true).data("loading-text"));
        $(form).ajaxSubmit({
          dataType:  'json',
          success: function(data) {
            if( data.status == 'true' ) {
              $(form).find('.form-control').val('');
            }
            form_btn.prop('disabled', false).html(form_btn_old_msg);
            $(form_result_div).html(data.message).fadeIn('slow');
            setTimeout(function(){ $(form_result_div).fadeOut('slow') }, 6000);
          }
        });
      }
    });
   
    //Mailchimp Subscription Form Validation
    $('#mailchimp-subscription-form-footer').ajaxChimp({
        callback: mailChimpCallBack,
        url: '//thememascot.us9.list-manage.com/subscribe/post?u=a01f440178e35febc8cf4e51f&amp;id=49d6d30e1e'
    });

    function mailChimpCallBack(resp) {
        // Hide any previous response text
        var $mailchimpform = $('#mailchimp-subscription-form-footer'),
            $response = '';
        $mailchimpform.children(".alert").remove();
        console.log(resp);
        if (resp.result === 'success') {
            $response = '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' + resp.msg + '</div>';
        } else if (resp.result === 'error') {
            $response = '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' + resp.msg + '</div>';
        }
        $mailchimpform.prepend($response);
    }

    
  });
</script>
</body>
</html>