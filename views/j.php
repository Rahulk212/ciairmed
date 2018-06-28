
<!doctype html>
<html lang="en" xmlns="http://www.w3.org/1999/html">
    <head>
        <meta charset="utf-8">
        <title>Airmed Labs</title>
        <meta name="description" content="LiveHealth is cloud based LIMS Software - Laboratory Information Management System Software for diagnostics centers and pathology lab at best price.">
        <meta name="keywords" content="lims software, lims system, laboratory information system software, pathology lab software, pathology software">
        <meta charset="utf-8">
<link rel="shortcut icon" type="image/x-icon" href="http://websitedemo.co.in/phpdemoz/patholab/user_assets/images/fav_icon.ico" />        <meta name="theme-color" content="#20975C">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <link rel="stylesheet" href="https://d2ncx6f7m7lux2.cloudfront.net/static/assets/track.react-toolbox.css">
        <link rel="stylesheet" href="https://d2ncx6f7m7lux2.cloudfront.net/static/CSS/home.css">
        <!-- Use minimum-scale=1 to enable GPU rasterization -->
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    </head>
    <body class="body-overscroll" style="background: #F2F2F2" id="body">
        <div style="height: auto;" id="app">
            <div data-reactroot="" data-react-toolbox="card" class="theme__card___2nWQb card-track">
                <div class="card-container-track">
                    <h5 class="patient-lab-header">Report(s) of <?=ucfirst($query[0]["full_name"]);?> from Airmed Labs</h5>
                    <h5 class="header-track">You will not be able to see the reports unless the pending amount is cleared.</h5>
                    <div class="payment-disclaimer-container">
                        <div class="pay-now-track" style="height:28px;">
                            <p class="payment-info"><span>Payment Due of Rs.</span><?=$query[0]["payable_amount"]?></p>
                            
                            <p class="payment-pay"><?php if($query[0]["payable_amount"]>0){ ?><a href="<?=base_url();?>u/payumoney/<?=$cid;?>/<?=$query[0]["payable_amount"]?>">PAY NOW</a><?php } ?></p>
                            
                        </div>
                        <p class="disclaimer">Payments made online may take some time to reflect on the bill.</p>
                    </div>
                    <div class="box-pending-reports">
                    <?php foreach ($query[0]["book_test"] as $key){ ?>
                    
                        <div class="pending-track-element">
                            <p class="test-track" style="width: 100%"><?=ucfirst($key['test_name'])?></p>
                            <div class="track-status">
                                
                            </div>
                        </div>
                    <?php } ?>
                         <?php foreach ($query[0]["book_package"] as $key){ ?>
                    
                        <div class="pending-track-element">
                            <p class="test-track" style="width: 100%"><?=ucfirst($key['title'])?></p>
                            <div class="track-status">
                                
                            </div>
                        </div>
                    <?php } ?>
                    </div>
                    <div class="legends-container">
                        <div class="pending-legends">
                            <svg width="25px" height="24px" viewBox="284 1 25 24" version="1.1" xmlns="http://www.w3.org/2000/svg">
                        </div>
                    </div>   
                    <!-- react-empty: 74 -->
                </div>
            </div>
        </div>
    </body>
</html>
