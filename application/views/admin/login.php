<!DOCTYPE html>
<html>

    <head>

        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <title><?= SITE_TITLE ?> | Login</title>
        <link rel="shortcut icon" href="<?= base_url() ?>web_assets/images/favicon.png" type="image/x-icon" />
        <link href="<?= base_url() ?>admin_assets/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" type="text/css" href="<?= base_url() ?>web_assets/fonts/f5/css/all.min.css">

        <link href="<?= base_url() ?>admin_assets/css/animate.css" rel="stylesheet">
        <link href="<?= base_url() ?>admin_assets/css/style.css?r=<?= time() ?>" rel="stylesheet">
        <link href="<?= base_url() ?>admin_assets/css/login.css?r=<?= time() ?>" rel="stylesheet">

    </head>
    <body class="login-bg" style="">
        <div class="login d-flex align-items-center">
            <div class="container">
                <div class="row d-flex align-items-center justify-content-center">
                    <div class="col-lg-5 col-md-12 align-self-center">
                        <div class="bg-color-white login-box">
                            <div class="form-section">
                                <h3>Welcome to <?= SITE_TITLE ?></h3>
                                <hr>
                                <div class="text-danger">
                                    <?php echo validation_errors(); ?>
                                    <?php
                                    if ($this->session->flashdata('type') == 'log') {
                                        echo "<br /><br /><h4 align='center' style='color:#FF0000;'>Invalid Login Details</h4>";
                                    }
                                    if ($this->session->flashdata('type') == 'inactive') {
                                        echo "<br /><br /><h4 align='center' style='color:#FF0000;'>Your Account is inactive.</h4>";
                                    }
                                    if ($this->session->flashdata('type') == 'timeout') {
                                        echo "<br /><br /><h4 align='center' style='color:#FF0000;'>Session Time Out.To Login Again Type your user name and password to Login</h4>";
                                    }
                                    if ($this->session->flashdata('type') == 'lout') {
                                        echo "<br /><br /><h4 align='center' style='color:#FF0000;'>You Have Successfully Logged Out.</h4>";
                                    }
                                    if ($this->session->flashdata('type') == 'location') {
                                        echo "<br /><br /><h4 align='center' style='color:#FF0000;'>You Are Not authorized To Login From This Computer.</h4>";
                                    }
                                    ?>
                                </div>
                                <div class="login-inner-form">
                                    <form class="m-t" role="form" id="loginForm" method="POST">
                                        <?php if ($this->input->get_post("otp_session_code")) { ?>
                                            <div class="form-group form-box">

                                                <input type="hidden" class="input-text" name="otp_session_code" placeholder="OTP Session Code" required="" value="<?= $this->input->get_post("otp_session_code") ?>">
                                            </div>
                                            <div class="form-group form-box">

                                                <input type="password" class="input-text" name="otp_code" placeholder="Enter OTP" required="">
                                                <i class="fal fa-lock-alt"></i>

                                            </div>

                                            <div class="resend-otp">
                                                <a onclick="location.href = location.href + '&resend_otp=true'"> <i class="fal fa-redo"></i> Resend OTP</a>
                                            </div>
                                        <?php } else { ?>
                                            <div class="form-group form-box">

                                                <input type="email" class="input-text" name="username" placeholder="Username" required="">
                                                <i class="fal fa-envelope"></i>
                                            </div>
                                            <div class="form-group form-box">
                                                <input type="password" class="input-text" name="password" placeholder="Password" required="">
                                                <i class="fal fa-lock-alt"></i>
                                            </div>
                                        <?php } ?>
                                        <div class="form-group mb-0">
                                            <button type="submit" class="btn-md btn-theme btn-block">Login</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>

        <!-- Mainly scripts -->
        <script src="<?= base_url() ?>admin_assets/js/jquery-2.1.1.js"></script>
        <script src="<?= base_url() ?>admin_assets/js/bootstrap.min.js"></script>
        <script src="<?= base_url() ?>admin_assets/js/loadingoverlay_progress.min.js"></script>
        <script>
                                                $(document).on("submit", "#loginForm", function () {
                                                    $.LoadingOverlay("show");
                                                });
        </script>
    </body>

</html>
