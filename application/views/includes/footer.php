
<footer id="rs-footer" class="rs-footer home9-style main-home">
    <div class="footer-top">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 col-md-6 col-sm-12 footer-widget md-mb-50">
                    <h3 class="widget-title">About Us</h3>

                    <div class="textwidget pr-60 md-pr-15"><p class="white-color"><?= $site_data[0]->about_site; ?></p>
                    </div>
                    <ul class="footer_social">
                        <?php if (!empty($social_media[0]->facebook)) { ?>
                            <li>
                                <a href="<?= $social_media[0]->facebook ?>" target="_blank"><span><i class="fa fa-facebook"></i></span></a>
                            </li>
                        <?php } ?>
                        <?php if (!empty($social_media[0]->twitter)) { ?>
                            <li>
                                <a href="<?= $social_media[0]->twitter ?>" target="_blank"><span><i class="fa fa-twitter"></i></span></a>
                            </li>
                        <?php } ?>
                        <?php if (!empty($social_media[0]->pinterest)) { ?>
                            <li>
                                <a href="<?= $social_media[0]->pinterest ?>" target="_blank"><span><i class="fa fa-pinterest-p"></i></span></a>
                            </li>
                        <?php } ?>
                        <?php if (!empty($social_media[0]->google)) { ?>
                            <li>
                                <a href="<?= $social_media[0]->google ?>" target="_blank"><span><i class="fa fa-google-plus-square"></i></span></a>
                            </li>
                        <?php } ?>
                        <?php if (!empty($social_media[0]->instagram)) { ?>
                            <li>
                                <a href="<?= $social_media[0]->instagram ?>" target="_blank"><span><i class="fa fa-instagram"></i></span></a>
                            </li>
                        <?php } ?>

                    </ul>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-12 footer-widget md-mb-50">
                    <h3 class="widget-title">Address</h3>
                    <ul class="address-widget">
                        <li>
                            <i class="flaticon-location"></i>
                            <div class="desc"><?= $site_data[0]->address; ?></div>
                        </li>
                        <style type="text/css">
                            .desc p{
                                margin: 0px;
                                padding: 0px;
                            }
                        </style>
                        <li>
                            <i class="flaticon-call"></i>
                            <div class="desc">
                                <a href="tel:<?= $site_data[0]->contact_number; ?>"><?= $site_data[0]->contact_number; ?></a>
                            </div>
                        </li>
                        <li>
                            <i class="flaticon-email"></i>
                            <div class="desc">
                                <a href="mailto:<?= $site_data[0]->contact_email; ?>"><?= $site_data[0]->contact_email; ?></a>
                            </div>
                        </li>
                    </ul>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-12 pl-50 md-pl-15 footer-widget md-mb-50">
                    <h3 class="widget-title">Quick Links</h3>
                    <ul class="site-map">
                        <?php if ($pages[1]->status) { ?>
                            <li><a href="<?= base_url() ?>about"><?= $pages[1]->name; ?></a></li>
                        <?php } ?>
                        <?php if ($pages[3]->status) { ?>
                            <li><a href="<?= base_url() ?>testimonials "><?= $pages[3]->name; ?></a></li>
                        <?php } ?>
                        <?php if ($journey_cats[0]->status) { ?>
                            <li><a href="<?= base_url() ?>trainedbatch"><?= strtoupper($journey_cats[0]->name) ?></a> </li>
                        <?php } ?>
                        <?php if ($journey_cats[1]->status) { ?>
                            <li><a href="<?= base_url() ?>jobplacement"><?= strtoupper($journey_cats[1]->name) ?></a> </li>
                        <?php } ?>
                        <?php if ($pages[5]->status) { ?>
                            <li><a href="<?= base_url() ?>gallery"><?= $pages[5]->name; ?></a></li>
                        <?php } ?>
                        <?php if ($pages[7]->status) { ?>
                            <li><a href="<?= base_url() ?>contact"><?= $pages[7]->name; ?></a></li>
                        <?php } ?>

                    </ul>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-12 footer-widget">
                    <h3 class="widget-title">Get In Touch</h3>
                    <?= $site_data[0]->google_maps; ?>
                </div>
                <style type="text/css">
                    .footer-widget iframe{
                        width: 100%;
                        height: 220px;
                    }
                    .rs-footer{
                        background-image: url(<?= base_url() ?>uploads/<?= $site_data[0]->footer_background; ?>);
                    }
                </style>
            </div>
        </div>
    </div>
