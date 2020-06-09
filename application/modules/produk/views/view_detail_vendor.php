<div class="container">
    <div class="col-md-12">
        <ul class="breadcrumb">
            <?php $uri1 = $this->uri->segment(1); ?>
            <?php $uri2 = $this->uri->segment(2); ?>
            <li><a href="<?php echo site_url('home'); ?>">Home </a></li>
            <li><?= str_replace("_"," ","$uri1"); ?></li>
            <li><?= str_replace("_"," ","$uri2"); ?></li>
        </ul>
    </div>

    <div class="col-md-3">
        <!-- *** SIDEBAR MENUS AND FILTERS *** -->
        <?php 
            if (isset($content_sidebar)) 
            {
                $this->load->view($content_sidebar); 
            } 
        ?>
    </div>
    <!-- *** SIDEBAR MENUS AND FILTERS END *** -->    
        
    <div class="col-md-9">
        <div class="row" id="productMain">
            <?php $link_img = $img_detail_big->img_vendor; ?>
            <div class="col-sm-4">
                <div id="mainImage" style="width: 250px; height: 250px;">
                    <img src="<?php echo site_url('assets/img/foto_usaha/'.$link_img.''); ?>" alt="" class="img-responsive">
                </div>
            </div> <!-- end div.col sm-6 -->

            <div class="col-sm-6">
                <h4>Pelapak Detail</h4>
                <p> <?php $id_vendor = $detail_vendor['id_vendor']; ?> </p>
                <p> Nama Pelapak : <?php echo $detail_vendor['nama_vendor']; ?> </p>
                <p> Jenis Usaha : <?php echo $detail_vendor['jenis_usaha_vendor']; ?> </p>
                <p> Alamat :  <?php echo $detail_vendor['alamat']; ?> </p>
            </div>         
        </div> <!-- end div.row #product-main -->

        <div class="box" id="details">
            <p>
                <h4>Produk-Produk dari <?php echo $detail_vendor['nama_vendor']; ?></h4>
                <?php foreach ($submenu_vendor as $key => $sv) {
                    echo "<ul>".$sv['nama_sub_kategori'];
                    for ($i=0; $i <count($produk); $i++) { 
                        if ($produk[$i]['id_sub_kategori'] == $sv['id_sub_kategori']) {
                            echo "<li>".
                            $produk[$i]['nama_produk'].
                            ' - Harga : Rp. '.number_format($produk[$i]['harga'],0,",",".").
                            ' Per '.$produk[$i]['nama_satuan'].
                            ' | <a href="'.base_url($produk[$i]['link_produk']).'">Lihat Selengkapnya</a>'.
                            "</li>";
                        }
                    }
                    echo "</ul>";
                } ?>
                <blockquote>
                    <p><em><strong>Trivia :</strong> Perlu anda ketahui, semua pelapak / vendor dalam OnPets E-Marketplace telah melewati proses seleksi dan verifikasi dari pihak kami. OnPets menjamin bahwa pelapak memiliki kegiatan yang sehat dan memiliki toko real (tidak fiktif). Jadi tidak perlu ragu lagi ketika berbelanja pada OnPets E-Marketplace.</em>
                    </p>
                </blockquote>
                <hr>
        </div> <!-- end div.box #product-detail -->
    </div><!-- /.col-md-9 -->
</div><!-- /.container -->    

<div id="hot">

    <div class="box">
        <div class="container">
            <div class="col-md-12">
                <h2 class="text-center">Produk terlaris dari <?php echo $detail_vendor['nama_vendor']; ?></h2>
            </div>
        </div>
    </div>

        
    <div class="container">
        <div class="product-slider">
            <?php foreach ($produk_terlaris as $val): ?>
                <div class="item">    
                    <div class="product">
                        <div class="flip-container">
                            <div class="flipper">
                                <div class="front">
                                    <a href="<?php echo site_url('produk/produk_detail/').$val->id_sub_kategori."/".$val->id_produk; ?>">
                                        <img src="<?php echo config_item('assets'); ?>img/produk/<?php echo $val->nama_gambar; ?>" alt="" class="img-responsive">
                                    </a>
                                </div>
                                <div class="back">
                                    <a href="<?php echo site_url('produk/produk_detail/').$val->id_sub_kategori."/".$val->id_produk; ?>">
                                        <img src="<?php echo config_item('assets'); ?>img/produk/<?php echo $val->nama_gambar; ?>" alt="" class="img-responsive">
                                    </a>
                                </div>
                            </div>
                        </div>
                        <a href="detail.html" class="invisible">
                            <img src="<?php echo config_item('assets'); ?>img/produk/<?php echo $val->nama_gambar; ?>" alt="" class="img-responsive">
                        </a>
                        <div class="text">
                            <h3>
                                <a href="<?php echo site_url('produk/produk_detail/').$val->id_sub_kategori."/".$val->id_produk; ?>">
                                    <?php echo $val->nama_produk; ?>
                                </a>
                            </h3>
                           <p class="price">Rp. <?php echo number_format($val->harga,0,",","."); ?></p>
                        </div>
                    </div><!-- /.product -->
                </div>                
            <?php endforeach ?>
        </div>
    </div>  

</div>

