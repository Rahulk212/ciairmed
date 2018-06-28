<!-- Footer -->
<footer id="footer" class="footer bg-black-222 set_footer_res">
    <div class="footer-bottom bg-black-333">
        <div class="container pt-20">
            <div class="row">
                <div class="col-md-3 col-sm-12">
                   <!-- <a class="header_logo pull-left flip xs-pull-center col-sm-12 col-md-12 pdng_0" href="<?php echo base_url(); ?>user_master"><img src="<?php echo base_url(); ?>user_assets/images/logo.png" alt=""></a>-->
                </div>
                <div class="col-md-12 col-sm-12">
                    <ul class="list-inline footer_menu">
                        <li><a href="<?php echo base_url(); ?>user_master/commercial">Press Release</a></li>
                        <li><a href="<?php echo base_url(); ?>advertisement">Advertisement</a></li>
                        <li><a href="<?php echo base_url(); ?>founders">Founders</a></li>
                        <li><a href="<?php echo base_url(); ?>user_master/all_packages">Packages</a></li>
                        <li><a href="<?php echo base_url(); ?>user_master/about_us">About us</a></li>
                        <li><a href="<?php echo base_url(); ?>user_master/partner_with_us">Partner with us</a></li>
                        <li><a href="#">Investors</a></li>
                        <?php if (isset($login_data['id'])) { ?>
                            <li><a href="<?php echo base_url(); ?>user_master/support_system">Support/Help</a></li>
                        <?php } ?>
                        <li><a href="<?php echo base_url(); ?>user_master/privacy_policy">Privacy policy</a></li>

                        <li><a href="<?php echo base_url(); ?>user_master/contact_us">Contact Us</a></li>
                        <li><a href="<?php echo base_url(); ?>user_track_report">Track Report</a></li>

                    </ul>
                </div>
            </div>

        </div>
        <div class="ftr_brdr_full" style="border:none">
            <div class="container pt-20 pb-20">
                <div class="row">
                    <div class="col-sm-12">
                      <!--  <p class="full_div text-center foot_copy">Airmed Pathology is India's fastest pathology service, creating a new benchmark for very high quality and best prices. 
                            Airmeds employs state of art 39 touchpoints technology for assuring quality collection and testing across its tightly calibrated and controlled network 
                            of labs, experienced pathologists  and phlebotomists. It offers booking of test in just 1 step. Coolpack and Barcode technology ensure safe sample transportation from heat, sunlight, contamination and human error. 
                            Safeprick<sup>TM</sup> technology ensures minimum pain by precision laser guided blood collection. 
                            It specializes in Diagnostic, Preventive and Chronic Disease Management. Its main tests include blood tests, free employee checkup, annual body check up, health test subscriptions.</p>
                        -->
                        <div class="subscribe-div">
                            <div class="form-group col-md-4 col-sm-8 col-sm-offset-1 col-md-offset-3">

                                <input name="name" class="form-control ftr_nwsltr" placeholder="Subscribe Newsletter" id="sub_news" type="text">
                                <span id="error_news" style="color:red"></span><span id="success_news" style="color:green"></span>
                            </div>
                            <div class="form-group col-md-2 col-sm-2">
                                <button type="button" onclick="subscribe_news();" class="btn btn-dark btn-theme-colored btn-flat mt_mns_15" >Subscribe</button>
                            </div>
                        </div>
                        <p class="font-13 text-black-777 m-0 text-center foot_copy">Copyright @ 2016 AirmedLabs. All rights reserved</p>
                        <?php /* <div class="widget no-border m-0">
                          <ul class="styled-icons icon-dark icon-circled icon-theme-colored icon-sm pull-center flip sm-pull-none sm-text-center mt-sm-15 set_mrgn_res fb_tw_ul"style="width:160px">
                          <li><a href="<?php echo $this->data['all_links'][0]['fb_link']; ?>" target="_blank"><i class="fa fa-facebook"></i></a></li>
                          <li><a href="<?php echo $this->data['all_links'][0]['tw_link']; ?>" target="_blank"><i class="fa fa-twitter"></i></a></li>
                          <li><a href="<?php echo $this->data['all_links'][0]['insta_link']; ?>" target="_blank"><i class="fa fa-instagram"></i></a></li>
                          <li><a href="<?php echo $this->data['all_links'][0]['linkedin_link']; ?>" target="_blank"><i class="fa fa-linkedin"></i></a></li>
                          </ul>
                          </div> */ ?>
                        <style>
                            .pull-center{margin:0 auto; float:none}
                        </style>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>
<a class="scrollToTop" href="#"><i style="line-height: 1;" class="fa fa-angle-up"></i></a>
</div>
<!-- end wrapper -->
<!-- Footer Scripts -->
<!-- JS | jquery plugin collection for this theme -->
<script src="<?php echo base_url(); ?>user_assets/js/jquery-plugin-collection.js"></script>
<!-- JS | Custom script for all pages -->
<script src="<?php echo base_url(); ?>user_assets/js/custom.js"></script>

