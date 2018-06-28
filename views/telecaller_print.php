<html>
    <head>
        <title></title>
        <!-- Latest compiled and minified CSS -->
        <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">

        <!-- jQuery library -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>

        <!-- Latest compiled JavaScript -->
        <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
        <style>
            .success_msg_div{border:1px solid #D01130; float:left; width:100%; margin:20px 0; padding:20px;box-shadow: 1px 1px 10px #ccc;}
            .ack_invc_btn{width:100%; float:left;margin-top:20px;}
            .ack_invc_btn ul{width:100%; float:left;text-align:center; }
            .ack_invc_btn ul li{display:inline-block;}
            .ack_invc_btn ul li a{background-color: #D01130; border-color: #D01130;border-radius:5px; padding:7px 20px; color:#fff; text-transform:capitalize; font-weight:bold; font-size:18px;    float: left;}
            .ack_invc_btn ul li a:hover{background-color: #dc1d3c; border-color: #dc1d3c; text-decoration:none;}
            .ack_invc_btn ul li span{font-size:25px; margin:0 20px;}
            .back_rgstr_btn{width:100%; float:left; text-align:center;}
            .back_rgstr_btn a{background-color: #D01130; border-color: #D01130;border-radius:5px; padding:7px 20px; color:#fff; text-transform:capitalize; font-weight:bold; font-size:18px;    display: inline-block;}
            .back_rgstr_btn a:hover{background-color: #dc1d3c; border-color: #dc1d3c; text-decoration:none;}
            .print_msg{width:100%; float:left; text-align:center;}
            .alert{padding: 10px 15px;}
            .alert-success{font-size: 21px;}
            .ack_invc_btn ul li span {font-size: 20px; margin: 0 20px; border-radius: 50%;border: 6px double #ccc; padding: 5px;float: left; width: 50px; height: 50px;line-height: 29px;}

        </style>

    </head>

    <body>	
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="success_msg_div">
                        <div class="alert alert-success">
                            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                            <strong>Success!</strong> Your Booking is Successfully Done.
                        </div>
                        <div class="print_msg">
                            <h2>Do you wont to Print?</h2>
                        </div>
                        <div class="ack_invc_btn">
                            <ul class="inline-block">
                                <li>
                                    <a href="#">Acknowledgment</a>
                                </li>
                                <li><span>OR</span></li>
                                <li>
                                    <a href="#">Invoice</a>
                                </li>
                        </div>
                        <div class="back_rgstr_btn">
                            <a href="#"><i class="fa fa-pencil-square-o"></i>Back To Register</a>
                        </div>
                    </div>	
                </div>
            </div>
        </div>
    </body>
</html>