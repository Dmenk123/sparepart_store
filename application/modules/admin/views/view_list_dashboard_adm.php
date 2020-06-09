   <!-- Content Header (Page header) -->
   <section class="content-header">
      <h1>Dashboard
         <small><strong>Selamat Datang : <?php foreach ($data_user as $val) {
            echo $val->fname_user." ".$val->lname_user;
         } ?></strong></small>
      </h1>
      <ol class="breadcrumb">
         <li><a href="#"><i class="fa fa-dashboard"></i>Home</a></li>
         <li class="active">Barang</li>
      </ol>
   </section>

   <!-- Main content -->
   <section class="content">
      <!-- Small boxes (Stat box) -->
      <div class="box">
         <div class="box-body">
            <div class="row">

               <?php if ($this->session->userdata('id_level_user') == '1'): ?>
                  <div class="col-lg-4 col-xs-6">
                     <!-- small box -->
                     <div class="small-box bg-green">
                        <div class="inner">
                           <h3>
                              <?php foreach ($counter_new_vendor as $val) { ?>
                                 <?php echo $val->jml_vendor; ?>
                              <?php } ?>
                           </h3>
                           <p>Permintaan Pelapak Baru</p>
                        </div>
                        <div class="icon">
                           <i class="fa fa-handshake-o"></i>
                        </div>
                        <a href="<?php echo site_url('new_vendor_adm'); ?>" class="small-box-footer">Lihat Detail <i class="fa fa-arrow-circle-right"></i></a>
                     </div>
                     <!-- /.small box -->
                  </div>

                  <!-- ./col -->
                  <div class="col-lg-4 col-xs-6">
                     <!-- small box -->
                     <div class="small-box bg-aqua">
                        <div class="inner">
                           <h3>
                              <?php echo $counter_omset; ?>
                           </h3>
                           <p>Omset Belum Dikonfirmasi</p>
                        </div>
                        <div class="icon">
                           <i class="fa fa-dollar"></i>
                        </div>
                        <a href="<?php echo site_url('kelola_omset_adm'); ?>" class="small-box-footer">Lihat Detail <i class="fa fa-arrow-circle-right"></i></a>
                     </div>
                     <!-- /.small box -->
                  </div>

                  <!-- ./col -->
                  <div class="col-lg-4 col-xs-6">
                     <!-- small box -->
                     <div class="small-box bg-purple">
                        <div class="inner">
                           <h3>
                              <?php echo $counter_penjualan_adm; ?>
                           </h3>
                           <p>Penjualan Belum Dikonfirmasi</p>
                        </div>
                        <div class="icon">
                           <i class="fa fa-shopping-cart"></i>
                        </div>
                        <a href="<?php echo site_url('penjualan_adm'); ?>" class="small-box-footer">Lihat Detail <i class="fa fa-arrow-circle-right"></i></a>
                     </div>
                     <!-- /.small box -->
                  </div>

                  <!-- ./col -->
                  <div class="col-lg-4 col-xs-6">
                     <!-- small box -->
                     <div class="small-box bg-green">
                        <div class="inner">
                           <h3>
                              <?php foreach ($counter_user as $val) { ?>
                                 <?php echo $val->jumlah_user; ?>
                              <?php } ?>
                           </h3>
                           <p>Master User Aktif</p>
                        </div>
                        <div class="icon">
                           <i class="fa fa-users"></i>
                        </div>
                        <a href="<?php echo site_url('master_user_adm'); ?>" class="small-box-footer">Lihat Detail <i class="fa fa-arrow-circle-right"></i></a>
                     </div>
                     <!-- /.small box -->
                  </div>

                  <!-- ./col -->
                  <div class="col-lg-4 col-xs-6">
                     <!-- small box -->
                     <div class="small-box bg-aqua">
                        <div class="inner">
                           <h3>
                              <?php echo $counter_data_vendor; ?>
                           </h3>
                           <p>Master Vendor Aktif</p>
                        </div>
                        <div class="icon">
                           <i class="fa fa-university"></i>
                        </div>
                        <a href="<?php echo site_url('master_user_adm'); ?>" class="small-box-footer">Lihat Detail <i class="fa fa-arrow-circle-right"></i></a>
                     </div>
                     <!-- /.small box -->
                  </div>

                  <!-- ./col -->
                  <div class="col-lg-4 col-xs-6">
                     <!-- small box -->
                     <div class="small-box bg-purple">
                        <div class="inner">
                           <h3>
                              <?php echo $counter_data_user; ?>
                           </h3>
                           <p>Master Customer Aktif</p>
                        </div>
                        <div class="icon">
                           <i class="fa fa-users"></i>
                        </div>
                        <a href="<?php echo site_url('master_user_adm'); ?>" class="small-box-footer">Lihat Detail <i class="fa fa-arrow-circle-right"></i></a>
                     </div>
                     <!-- /.small box -->
                  </div>
               <?php endif ?>

               <?php if ($this->session->userdata('id_level_user') == '4'): ?>
                  <div class="col-lg-4 col-xs-6">
                     <!-- small box -->
                     <div class="small-box bg-green">
                        <div class="inner">
                           <h3>
                              <?php if (count($counter_new_penjualan) == 0): ?>
                                 <?php echo '0'; ?>
                              <?php else: ?>
                                 <?php echo $counter_new_penjualan->jml_trans; ?>
                              <?php endif ?>
                           </h3>
                           <p>Pembelian Baru Belum Konfirmasi</p>
                        </div>
                        <div class="icon">
                           <i class="fa fa-cart-plus"></i>
                        </div>
                        <a href="<?php echo site_url('penjualan_adm'); ?>" class="small-box-footer">Lihat Detail <i class="fa fa-arrow-circle-right"></i></a>
                     </div>
                     <!-- /.widget-user -->
                  </div>
                  <!-- ./col -->

                  <div class="col-lg-4 col-xs-6">
                     <!-- small box -->
                     <div class="small-box bg-purple">
                        <div class="inner">
                           <h3>
                              <?php echo $counter_kategori_vendor; ?>                              
                           </h3>
                           <p>Jumlah Kategori Produk Vendor</p>
                        </div>
                        <div class="icon">
                           <i class="fa fa-list"></i>
                        </div>
                        <a href="<?php echo site_url('kat_penjualan_adm'); ?>" class="small-box-footer">Lihat Detail <i class="fa fa-arrow-circle-right"></i></a>
                     </div>
                     <!-- /.widget-user -->
                  </div>
                  <!-- ./col -->

                  <div class="col-lg-4 col-xs-6">
                     <!-- small box -->
                     <div class="small-box bg-aqua">
                        <div class="inner">
                           <h3>
                              <?php echo $counter_produk_vendor; ?>                              
                           </h3>
                           <p>Jumlah Produk Vendor</p>
                        </div>
                        <div class="icon">
                           <i class="fa fa-list-ol"></i>
                        </div>
                        <a href="<?php echo site_url('set_produk_adm'); ?>" class="small-box-footer">Lihat Detail <i class="fa fa-arrow-circle-right"></i></a>
                     </div>
                     <!-- /.widget-user -->
                  </div>
                  <!-- ./col -->
                  
                  <!-- table -->
                  <div class="col-lg-6 col-xs-6">
                     <!-- Widget: user widget style 1 -->
                     <div class="box box-widget widget-user-2">
                        <!-- Add the bg color to the header using any of the bg-* classes -->
                        <div class="widget-user-header bg-blue">
                           List Kategori Vendor
                        </div>
                        <div class="box-footer no-padding">
                           <ul class="nav nav-stacked">
                              <?php foreach ($list_kategori_vendor as $val) { ?>
                                 <li><a><?php echo $val->nama_kategori; ?> <span class="pull-right badge bg-blue"><?php echo $val->qty; ?></span></a></li>
                              <?php } ?>
                           </ul>
                        </div>
                     </div>
                     <!-- /.widget-user -->
                  </div>
                  <!-- table -->

                  <!-- table -->
                  <div class="col-lg-6 col-xs-6">
                     <!-- Widget: user widget style 1 -->
                     <div class="box box-widget widget-user-2">
                        <!-- Add the bg color to the header using any of the bg-* classes -->
                        <div class="widget-user-header bg-blue">
                           Top 5 Produk Vendor
                        </div>
                        <div class="box-footer no-padding">
                           <ul class="nav nav-stacked">
                              <?php foreach ($list_produk_vendor as $val) { ?>
                                 <li><a><?php echo $val->id_produk.' - '.$val->nama_produk; ?> <span class="pull-right badge bg-blue"><?php echo $val->stok_sisa; ?></span></a></li>
                              <?php } ?>
                           </ul>
                        </div>
                     </div>
                     <!-- /.widget-user -->
                  </div>
                  <!-- table -->

               <?php endif ?>

               <!-- chart -->
               <div class="col-lg-12 col-xs-12">
                  <canvas id="canvas" width="1000" height="380"></canvas>
               </div>
               <div class="col-xs-12">
                  <div id="legenda"> </div>
               </div>

               <?php if ($this->session->userdata('id_level_user') == '4'): ?>
                  <div class="col-lg-12 col-xs-12">
                     <canvas id="canvas2" width="1000" height="380"></canvas>
                  </div>
                  <div class="col-xs-12">
                     <div id="legenda2"> </div>
                  </div>                  
               <?php endif ?>
            </div>
            <!-- ./row -->
         </div>
      </div>       
   </section>
   <!-- /.content -->