<!-- Revolution Slider 5.x SCRIPTS -->
<script src="<?php echo base_url(); ?>user_assets/js/revolution-slider/js/jquery.themepunch.tools.min.js"></script>
<script src="<?php echo base_url(); ?>user_assets/js/revolution-slider/js/jquery.themepunch.revolution.min.js"></script>

<?php /*

  <!-- SLIDER REVOLUTION 5.0 EXTENSIONS  -->
  <script type="text/javascript" src="<?php echo base_url(); ?>user_assets/js/revolution-slider/js/extensions/revolution.extension.actions.min.js"></script>
  <script type="text/javascript" src="<?php echo base_url(); ?>user_assets/js/revolution-slider/js/extensions/revolution.extension.carousel.min.js"></script>
  <script type="text/javascript" src="<?php echo base_url(); ?>user_assets/js/revolution-slider/js/extensions/revolution.extension.kenburn.min.js"></script>
  <script type="text/javascript" src="<?php echo base_url(); ?>user_assets/js/revolution-slider/js/extensions/revolution.extension.layeranimation.min.js"></script>
  <script type="text/javascript" src="<?php echo base_url(); ?>user_assets/js/revolution-slider/js/extensions/revolution.extension.migration.min.js"></script>
  <script type="text/javascript" src="<?php echo base_url(); ?>user_assets/js/revolution-slider/js/extensions/revolution.extension.navigation.min.js"></script>
  <script type="text/javascript" src="<?php echo base_url(); ?>user_assets/js/revolution-slider/js/extensions/revolution.extension.parallax.min.js"></script>
  <script type="text/javascript" src="<?php echo base_url(); ?>user_assets/js/revolution-slider/js/extensions/revolution.extension.slideanims.min.js"></script>
  <script type="text/javascript" src="<?php echo base_url(); ?>user_assets/js/revolution-slider/js/extensions/revolution.extension.video.min.js"></script>
 */ ?>

<!-- Footer Custom Scripts  -->
<script type="text/javascript">
                                    function subscribe_news() {
                                        var sub_news = $("#sub_news").val();
                                        $("#error_news").html("");
                                        $("#success_news").html("");
                                        if (checkemail(sub_news) == false) {
                                            $("#error_news").html("Invalid Email.");
                                            return false;
                                        }
                                        $.ajax({
                                            url: "<?php echo base_url(); ?>user_master/sub_news",
                                            type: 'post',
                                            data: {email: sub_news},
                                            success: function (data) {
                                                data = data.trim();
                                                if (data == 0) {
                                                    $("#error_news").html("You have successfully subscribed to the newsletter.");
                                                }
                                                if (data == 1) {
                                                    $("#success_news").html("Successfully Subscribe Newsletter.");
                                                    $("#sub_news").val("");
                                                }
                                            }
                                        });
                                    }
                                    function checkemail(mail) {
                                        //var str=document.validation.emailcheck.value
                                        var filter = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i
                                        if (filter.test(mail)) {
                                            return true;
                                        } else {
                                            return false;
                                        }
                                    }
                                    window.fbAsyncInit = function () {
                                        FB.init({
                                            appId: '156557021428302', // replace your app id here
                                            channelUrl: '<?= base_url(); ?>',
                                            status: true,
                                            cookie: true,
                                            xfbml: true
                                        });
                                    };
                                    (function (d) {
                                        var js, id = 'facebook-jssdk', ref = d.getElementsByTagName('script')[0];
                                        if (d.getElementById(id)) {
                                            return;
                                        }
                                        js = d.createElement('script');
                                        js.id = id;
                                        js.async = true;
                                        js.src = "//connect.facebook.net/en_US/all.js";
                                        ref.parentNode.insertBefore(js, ref);
                                    }(document));
                                    function FBLogin() {
                                        FB.login(function (response) {
                                                if (response.authResponse) {
                                                   var accessToken = response.authResponse.accessToken;
                                                    window.location.href = "<?php echo base_url(); ?>user_login/fb_login/fblogin/" + accessToken;
                                                } else {
                                                    window.location.href = "<?php echo base_url(); ?>user_login";
                                                }
                                            }, {scope: 'email,user_likes'});
                                    }
</script>
<script>
    $(document).on('change', '.btn-file :file', function () {
        var input = $(this),
                numFiles = input.get(0).files ? input.get(0).files.length : 1,
                label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
        input.trigger('fileselect', [numFiles, label]);
    });
    $(document).ready(function () {
        $('.btn-file :file').on('fileselect', function (event, numFiles, label) {

            var input = $(this).parents('.input-group').find(':text'),
                    log = numFiles > 1 ? numFiles + ' files selected' : label;
            if (input.length) {
                input.val(log);
            } else {
                // if (log) alert(log);
            }
        });
    });</script>
