<?php
/**
 * Created by PhpStorm.
 * User: Mv
 * Date: 12/29/2017
 * Time: 7:26 PM
 */

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Mail-Chimp</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="./css/bootstrap.css">
    <link rel="stylesheet" href="./css/style.css">
    <link rel="shortcut icon" href="">
    <script src="./js/jquery.js"></script>
    <script src="./js/bootstrap.js"></script>
    <script src="./js/custom.js"></script>
    <script src='./js/notify.min.js'></script>
</head>
<body>
<div class="inner_page">
    <div class="load-bar inner_loader" id="loader" style="display: none">
        <div class="bar"></div>
        <div class="bar"></div>
    </div>
    <div class="container">
        <div class="col-sm-12 col-lg-12 col-md-12 content-wrapper">
            <div class="table-title">
                <h2 class="title-text">Mailchimp API Integration</h2>
            </div>
            <div class="body-card">
                <div class="subcription-div">

                </div>
                <div>
                    <div class="list-available" id="mailchimp_lists" style="display: none;">

                    </div>
                </div>
                <div>
                    <div class="list-available" id="user_status" style="display: none;">

                    </div>
                </div>
            </div>

        </div>
    </div>
</div>


<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Please Fill Subcription Form</h4>
            </div>
            <div class="modal-body">
                <form id="subcription_form">
                    <div class="form-group">

                        <input class="form-control" type="text" name="user_email" id="user_email" placeholder="Enter email address*" required>
                    </div>
                    <div class="form-group">

                        <input class="form-control" type="text" name="fname" id="fname" placeholder="Enter First Name">
                    </div>
                    <div class="form-group">

                        <input class="form-control" type="text" name="lname" id="lname" placeholder="Enter Last Name">
                    </div>
                    <div class="form-group">

                        <button class="form-control" type="button" onclick="subcribeUserMail()">
                            Subcribe
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>
</body>
</html>
