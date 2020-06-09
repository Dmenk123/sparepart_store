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
        <div class="box">
            <span style="font-size: 20px;"><a href="#details" class="scroll-to">Sub Kategori <?php echo $sub_menu_kategori->nama_sub_kategori; ?></a></span>
        </div>
        <div class="row" id="productMain">
            <?php foreach ($img_detail_big as $val) { ?>
            <?php $link_img = $val->nama_gambar; ?>
            <div class="col-sm-6">
                <div id="mainImage">
                    <img src="<?php echo site_url('assets/img/produk/img_detail/'.$link_img.''); ?>" alt="" class="img-responsive">
                </div>
            </div> <!-- end div.col sm-6 -->
            <?php } ?>

            <div class="col-sm-6">
                <?php foreach ($detail_produk as $val) { ?>
                    <div class="box">
                        <?php $id_produk = $this->uri->segment(4); ?>
                        <input type="hidden" name="txt_id_produk" class="txtIdProduk" value="<?php echo $id_produk; ?>">
                        <h1 class="text-center"><a href="#details" class="scroll-to"><?php echo $val->nama_produk; ?></a></h1>
                        <p class="price">Rp. <?php echo number_format($val->harga,0,",","."); ?> / Hari.</p>
                        <div class="col-sm-4" style="text-align: center;">
                            <label><strong>Pilih Qty : </strong></label>
                        </div>
                        <div class="col-sm-8" style="padding-bottom: 10px;">
                            <select class="form-control selectQty" id="qty_<?php echo $val->id_produk;?>" name="select_qty_jasa" required>
                                <option value="">Pilih Qty</option>
                            </select>
                        </div>
                        <div class="col-sm-4" style="text-align: center;">
                            <label><strong>Durasi : </strong></label>
                        </div>
                        <div class="col-sm-8" style="padding-bottom: 10px;">
                            <select class="form-control" id="durasi_<?php echo $val->id_produk;?>" name="select_durasi" required>
                                <option value="">Pilih Durasi</option>
                                <?php for ($i=1; $i < 31; $i++) { 
                                    echo '<option value="'.$i.'">'.$i.' Hari</option>';
                                } ?>
                            </select>
                        </div>
                        <div class="col-sm-4" style="text-align: center;">
                            <label><strong>Jasa Rekber : </strong></label>
                        </div>
                        <div class="col-sm-8" style="padding-bottom: 10px;">
                            <select class="form-control" id="rekber_<?php echo $val->id_produk;?>" name="select_rekber" required>
                                <?php 
                                    foreach ($rekber as $rek) { ?>
                                        <option value="<?= $rek->id; ?>"><?= $rek->nama.' - '.$rek->no_rek; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <p class="text-center buttons">
                            <button class="btn btn-primary btn-block add_jasa" data-namavendor="<?php echo $val->nama_vendor;?>" data-idvendor="<?php echo $val->id_vendor;?>" data-idproduk="<?php echo $val->id_produk;?>" data-namaproduk="<?php echo $val->nama_produk;?>" data-hargaproduk="<?php echo $val->harga;?>" data-gambarproduk="<?php echo $val->nama_gambar;?>"><i class="fa fa-shopping-cart"></i>Pesan Sekarang</button>
                        </p>
                    </div> <!-- end div.box -->
                <?php } ?>

                <div class="row" id="thumbs">
                    <?php foreach ($img_detail_thumb as $val) { ?>
                    <?php $link_img = $val->nama_gambar; ?>
                    <div class="col-xs-4">
                        <a href="<?php echo site_url('assets/img/produk/img_detail/'.$link_img.''); ?>" class="thumb">
                            <img src="<?php echo site_url('assets/img/produk/img_detail/'.$link_img.''); ?>" alt="" class="img-responsive">
                        </a>
                    </div> <!-- end div.col xs-4 -->
                    <?php } ?> 
                </div> <!-- end div.row -->
            </div> <!-- end div.col sm-6 -->
        </div> <!-- end div.row #product-main -->

        <div class="box" id="details">
            <p>
                <h4>Produk Detail</h4>
                <?php foreach ($detail_produk as $key => $value) { ?>
                    <p><?php echo $value->keterangan_produk; ?></p>
                    <h4>varian Produk</h4>
                    <ul>
                        <li><?php echo $value->varian_produk; ?></li>
                    </ul>
                <?php } ?>

                <h4>Pelapak Detail</h4>
                <p>
                    <?php $id_vendor = $pelapak['id_vendor']; ?>
                    Nama Pelapak : <?php echo $pelapak['nama_vendor']; ?> <br>
                    Jenis Usaha : <?php echo $pelapak['jenis_usaha_vendor']; ?> <br><br>
                    <a href="<?=base_url('produk/detail_vendor/').$id_vendor;?>"><strong>Klik</strong> untuk Info lebih lanjut tentang : <?php echo $pelapak['nama_vendor']; ?></a>
                </p>

                <blockquote>
                    <p><em><strong>Tips & info :</strong> Apabila anda sedang merencanakan untuk liburan/berpergian jauh, namun khawatir dengan kondisi hewan peliharaan anda ?? , Jangan ragu untuk menitipkan hewan peliharaan anda pada yang profesional. Liburan anda nyaman dan peliharaan pun terjaga dengan baik.</em>
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
                <h2 class="text-center">Produk terlaris kami</h2>
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