</footer>
<!-- Footer End -->

<!-- start scrollUp  -->
<div id="scrollUp" class="orange-color">
    <i class="fa fa-angle-up"></i>
</div>

<div aria-hidden="true" class="modal fade search-modal contact-page-section " role="dialog" tabindex="-1" id="admissions">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span class="flaticon-cross"></span>
    </button>
    <div class="modal-dialog modal-dialog-centered rs-quick-contact">
        <div class="modal-content ">
            <div class="search-block clearfix">
                <div class="rs-quick-contact new-style">
                    <div class="inner-part mb-50">
                        <h2 class="title mb-15">Quick Enquiry</h2>

                    </div>
                    <p id="form-messages"></p>
                    <style type="text/css">
                        #form-messages{
                            padding: 0px 0px 10px 30px;
                        }
                    </style>
                    <div class="form-modal">
                        <div class="row">
                            <div class="col-lg-6 mb-35 col-md-12">
                                <input class="from-control" type="text" id="modal-name" name="name" placeholder="Name" required="">
                            </div>
                            <div class="col-lg-6 mb-35 col-md-12">
                                <input class="from-control" type="text" id="modal-email" name="email" placeholder="Email" required="">
                            </div>
                            <div class="col-lg-6 mb-35 col-md-12">
                                <input class="from-control" type="text" id="modal-phone" name="phone" placeholder="Phone" required="">
                            </div>
                            <div class="col-lg-6 mb-35 col-md-12">
                                <input class="from-control" type="text" id="modal-subject" name="subject" placeholder="Course" required="">
                            </div>

                            <div class="col-lg-12 mb-50">
                                <textarea class="from-control" id="modal-message" name="message" placeholder=" Message" required=""></textarea>
                            </div>
                        </div>
                        <div class="form-group mb-0">
                            <button id="modal_sub" class="btn-send" value="Submit Now">Submit Now</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    document.getElementById('modal_sub').onclick = function () {
        var name = document.getElementById('modal-name').value;
        var email = document.getElementById('modal-email').value;
        var phone = document.getElementById('modal-phone').value;
        var subject = document.getElementById('modal-subject').value;
        var message = document.getElementById('modal-message').value;
        $.ajax({
            url: "<?= base_url() ?>contact/sendModalMessage",
            type: "post",
            data: {name: name, email: email, phone: phone, subject: subject, message: message},
            success: function (res) {
                document.getElementById('modal-name').value = "";
                document.getElementById('modal-email').value = "";
                document.getElementById('modal-phone').value = "";
                document.getElementById('modal-subject').value = "";
                document.getElementById('modal-message').value = "";
                alert(res);
                $(".modal").modal('hide');
            }
        });
    };
</script>
<div class="float-icons hidden-xs">
    <ul>


        <?php if (!empty($social_media[0]->facebook)) { ?>
            <li class="facebook">
                <a href="<?= $social_media[0]->facebook ?>" target="_blank"><span><i class="fa fa-facebook my-float"></i></span></a>
            </li>
        <?php } ?>
        <?php if (!empty($social_media[0]->twitter)) { ?>
            <li class="twitter">
                <a href="<?= $social_media[0]->twitter ?>" target="_blank"><span><i class="fa fa-twitter my-float"></i></span></a>
            </li>
        <?php } ?>
        <?php if (!empty($social_media[0]->google)) { ?>
            <li class="linkedin">
                <a href="<?= $social_media[0]->google ?>" target="_blank"><span><i class="fa fa-linkedin my-float"></i></span></a>
            </li>
        <?php } ?>
        <?php if (!empty($social_media[0]->instagram)) { ?>
            <li class="instagram">
                <a href="<?= $social_media[0]->instagram ?>" target="_blank"><span><i class="fa fa-instagram my-float"></i></span></a>
            </li>
        <?php } ?>

    </ul>
</div>

