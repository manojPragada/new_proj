<!DOCTYPE html>
<html lang="zxx">  
<head>
        <!-- meta tag -->
        <meta charset="utf-8">
        <title><?= $site_data[0]->site_name; ?></title>
        <meta name="description" content="<?= $seo[0]->seo_description; ?>">
        <meta name="author" content="<?= $seo[0]->seo_title; ?>">
        <meta name="keywords" content="<?= $seo[0]->seo_keywords; ?>">
        <!-- responsive tag -->
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- favicon -->
        <link rel="apple-touch-icon" href="apple-touch-icon.html">
        <link rel="shortcut icon" type="image/x-icon" href="<?= base_url() ?>uploads/<?= $site_data[0]->favicon; ?>">
        <!-- Bootstrap v4.4.1 css -->
        <link rel="stylesheet" type="text/css" href="<?= base_url()?>assets/css/bootstrap.min.css">
        <!-- font-awesome css -->
        <link rel="stylesheet" type="text/css" href="<?= base_url()?>assets/css/font-awesome.min.css">
        <!-- animate css -->
        <link rel="stylesheet" type="text/css" href="<?= base_url()?>assets/css/animate.css">
        <!-- owl.carousel css -->
        <link rel="stylesheet" type="text/css" href="<?= base_url()?>assets/css/owl.carousel.css">
        <!-- slick css -->
        <link rel="stylesheet" type="text/css" href="<?= base_url()?>assets/css/slick.css">
        <!-- off canvas css -->
        <link rel="stylesheet" type="text/css" href="<?= base_url()?>assets/css/off-canvas.css">
        <!-- linea-font css -->
        <link rel="stylesheet" type="text/css" href="<?= base_url()?>assets/fonts/linea-fonts.css">
        <!-- flaticon css  -->
        <link rel="stylesheet" type="text/css" href="<?= base_url()?>assets/fonts/flaticon.css">
        <!-- magnific popup css -->
        <link rel="stylesheet" type="text/css" href="<?= base_url()?>assets/css/magnific-popup.css">
        <!-- Main Menu css -->
        <link rel="stylesheet" href="<?= base_url()?>assets/css/rsmenu-main.css">
        <!-- spacing css -->
        <link rel="stylesheet" type="text/css" href="<?= base_url()?>assets/css/rs-spacing.css">
        <!-- style css -->
        <link rel="stylesheet" type="text/css" href="<?= base_url()?>/assets/style.css"> 
        <link rel="stylesheet" type="text/css" href="<?= base_url()?>assets/css/responsive.css">
        <link type="text/css" rel="stylesheet" href="<?= base_url()?>assets/css/jquery.mmenu.css"/>
     
    </head>
    <body class="defult-home">
        
        <!--Preloader area start here-->
    
 
        <div class="main-content">
            <div class="full-width-header home8-style4 main-home">
                <header id="rs-header" class="rs-header">
                    <div class="topbar-area">
                    <div class="container-fluid">
                        <div class="row y-middle">
                            <div class="col-md-5">
                               
                            </div>
                            <div class="col-md-7 text-right topbar-contact">
                                <div class="topbar-right ">
                                   <li>
                                        <i class="flaticon-email"></i>
                                        <a href="mailto:<?= $site_data[0]->contact_email; ?>"><?= $site_data[0]->contact_email; ?></a>
                                    </li>
                                    <li>
                                        <i class="flaticon-call"></i>
                                        <a href="tel:<?= $site_data[0]->contact_number; ?>"><?= $site_data[0]->contact_number; ?></a>
                                    </li>
                                    <li>
                                        <a class="apply-btn" href="#" data-toggle="modal" data-target="#admissions">Admissions</a>
                                    </li>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> 
                    <!-- Menu Start -->
                    <div class="menu-area menu-sticky">
                        <div class="container-fluid">
                            <div class="row y-middle">
                                <div class="col-lg-3">
                                    <div class="logo-cat-wrap">
                                        <div class="logo-part">
                                            <a href="index.php">
                                                <img class="normal-logo" src="<?= base_url(); ?>uploads/<?= $site_data[0]->logo; ?>" alt="">
                                                <img class="sticky-logo" src="<?= base_url(); ?>uploads/<?= $site_data[0]->logo; ?>" alt="">
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-9 text-right">
                                  <div class="rs-menu-area">
                                      <div class="main-menu">
                                          <div class="mobile-menu">
                                              <a href="#menu" class="respmenu visible-xs">
                                                  <i class="fa fa-bars"></i>
                                              </a>
                                          </div>
                                          <nav class="rs-menu">
                                             <ul class="nav-menu">
                                                <?php if($pages[0]->status){ ?>
                                                <li class=""> <a href="<?= base_url() ?>"><?= $pages[0]->name; ?></a>
                                                </li>
                                                <?php } ?>
                                                <?php if($pages[1]->status){ ?>
                                                 <li class="menu-item-has-children">
                                                     <a href="<?= base_url() ?>about"><?= $pages[1]->name; ?></a>
                                                     
                                                 </li>
                                                <?php } ?>
                                                <?php if($pages[2]->status){ ?>
                                                <li class="menu-item-has-children">
                                                   <a class="subs" href="#"><?= $pages[2]->name; ?> <i class="fa fa-chevron-down" aria-hidden="true"></i>
                                                    </a>
                                                   <ul class="sub-menu">
                                                        <?php foreach($courses_cats as $cats): ?>
                                                        <?php if($cats->status){ ?>
                                                       <li><a href="<?= base_url() ?>courses/<?= $cats->id ?>"><?= $cats->name ?></a> </li>
                                                        <?php } endforeach; ?>
                                                   </ul>
                                                </li>
                                                <?php } ?>
                                                <?php if($pages[3]->status){ ?>
                                                  <li class="">
                                                     <a href="<?= base_url() ?>testimonials"><?= $pages[3]->name; ?></a>
                                                     
                                                 </li>
                                                <?php } ?>
                                                <?php if($pages[4]->status){ ?>
                                                 <li class="menu-item-has-children">
                                                   <a href="#"><?= $pages[4]->name; ?> <i class="fa fa-chevron-down" aria-hidden="true"></i>
                                                    </a>
                                                   <ul class="sub-menu">
                                                        <?php if($journey_cats[0]->status){ ?>
                                                        <li><a href="<?= base_url() ?>trainedbatch"><?= $journey_cats[0]->name ?></a> </li>
                                                        <?php } ?>
                                                        <?php if($journey_cats[1]->status){ ?>
                                                        <li><a href="<?= base_url() ?>jobplacement"><?= $journey_cats[1]->name ?></a> </li>
                                                        <?php } ?>
                                                   </ul>
                                                </li>
                                                <?php } ?>
                                                <?php if($pages[5]->status){ ?>
                                                 <li class="">
                                                     <a href="<?= base_url() ?>gallery"><?= $pages[5]->name; ?></a>
                                                     
                                                 </li>
                                                <?php } ?>
                                                <?php if($pages[6]->status){ ?>
                                                <li class="menu-item-has-children">
                                                   <a href="#"><?= $pages[6]->name; ?> <i class="fa fa-chevron-down" aria-hidden="true"></i></a>
                                                   <ul class="sub-menu">
                                                        <?php foreach($campus_cats as $cats): ?>
                                                        <?php if($cats->status){ ?>
                                                       <li><a href="<?= base_url().'campus/'.$cats->id ?>"><?= $cats->name ?></a> </li>
                                                        <?php } endforeach; ?>

                                                       <!-- <li><a href="campus">Hyderabad</a> </li> -->
                                                   </ul>
                                                </li>
                                                <?php } ?>
                                                <?php if($pages[7]->status){ ?>
                                                <li class="">
                                                    <a href="<?= base_url() ?>contact"><?= $pages[7]->name; ?></a>     
                                                </li>
                                                <?php } ?>

                                                 

                                                 
                                             </ul> <!-- //.nav-menu -->
                                          </nav>                                       
                                      </div> <!-- //.main-menu -->
                                    
                                  </div>
                                </div>
                                
                            </div>
                        </div>
                    </div>
                    <!-- Menu End --> 

                    <!-- Canvas Menu start -->
                    
                    <!-- Canvas Menu end -->
                </header>
                <!--Header End-->
            </div>
            <!--Full width header End-->