<script>
    $(document).ready(function (e) {
        //Revolution Slider
        $(".rev_slider").revolution({
            sliderType: "standard",
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
                    style: "zeus",
                    enable: true,
                    hide_onmobile: true,
                    hide_under: 600,
                    hide_onleave: true,
                    hide_delay: 200,
                    hide_delay_mobile: 1200,
                    tmp: '<div class="tp-title-wrap">    <div class="tp-arr-imgholder"></div> </div>',
                    left: {
                        h_align: "left",
                        v_align: "center",
                        h_offset: 30,
                        v_offset: 0
                    },
                    right: {
                        h_align: "right",
                        v_align: "center",
                        h_offset: 30,
                        v_offset: 0
                    }
                },
                bullets: {
                    enable: true,
                    hide_onmobile: true,
                    hide_under: 600,
                    style: "metis",
                    hide_onleave: true,
                    hide_delay: 200,
                    hide_delay_mobile: 1200,
                    direction: "horizontal",
                    h_align: "center",
                    v_align: "bottom",
                    h_offset: 0,
                    v_offset: 30,
                    space: 5,
                    tmp: '<span class="tp-bullet-img-wrap">  <span class="tp-bullet-image"></span></span><span class="tp-bullet-title">{{title}}</span>'
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
            submitHandler: function (form) {
                var form_btn = $(form).find('button[type="submit"]');
                var form_result_div = '#form-result';
                $(form_result_div).remove();
                form_btn.before('<div id="form-result" class="alert alert-success" role="alert" style="display: none;"></div>');
                var form_btn_old_msg = form_btn.html();
                form_btn.html(form_btn.prop('disabled', true).data("loading-text"));
                $(form).ajaxSubmit({
                    dataType: 'json',
                    success: function (data) {
                        if (data.status == 'true') {
                            $(form).find('.form-control').val('');
                        }
                        form_btn.prop('disabled', false).html(form_btn_old_msg);
                        $(form_result_div).html(data.message).fadeIn('slow');
                        setTimeout(function () {
                            $(form_result_div).fadeOut('slow')
                        }, 6000);
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


    });</script>
<!--AUTO INPUT-->
</body>
<script>
    $('.menuBtn').click(function (e) {
        e.stopPropagation();
        $('.navMenuSecWrapper').fadeToggle();
        return false;
    });
    $(document).click(function () {
        $('.navMenuSecWrapper').fadeOut();
    });</script>
<script>
    function signOut() {

        var auth2 = gapi.auth2.getAuthInstance();
        auth2.signOut().then(function () {
            console.log('User signed out.');
        });
    }
</script>
<script>
    function onSignIn(googleUser) {
        var profile = googleUser.getBasicProfile();
//  console.log('ID: ' + profile.getId()); // Do not send to your backend! Use an ID token instead.
        // console.log('Name: ' + profile.getName());
        //console.log('Image URL: ' + profile.getImageUrl());
        //console.log('Email: ' + profile.getEmail());
        var id = profile.getId();
        var name = profile.getName();
        var image = profile.getImageUrl();
        var email = profile.getEmail();
<?php if (!isset($login_data['id'])) { ?>
            $.ajax({
                url: "<?php echo base_url(); ?>user_login/google_login",
                type: 'post',
                data: {id: id, name: name, email: email, image: image},
                success: function (data) {
                    //     console.log("data"+data);
                    var jsondata = JSON.parse(data);
                    if (jsondata.status == '1') {
                        var auth2 = gapi.auth2.getAuthInstance();
                        auth2.signOut().then(function () {
                            console.log('User signed out.');
                        });
                        window.location = "<?php echo base_url(); ?>user_master";
                    }
                    if (jsondata.status == '0') {
                        var auth2 = gapi.auth2.getAuthInstance();
                        auth2.signOut().then(function () {
                            console.log('User signed out.');
                        });
                        window.location = "<?php echo base_url(); ?>Register/varify_phone1/" + jsondata.id;
                    }
                    //window.location = "<?php echo base_url(); ?>user_master";
                } 
            });
<?php } ?>

    }

</script>
<!-- FlexSlider
  <script defer src="<?php echo base_url(); ?>user_assets/css/testi/jquery.flexslider.js"></script>

  <script type="text/javascript">
    $(function(){
      SyntaxHighlighter.all();
    });
    $(window).load(function(){
      $('.flexslider').flexslider({
       animationDuration: 1100,
    directionNav: false,
    controlNav: true,
    pausePlay: true,
    pauseText: 'Pause',
    playText: 'Play',
       
      });
    });
  </script>-->
<div class="g-signin2" style="display:none" data-onsuccess="onSignIn"></div>
<style>
    .token-input-input-token-facebook > input {
        min-width: 70px;
    }
    .token-input-dropdown-facebook p{
        display:none;
    }
</style>
<script>
    (function (i, s, o, g, r, a, m) {
        i['GoogleAnalyticsObject'] = r;
        i[r] = i[r] || function () {
            (i[r].q = i[r].q || []).push(arguments)
        }, i[r].l = 1 * new Date();
        a = s.createElement(o),
                m = s.getElementsByTagName(o)[0];
        a.async = 1;
        a.src = g;
        m.parentNode.insertBefore(a, m)
    })(window, document, 'script', 'https://www.google-analytics.com/analytics.js', 'ga');

    ga('create', 'UA-87341643-1', 'auto');
    ga('send', 'pageview');

</script>
<!--AUTO INPUT-->


</body>
</html>