<nav id="menu">
    <ul>
        <?php if ($pages[0]->status) { ?>
            <li> <a href="<?= base_url() ?>"><?= $pages[0]->name; ?></a>
            </li>
        <?php } ?>
        <?php if ($pages[1]->status) { ?>
            <li>
                <a href="<?= base_url() ?>about"><?= $pages[1]->name; ?></a>
            </li>
        <?php } ?>
        <?php if ($pages[2]->status) { ?>
            <li><span><?= $pages[2]->name; ?></span>
                <ul>
                    <?php foreach ($courses_cats as $cats): ?>
                        <?php if ($cats->status) { ?>
                            <li><a href="<?= base_url() ?>courses/<?= $cats->id ?>"><?= $cats->name ?></a> </li>
                        <?php } endforeach; ?>
                </ul>
            </li>
        <?php } ?>
        <?php if ($pages[3]->status) { ?>
            <li>
                <a href="<?= base_url() ?>testimonials"><?= $pages[3]->name; ?></a>

            </li>
        <?php } ?>
        <?php if ($pages[4]->status) { ?>
            <li><span><?= $pages[4]->name; ?></span>
                <ul>
                    <?php if ($journey_cats[0]->status) { ?>
                        <li><a href="<?= base_url() ?>trainedbatch"><?= $journey_cats[0]->name ?></a> </li>
                    <?php } ?>
                    <?php if ($journey_cats[1]->status) { ?>
                        <li><a href="<?= base_url() ?>jobplacement"><?= $journey_cats[1]->name ?></a> </li>
                    <?php } ?>

                </ul>
            </li>
        <?php } ?>
        <?php if ($pages[5]->status) { ?>
            <li><a href="<?= base_url() ?>gallery"><?= $pages[5]->name; ?></a></li>
        <?php } ?>
        <?php if ($pages[6]->status) { ?>
            <li><span><?= $pages[6]->name; ?></span>
                <ul>
                    <?php foreach ($campus_cats as $cats): ?>
                        <?php if ($cats->status) { ?>
                            <li><a href="<?= base_url() . 'campus/' . $cats->id ?>"><?= $cats->name ?></a> </li>
                        <?php } endforeach; ?>

                </ul>
            </li>
        <?php } ?>
        <?php if ($pages[7]->status) { ?>
            <li>
                <a href="<?= base_url() ?>contact"><?= $pages[7]->name; ?></a>
            </li>
        <?php } ?>
    </ul>
</nav>
<!-- Search Modal End -->

<!-- modernizr js -->
<script src="<?= base_url() ?>assets/js/modernizr-2.8.3.min.js"></script>
<!-- jquery latest version -->
<script src="<?= base_url() ?>assets/js/jquery.min.js"></script>
<!-- Bootstrap v4.4.1 js -->
<script src="<?= base_url() ?>assets/js/bootstrap.min.js"></script>
<!-- Menu js -->
<script src="<?= base_url() ?>assets/js/rsmenu-main.js"></script>
<!-- op nav js -->
<script src="<?= base_url() ?>assets/js/jquery.nav.js"></script>
<!-- owl.carousel js -->
<script src="<?= base_url() ?>assets/js/owl.carousel.min.js"></script>
<!-- Slick js -->
<script src="<?= base_url() ?>assets/js/slick.min.js"></script>
<!-- isotope.pkgd.min js -->
<script src="<?= base_url() ?>assets/js/isotope.pkgd.min.js"></script>
<!-- imagesloaded.pkgd.min js -->
<script src="<?= base_url() ?>assets/js/imagesloaded.pkgd.min.js"></script>
<!-- wow js -->
<script src="<?= base_url() ?>assets/js/wow.min.js"></script>
<!-- Skill bar js -->
<script src="<?= base_url() ?>assets/js/skill.bars.jquery.js"></script>
<script src="<?= base_url() ?>assets/js/jquery.counterup.min.js"></script>
<!-- counter top js -->
<script src="<?= base_url() ?>assets/js/waypoints.min.js"></script>
<!-- video js -->
<script src="<?= base_url() ?>assets/js/jquery.mb.YTPlayer.min.js"></script>
<!-- magnific popup js -->
<script src="<?= base_url() ?>assets/js/jquery.magnific-popup.min.js"></script>
<!-- plugins js -->
<script src="<?= base_url() ?>assets/js/plugins.js"></script>
<!-- contact form js -->
<script src="<?= base_url() ?>assets/js/contact.form.js"></script>
<!-- main js -->
<script src="<?= base_url() ?>assets/js/main.js"></script>
<script type="text/javascript" src="<?= base_url() ?>assets/js/jquery.mmenu.js"></script>
<script>
    $(function () {
        $('nav#menu').mmenu();
    });
</script>
</body>
</html>