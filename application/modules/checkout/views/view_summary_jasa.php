<div class="container">
    <div class="col-md-12">
        <ul class="breadcrumb">
            <li><a href="<?php echo site_url('home'); ?>">Home </a></li>
            <li><?php echo $this->uri->segment('1'); ?></li>
            <li><?php echo $this->uri->segment('2'); ?></li>
        </ul>
    </div>
        
    <div class="col-md-12" id="basket">
        <div class="box">
                <h1>Detail Belanja Anda</h1>
                <!-- hitung produk pada cart --> 
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Gambar</th>
                                <th>Nama Produk</th>
                                <th>Pelapak</th>
                                <th>Qty</th>
                                <th>Berat Satuan</th>
                                <th>Berat Total</th>
                                <th>Harga Satuan</th>
                                <th>Harga Total</th>
                            </tr>
                        </thead>
                        <tbody id="show_detail">
                        
                           <?php $link_img = $data_cart['gambar_produk'];  ?>
                           <tr>
                              <td><img src="<?php echo site_url("assets/img/produk/$link_img"); ?>"></td>
                              <td><?php echo $data_cart['nama_produk']; ?></td>
                              <td><?php echo $data_cart['nama_vendor']; ?></td>
                              <td><?php echo $data_cart['qty_produk']; ?></td>
                              <td><?php echo $data_cart['berat_satuan']; ?> gram</td>
                              <td><?php echo $data_cart['berat_satuan'] * $data_cart['qty_produk']; ?> gram</td>
                              <td>Rp. <?php echo number_format($data_cart['harga_produk'],0,",","."); ?></td>
                              <td>Rp. <?php echo number_format($data_cart['harga_produk'] * $data_cart['qty_produk'],0,",","."); ?></td>
                           </tr>
                           
                           <tr>
                              <th colspan="3">Metode Pembayaran</th>
                              <td colspan="5"><strong>Transfer ( <?= $data_cart['nama'].' - '.$data_cart['no_rek']; ?> )</strong></td>
                           </tr>
                           <tr>
                              <th colspan="3">Kode Refrensi Transaksi (Kode ini digunakan saat konfirmasi pembayaran)</th>
                              <td colspan="5" style="color:blue; font-size: 20px;"><strong><?php echo $data_input['no_ref']; ?></strong></td>
                           </tr>
   
                           <?php $beratTotal += $data_cart['berat_satuan'] * $data_cart['qty_produk']; ?>
                           <tr>
                              <th colspan="5">Berat Total Produk</th>
                              <td colspan="4"><?php echo number_format($beratTotal,0,",","."); ?> gram </td>
                           </tr>
                           <tr>
                              <th colspan="5">Durasi Hari</th>
                              <td colspan="4"><?php echo $data_cart['durasi']; ?> Hari</td>
                           </tr>
                           <tr>
                              <th colspan="7"><span style="font-size:20px;">Total Harga Produk</span></th>
                              <td colspan="2">
                                 <span style="font-size:20px; text-decoration:underline;">
                                    Rp. <?php echo number_format(($data_cart['harga_produk'] * $data_cart['qty_produk']) * $data_cart['durasi'],0,",","."); ?>
                                 </span>
                              </td>
                           </tr>
                        </tbody>
                    </table>
                </div><!-- /.table-responsive -->

                <form id="form_checkout2" action="#">
                  <input type="hidden" class="form-control" name="frmIdTempJasa" value="<?php echo $data_cart['id']; ?>">
                  <input type="hidden" class="form-control" name="frmIduser" value="<?php echo $data_input['iduser_krm']; ?>">
                  <input type="hidden" class="form-control" name="frmBeaProduk" value="<?php echo ($data_cart['harga_produk'] * $data_cart['qty_produk']) * $data_cart['durasi']; ?>">
                  <input type="hidden" class="form-control" name="frmMethod" value="<?php echo $data_cart['id_rekber']; ?>">
                  <input type="hidden" class="form-control" name="frmRef" value="<?php echo $data_input['no_ref']; ?>">
                  <input type="hidden" class="form-control" name="frmKurir" value="<?php echo null; ?>">
                  <input type="hidden" class="form-control" name="frmPaket" value="<?php echo null; ?>">
                  <input type="hidden" class="form-control" name="frmEtd" value="<?php echo null; ?>">
                  <input type="hidden" class="form-control" name="frmOngkir" value="<?php echo null; ?>">
                  <input type="hidden" class="form-control" name="frmBeratKurir" value="<?php echo null; ?>">
                  <input type="hidden" class="form-control" name="frmBeaTotal" value="<?php echo ($data_cart['harga_produk'] * $data_cart['qty_produk']) * $data_cart['durasi']; ?>">
                  <input type="hidden" class="form-control" name="frmFnameKrm" value="<?php echo $data_user[0]->fname_user; ?>">
                  <input type="hidden" class="form-control" name="frmLnameKrm" value="<?php echo $data_user[0]->lname_user; ?>">
                  <input type="hidden" class="form-control" name="frmAlamatKrm" value="<?php echo $data_user[0]->alamat_user.", ".$data_user[0]->nama_kelurahan." - ".$data_user[0]->nama_kecamatan.", ".$data_user[0]->nama_kota." - ".$data_user[0]->nama_provinsi; ?>">
                    
                  <input type="hidden" class="form-control" name="rowId[]" value="">
                  <input type="hidden" class="form-control" name="frmIdproduk[]" value="<?php echo $data_cart['id_produk']; ?>">
                  <input type="hidden" class="form-control" name="frmIdsatuan[]" value="<?php echo $data_cart['id_satuan']; ?>">
                  <input type="hidden" class="form-control" name="frmIdvendor[]" value="<?php echo $data_cart['id_vendor']; ?>">
                  <input type="hidden" class="form-control" name="frmHargaSatuan[]" value="<?php echo $data_cart['harga_produk'] * $data_cart['durasi']; ?>">
                  <input type="hidden" class="form-control" name="frmBeratTotal[]" value="<?php echo $data_cart['berat_satuan']; ?>">
                  <input type="hidden" class="form-control" name="frmIdstok[]" value="<?php echo $data_cart['id_stok']; ?>">
                  <input type="hidden" class="form-control" name="frmIdqty[]" value="<?php echo $data_cart['qty_produk']; ?>">
                </form>
                <div class="box-footer">
                    <div class="pull-left">
                        <a href="<?php echo site_url('checkout') ?>" class="btn btn-default"><i class="fa fa-chevron-left"></i> Kembali</a>
                    </div>
                    <div class="pull-right">
                        <button type="button" class="btn btn-primary" onclick="proses_pembayaran_jasa()">Selesai<i class="fa fa-chevron-right"></i></button>
                    </div>
                </div>
         </div><!-- /.box -->
         <!-- hot product -->           
         <?php $this->load->view('homepage/view_homepage_hot'); ?>
    </div><!-- /.col-md-9 -->
                      
</div><!-- /.container -->