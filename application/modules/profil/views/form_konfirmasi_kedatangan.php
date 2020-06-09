<div class="container">
   
   <ul class="breadcrumb">
      <li><a href="#">Home</a></li>
      <li><?php echo $this->uri->segment(1); ?></li>
      <li><?php echo $this->uri->segment(2); ?></li>
   </ul>
  
   <div class="col-md-12">
      <div class="form-group col-md-12">
         <?php if ($tipe == 'reguler'): ?>
         <h2>Konfirmasi Kedatangan Barang</h2>
         <?php elseif ($tipe == 'jasa'): ?>
         <h2>Konfirmasi Pembelian Jasa/Layanan</h2>
         <?php endif ?>
      </div>
      <?php if ($status_datang == '1') { ?>
         <h4>Maaf, Data sudah anda konfirmasi</h4><br><br>
         <button type="button" class="btn btn-danger" onclick="javascript:history.back()">kembali</button><br><br><br>
      <?php }else{ ?>
         <?php if (isset($data_header)) { ?>
            <?php foreach ($data_header as $val ) : ?>
               <div class="col-xs-4">
                  <h4 style="text-align: left;">Nama Customer : <?php echo $val->fname_user." ".$val->lname_user; ?></h4>
                  <h4 style="text-align: left;">No. Transaksi : <?php echo $val->id_checkout; ?></h4>
               </div>
               <?php if ($tipe == 'reguler'): ?>
               <div class="col-xs-4">
                  <?php if ($val->method_checkout != "COD") { ?>
                  <h4 style="text-align: center;">Ekspedisi : <?php echo $val->jasa_ekspedisi." | ".$val->pilihan_paket." | ".$val->estimasi_datang; ?></h4>
                  <?php } ?>
               </div>
               <?php elseif ($tipe == 'jasa'): ?>
               <div class="col-xs-4">
               </div>
               <?php endif ?>
               <div class="col-xs-4">
                  <h4 style="text-align: right;">Tanggal Transaksi : <?php echo date('d-m-Y', strtotime($val->tgl_checkout)); ?></h4>
               </div>
               <table id="tabelTransOrderDetail" class="table table-bordered table-hover" cellspacing="0" width="100%">
                  <thead>
                     <tr>
                        <th style="width: 30px; text-align: center;">No</th>
                        <th style="width: 70px; text-align: center;">A/n Kirim</th>
                        <th style="width: 200px; text-align: center;">Nama Produk</th>
                        <th style="width: 30px; text-align: center;">Varian</th>
                        <?php if ($tipe == 'jasa'): ?>
                        <th style="width: 30px; text-align: center;">Durasi</th>    
                        <?php elseif ($tipe == 'reguler'): ?>
                        <th style="width: 30px; text-align: center;">Sat</th>
                        <?php endif ?>
                        <th style="width: 30px; text-align: center;">Qty</th>
                        <th style="width: 50px; text-align: center;">Harga Satuan</th>
                        <th style="width: 50px; text-align: center;">Harga Total</th>
                     </tr>
                  </thead>
                  <tbody>
                     <?php if (count($data_detail) != 0): ?>
                     <?php $no = 1; ?>
                     <?php $harga_total_produk = 0; ?>
                     <?php foreach ($data_detail as $val ) : ?>
                     <?php $harga_total_produk += $val->harga_total; ?>  
                     <tr>
                        <td><?php echo $no++; ?></td>  
                        <td><?php echo $val->fname_kirim." ".$val->lname_kirim; ?></td>
                        <td><?php echo $val->nama_produk; ?></td>
                        <td><?php echo $val->varian_produk; ?></td>
                        <?php if ($tipe == 'jasa'): ?>
                          <td><?php echo (int)$val->harga_total/(int)$val->harga; ?> Hari</td> 
                          <?php elseif ($tipe == 'reguler'): ?>
                          <td><?php echo $val->nama_satuan; ?></td>
                          <?php endif ?>
                        <td><?php echo $val->qty; ?></td>
                        <td>
                           <span class="pull-left">Rp.</span>
                           <span class="pull-right"><?php echo number_format($val->harga,0,",","."); ?></span>
                        </td>
                        <td>
                           <span class="pull-left">Rp.</span>
                           <?php if ($tipe == 'jasa'): ?>
                           <span class="pull-right"><?php echo number_format(($val->harga * $val->qty)*((int)$val->harga_total/(int)$val->harga),0,",","."); ?></span>
                           <?php elseif ($tipe == 'reguler'): ?>
                           <span class="pull-right"><?php echo number_format($val->harga * $val->qty,0,",","."); ?></span>
                           <?php endif ?> 
                        </td>
                     </tr>
                     <?php endforeach ?>
                     <?php endif ?>
                     <tr>
                        <td colspan="6" align="center"><strong>Harga Keseluruhan</strong></td>
                        <td colspan="2" align="center">
                           <span class="pull-left">Rp.</span>
                           <span class="pull-right"><?php echo number_format($harga_total_produk,0,",","."); ?></span>
                        </td>
                     </tr>    
                     <tr>
                        <?php $bea_ongkir = 0; ?>
                        <?php foreach ($data_detail as $val ) : ?>
                           <?php $bea_ongkir += $val->harga_ongkir; ?>
                        <?php endforeach ?> 
                        <?php $bea_fix = $harga_total_produk + $bea_ongkir;?>
                        <td colspan="6" align="center"><strong>+ Biaya Ongkir</strong></td>
                        <td>
                           <span class="pull-left">Rp.</span>
                           <span class="pull-right"><?php echo number_format($bea_ongkir,0,",","."); ?></span>
                        </td>
                        <td>
                           <strong>
                              <span class="pull-left">Rp.</span>
                              <span class="pull-right"><?php echo number_format($bea_fix,0,",","."); ?></span>
                           </strong>
                        </td>
                     </tr>   
                  </tbody>
               </table>
            <?php endforeach ?>
            <form method="post" enctype="multipart/form-data" id="form_konfirmasi_datang" name="formKonfirmasiDatang">
               <div class="form-group col-md-6">
                  <?php if ($status_datang == '1') { ?>
                     <button type="button" class="btn btn-danger" onclick="javascript:history.back()">kembali</button>
                  <?php }else{ ?>
                     <input type="submit" value="Konfirmasi" id="btnKonfirmasiDatang" class="btn btn-primary"/>
                     <input type="hidden" value="<?= $data_header[0]->id_checkout; ?>" id="idCheckout" name="idCheckout" class="form-control"/>
                     <button type="button" class="btn btn-danger" onclick="javascript:history.back()">kembali</button>
                  <?php } ?>
               </div>       
            </form>
         <?php }else{ ?>
            <button type="button" class="btn btn-danger" onclick="javascript:history.back()">kembali</button><br><br><br>
         <?php } ?>
      <?php } ?>
   </div>
</div><!-- /.container -->