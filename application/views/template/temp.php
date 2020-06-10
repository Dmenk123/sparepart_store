<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
         <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->

        <title>Jualan Sparepart MOGE</title>

        <!-- Google font -->
        <link href="https://fonts.googleapis.com/css?family=Montserrat:400,500,700" rel="stylesheet">

        <!-- Bootstrap -->
        <link type="text/css" rel="stylesheet" href="<?=base_url('assets/template/'); ?>css/bootstrap.min.css"/>

        <!-- Slick -->
        <link type="text/css" rel="stylesheet" href="<?=base_url('assets/template/'); ?>css/slick.css"/>
        <link type="text/css" rel="stylesheet" href="<?=base_url('assets/template/'); ?>css/slick-theme.css"/>

        <!-- nouislider -->
        <link type="text/css" rel="stylesheet" href="<?=base_url('assets/template/'); ?>css/nouislider.min.css"/>

        <!-- Font Awesome Icon -->
        <link rel="stylesheet" href="<?=base_url('assets/template/'); ?>css/font-awesome.min.css">

        <!-- Custom stlylesheet -->
        <link type="text/css" rel="stylesheet" href="<?=base_url('assets/template/'); ?>css/style.css"/>

        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
          <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->

    </head>
    <body>
        <!-- HEADER -->
        <?php $this->load->view('template/components/header'); ?>
        <!-- /HEADER -->

        <!-- NAVIGATION -->
        <?php $this->load->view('template/components/navbar'); ?>
        <!-- /NAVIGATION -->

        <!-- SECTION -->
        <!-- first row -->
        <div class="section">
            <!-- container -->
            <div class="container">
                <!-- row -->
                <div class="row">
                    <!-- shop -->
                    <div class="col-md-4 col-xs-6">
                        <div class="shop">
                            <div class="shop-img">
                                <img src="<?= base_url('assets/img/produk/'); ?>shop01.png" alt="">
                            </div>
                            <div class="shop-body">
                                <h3>Laptop<br>Collection</h3>
                                <a href="#" class="cta-btn">Shop now <i class="fa fa-arrow-circle-right"></i></a>
                            </div>
                        </div>
                    </div>
                    <!-- /shop -->

                    <!-- shop -->
                    <div class="col-md-4 col-xs-6">
                        <div class="shop">
                            <div class="shop-img">
                                <img src="<?= base_url('assets/img/produk/'); ?>shop03.png" alt="">
                            </div>
                            <div class="shop-body">
                                <h3>Accessories<br>Collection</h3>
                                <a href="#" class="cta-btn">Shop now <i class="fa fa-arrow-circle-right"></i></a>
                            </div>
                        </div>
                    </div>
                    <!-- /shop -->

                    <!-- shop -->
                    <div class="col-md-4 col-xs-6">
                        <div class="shop">
                            <div class="shop-img">
                                <img src="<?= base_url('assets/img/produk/'); ?>shop02.png" alt="">
                            </div>
                            <div class="shop-body">
                                <h3>Cameras<br>Collection</h3>
                                <a href="#" class="cta-btn">Shop now <i class="fa fa-arrow-circle-right"></i></a>
                            </div>
                        </div>
                    </div>
                    <!-- /shop -->
                </div>
                <!-- /row -->
            </div>
            <!-- /container -->
        </div>
        <!-- /SECTION -->

        <!-- SECTION -->
        <!-- new product -->
        <?php if (isset($new_product)) { $this->load->view($new_product); } ?>
        <!-- /SECTION -->

        <!-- HOT DEAL SECTION -->
        <!-- promo -->
        <?php if (isset($promo)) { $this->load->view($promo); } ?>
        <!-- /HOT DEAL SECTION -->

        <!-- SECTION -->
        <!-- top selling -->
        <?php if (isset($top_selling)) { $this->load->view($top_selling); } ?>
        <!-- /SECTION -->
        
        <!-- CONTENT -->
        <?php if (isset($content)) { $this->load->view($content); } ?>
         <!--CONTENT -->

        <!-- SECTION -->
        <!-- top selling partial -->
        <!-- /SECTION -->

        <!-- NEWSLETTER -->
        <?php $this->load->view('template/components/newsletter'); ?>
        <!-- /NEWSLETTER -->

        <!-- FOOTER -->
        <?php $this->load->view('template/components/footer'); ?>
        <!-- /FOOTER -->

        <!-- jQuery ins -->
        <script src="<?= base_url('assets/template/'); ?>js/jquery.min.js"></script>
        <script src="<?= base_url('assets/template/'); ?>js/bootstrap.min.js"></script>
        <script src="<?= base_url('assets/template/'); ?>js/slick.min.js"></script>
        <script src="<?= base_url('assets/template/'); ?>js/nouislider.min.js"></script>
        <script src="<?= base_url('assets/template/'); ?>js/jquery.zoom.min.js"></script>
        <script src="<?= base_url('assets/template/'); ?>js/main.js"></script>

    </body>
</html>
