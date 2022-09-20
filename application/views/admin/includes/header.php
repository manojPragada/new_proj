<!DOCTYPE html>
<html>
    <?php
    $this->benchmark->mark('code_start');
    ?>
    <head>

        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">

        <title><?= SITE_TITLE ?> | Dashboard</title>

        <link href="<?= base_url() ?>admin_assets/css/bootstrap.min.css" rel="stylesheet">
        <link href="<?= base_url() ?>admin_assets/font-awesome/css/font-awesome.css" rel="stylesheet">

        <!-- Toastr style -->
        <link href="<?= base_url() ?>admin_assets/css/plugins/toastr/toastr.min.css" rel="stylesheet">

        <!-- Gritter -->
        <link href="<?= base_url() ?>admin_assets/js/plugins/gritter/jquery.gritter.css" rel="stylesheet">

        <link href="<?= base_url() ?>admin_assets/css/animate.css" rel="stylesheet">
        <link href="<?= base_url() ?>admin_assets/css/style.css" rel="stylesheet">
        <link href="<?= base_url() ?>admin_assets/css/jquery.fancybox.css" rel="stylesheet">
        <link href="<?= base_url() ?>admin_assets/css/jquery.datetimepicker.css" rel="stylesheet">
        <link href="<?= base_url() ?>admin_assets/css/chosen.min.css" rel="stylesheet">
        <link href="<?= base_url() ?>admin_assets/css/plugins/dataTables/datatables.min.css" rel="stylesheet">

        <link href="<?= base_url() ?>admin_assets/css/plugins/bootstrap-tagsinput/bootstrap-tagsinput.css" rel="stylesheet">



        <script src="<?= base_url() ?>admin_assets/js/jquery-2.1.1.js"></script>
        <style>
            em.invalid{
                color:red;
            }
        </style>
        <script>
            //jquery image preview and file validation code
            $(document).ready(function () {
                $('.btnRmv').hide();
                var max_file_upload_size = 1000000;//1mb
                $(document).on('change', '.show_selected_image_preview', function (e) {
                    var t = $(this);

                    if (max_file_upload_size < this.files[0].size) {
                        alert("Allowed file size exceeded. (Max. 1 MB)")
                        t.parent('span').find('img').hide();
                        t.parent('span').find('button').hide();
                        t.parent('span').find('input').val('');
                        return false;
                    }

                    if (this.files && this.files[0]) {
                        var reader = new FileReader();
                        reader.onload = function (e) {
                            t.parent('span').find('img').attr('src', e.target.result);
                            t.parent('span').find('a').attr('href', e.target.result);
                            t.parent('span').find('img').show();
                            t.parent('span').find('.btnRmv').show();
                            t.parent('span').find('strong').hide();
                        }
                        reader.readAsDataURL(this.files[0]);
                    }
                });

                $(document).on('click', '.btnRmv, .btnRmvUpdate', function (e) {
                    e.preventDefault();
                    $(this).parent('span').find('img').hide();
                    $(this).hide();
                    $(this).parent('span').find('input').val('');
                });
            });
        </script>
    </head>
    <body>
        <div id="wrapper">

            <nav class="navbar-default navbar-static-side" role="navigation">
                <div class="sidebar-collapse">
                    <ul class="nav metismenu" id="side-menu">
                        <li class="nav-header">
                            <div class="dropdown profile-element"> <span>
                                </span>
                                <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                                    <span class="clear"> <span class="block m-t-xs"> <strong class="font-bold"><?= SITE_TITLE ?></strong>
                                        </span>
                                    </span> </a>
                            </div>
                            <div class="logo-element">
                                <i class="fa fa-anchor"></i>
                            </div>
                        </li>
                        <li class="<?= $this->uri->segment($this->uri->total_segments()) == "dashboard" ? "active" : "" ?>">
                            <a href="<?= base_url() ?>admin/dashboard"><i class="fa fa-th-large"></i> <span class="nav-label">Dashboard</span> </a>
                        </li>
                        <?php
                        //getting left menu from database
                        $left_menu = $this->curd_model->get_dashboard_menu();
                        foreach ($left_menu as $item) {
                            ?>
                            <?php if (!is_array($item->sub_menu)) { ?>
                                <li class="<?php
                                if ($this->uri->segment($this->uri->total_segments()) == $item->menu_url || $this->uri->segment($this->uri->total_segments() - 1) == $item->menu_url || $this->uri->segment($this->uri->total_segments() - 2) == $item->menu_url
                                ) {
                                    echo "active";
                                }
                                ?>">
                                    <a href="<?= base_url("admin/") ?><?= $item->menu_url ?>"><i class="<?= $item->menu_icon ?>"></i> <span class="nav-label"><?= $item->menu_name ?></span> </a>
                                </li>
                            <?php } else { ?>
                                <li class="<?php
                                foreach ($item->sub_menu as $sun_item) {
                                    if ($this->uri->segment($this->uri->total_segments()) == $sun_item->menu_url || $this->uri->segment($this->uri->total_segments() - 1) == $sun_item->menu_url || $this->uri->segment($this->uri->total_segments() - 2) == $sun_item->menu_url
                                    ) {
                                        echo "active";
                                    }
                                }
                                ?>">
                                    <a href="#"><i class="<?= $item->menu_icon ?>"></i> <span class="nav-label"><?= $item->menu_name ?> </span><span class="fa arrow"></span></a>
                                    <ul class="nav nav-second-level collapse">
                                        <?php foreach ($item->sub_menu as $sun_item) { ?>
                                            <li class="<?php
                                            if ($this->uri->segment($this->uri->total_segments()) == $sun_item->menu_url || $this->uri->segment($this->uri->total_segments() - 1) == $sun_item->menu_url || $this->uri->segment($this->uri->total_segments() - 2) == $sun_item->menu_url
                                            ) {
                                                echo "active";
                                            }
                                            ?>"><a href="<?= base_url("admin/") ?><?= $sun_item->menu_url ?>"><?= $sun_item->menu_name ?></a></li>
                                            <?php } ?>
                                    </ul>
                                </li>
                                <?php
                            }
                        }
                        ?>

                        <?php if ($_SERVER["HTTP_HOST"] == "localhost") { ?>
                                                                            <!--                            <li class="<?= $this->uri->segment($this->uri->total_segments()) == "main_menu" ? "active" : "" ?>">
                                                                                                            <a href="<?= base_url() ?>admin/main_menu"><i class="fa fa-comments-o" aria-hidden="true"></i> <span class="nav-label">Main Menu</span></a>
                                                                                                        </li>
                                                                                                        <li class="<?= $this->uri->segment($this->uri->total_segments()) == "sub_menu" ? "active" : "" ?>">
                                                                                                            <a href="<?= base_url() ?>admin/sub_menu"><i class="fa fa-comments-o" aria-hidden="true"></i> <span class="nav-label">Sub Menu</span></a>
                                                                                                        </li>-->
                        <?php } ?>
                    </ul>
                </div>
            </nav>

            <div id="page-wrapper" class="gray-bg">
                <div class="row border-bottom">
                    <nav class="navbar navbar-static-top" role="navigation" style="margin-bottom: 0">
                        <div class="navbar-header">
                            <a class="navbar-minimalize minimalize-styl-2 btn btn-primary " href="#"><i class="fa fa-bars"></i> </a>

                        </div>
                        <ul class="nav navbar-top-links navbar-right">
                            <li>
                                <div class="dropdown" style="margin-top: 10px">
                                    <button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown">
                                        <i class="fa fa-user"></i> Admin
                                        <span class="caret"></span></button>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <a href="<?= base_url() ?>admin/logout">
                                                <i class="fa fa-sign-out"></i> Log out
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                        </ul>
                    </nav>
                </div>