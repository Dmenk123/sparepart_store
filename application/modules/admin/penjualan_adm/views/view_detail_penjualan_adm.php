    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Detail Konfirmasi Penjualan
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i>Data Transaksi</a></li>
        <li>Konfirmasi Penjualan</li>
        <li class="active">Detail Konfirmasi Penjualan</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <!-- /.box-header -->
            <div class="box-body">
              <div class="table-responsive">
                <div class="col-xs-12">
                  <?php if ($flag_tipe == 'reguler'): ?>
                    <h4 style="text-align: center;"><strong>Detail Penjualan Produk</strong></h4>  
                  <?php elseif ($flag_tipe == 'jasa'): ?>
                    <h4 style="text-align: center;"><strong>Detail Penjualan Jasa</strong></h4>
                  <?php endif ?>
                  
                </div>
                <?php foreach ($hasil_header as $val ) : ?>
                <div class="col-xs-4">
                  <h4 style="text-align: left;">Nama Customer : <?php echo $val->fname_user." ".$val->lname_user; ?></h4>
                  <h4 style="text-align: left;">ID Pembelian : <?php echo $val->id_pembelian; ?></h4>
                </div>
                
                <?php if ($flag_tipe == 'reguler'): ?>
                <div class="col-xs-4">
                  <h4 style="text-align: center;">Metode : <?php echo $val->method_checkout; ?></h4>
                  <?php if ($val->method_checkout != "COD") { ?>
                    <h4 style="text-align: center;">Ekspedisi : <?php echo $val->jasa_ekspedisi." | ".$val->pilihan_paket." | ".$val->estimasi_datang; ?></h4>
                  <?php } ?>
                </div>  
                <?php elseif($flag_tipe == 'jasa'): ?>
                <div class="col-xs-4">
                </div>  
                <?php endif ?>
                <div class="col-xs-4">
                  <h4 style="text-align: right;">Tanggal Pembelian : <?php echo $val->tgl_pembelian; ?></h4>
                  <h4 style="text-align: right;">ID Checkout : <?php echo $val->id_checkout; ?></h4>
                </div>
                <div class="col-xs-9" style="padding-bottom: 20px;">
                  <?php if ($val->method_checkout != "COD"): ?>
                    <p><strong>Gambar bukti Transfer</strong></p>
                  <?php endif ?>
                  <?php if ($val->btransfer_1 != null) { ?>
                    <a href="#" data-toggle="modal" class="modalGbrTransfer" data-id="<?php echo $val->btransfer_1; ?>">
                      <img src="<?php echo base_url('assets/img/bukti_transfer/'.$val->btransfer_1);?>" class="gbrDetailPenjualan">
                    </a>
                  <?php } ?>
                  <?php if ($val->btransfer_2 != null) { ?>
                    <a href="#" data-toggle="modal" class="modalGbrTransfer" data-id="<?php echo $val->btransfer_2; ?>">
                      <img src="<?php echo base_url('assets/img/bukti_transfer/'.$val->btransfer_2);?>" class="gbrDetailPenjualan">
                    </a>
                  <?php } ?>
                  <?php if ($val->btransfer_3 != null) { ?>
                  <a href="#" data-toggle="modal" class="modalGbrTransfer" data-id="<?php echo $val->btransfer_3; ?>">
                    <img src="<?php echo base_url('assets/img/bukti_transfer/'.$val->btransfer_3);?>" class="gbrDetailPenjualan">
                  </a>
                  <?php } ?>
                </div>
                <div class="col-xs-3" style="padding-bottom: 20px;">
                  <?php if ($val->bukti_transfer_vendor != null) { ?>
                    <p><strong>Gambar bukti Konfirmasi</strong></p>
                    <a href="#" data-toggle="modal" class="modalGbrKonfirmasi" data-id="<?php echo $val->bukti_transfer_vendor; ?>">
                      <img src="<?php echo base_url('assets/img/bukti_konfirmasi/'.$val->bukti_transfer_vendor);?>" class="gbrDetailPenjualan">
                    </a>
                  <?php } ?>
                </div>
                <?php endforeach ?>
                  <table id="tabelTransOrderDetail" class="table table-bordered table-hover" cellspacing="0" width="100%">
                    <thead>
                      <tr>
                        <th style="width: 30px; text-align: center;">No</th>
                        <th style="width: 70px; text-align: center;">A/n Kirim</th>
                        <th style="width: 200px; text-align: center;">Nama Produk</th>
                        <th style="width: 30px; text-align: center;">Varian</th>
                        <?php if ($flag_tipe == 'jasa'): ?>
                        <th style="width: 30px; text-align: center;">Durasi</th>    
                        <?php elseif ($flag_tipe == 'reguler'): ?>
                        <th style="width: 30px; text-align: center;">Sat</th>
                        <?php endif ?>  
                        <th style="width: 30px; text-align: center;">Qty</th>
                        <th style="width: 50px; text-align: center;">Harga Satuan</th>
                        <th style="width: 50px; text-align: center;">Harga Total</th>
                      </tr>
                    </thead>
                    <tbody>
                    <?php if (count($hasil_data) != 0): ?>
                    <?php $no = 1; ?>
                        <?php $harga_total_produk = 0; ?>
                        <?php foreach ($hasil_data as $val ) : ?>
                        <?php $harga_total_produk += $val->harga_total; ?>  
                        <tr>
                          <td><?php echo $no++; ?></td>  
                          <td><?php echo $val->fname_kirim." ".$val->lname_kirim; ?></td>
                          <td><?php echo $val->nama_produk; ?></td>
                          <td><?php echo $val->varian_produk; ?></td>
                          <?php if ($flag_tipe == 'jasa'): ?>
                          <td><?php echo (int)$val->harga_total/(int)$val->harga; ?> Hari</td> 
                          <?php elseif ($flag_tipe == 'reguler'): ?>
                          <td><?php echo $val->nama_satuan; ?></td>
                          <?php endif ?>  
                          <td><?php echo $val->qty; ?></td>
                          <td>
                            <span class="pull-left">Rp.</span>
                            <span class="pull-right"><?php echo number_format($val->harga,0,",","."); ?></span>
                          </td>
                          <td>
                            <span class="pull-left">Rp.</span>
                            <?php if ($flag_tipe == 'jasa'): ?>
                            <span class="pull-right"><?php echo number_format(($val->harga * $val->qty)*((int)$val->harga_total/(int)$val->harga),0,",","."); ?></span>
                            <?php elseif ($flag_tipe == 'reguler'): ?>
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
                      <?php foreach ($hasil_data as $val ) : ?>
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
                  <div>
                    <?php if ($this->session->userdata('id_level_user') != '1') { ?>
                      <p>Apabila data sudah masuk pada tahap ini, maka <strong>Customer telah melakukan transfer ke Layanan Rekening Bersama.</strong> Mohon di konfirmasi agar uang penjualan dapat ditarik ke rekening anda. Terima Kasih</p>
                    <?php } ?>
                  </div>
                  <div style="padding-top: 30px; padding-bottom: 10px;">
                    <a class="btn btn-sm btn-danger" title="Kembali" onclick="javascript:history.back()"><i class="glyphicon glyphicon-menu-left"></i> Kembali</a>

                    <?php $id = $this->uri->segment(3); ?>
                    <?php $link_cetak = site_url('penjualan_adm/cetak_nota_penjualan/').$id.'/'.$flag_tipe; ?>
                    <?php if ($hasil_header[0]->status_confirm_vendor != '0') { ?>
                      <?php echo '<a class="btn btn-sm btn-success" href="'.$link_cetak.'" title="Print Nota" id="btn_print_surat_beli" target="_blank"><i class="glyphicon glyphicon-print"></i> Cetak Nota</a>';?>
                      <?php if ($this->session->userdata('id_level_user') == '1') { ?>
                        <?php if ($hasil_header[0]->status_confirm_adm == '0') { ?>
                          <?php if ($hasil_header[0]->status_confirm_customer == '1') { ?>
                            <?php echo '<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Confirm" onclick="editConfirmAdm('."'".$id."'".')"><i class="glyphicon glyphicon-check"></i> Confirm</a>';?>
                          <?php } ?>
                        <?php } ?>
                      <?php } ?>
                    <?php }else{ ?>
                      <?php if ($this->session->userdata('id_level_user') != '1') { ?>
                        <?php echo '<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Confirm" onclick="editConfirmPenjualan('."'".$id."'".')"><i class="glyphicon glyphicon-check"></i> Confirm</a>';?>
                        <?php echo '<a class="btn btn-sm btn-success" href="'.$link_cetak.'" title="Print Nota" id="btn_print_surat_beli" target="_blank"><i class="glyphicon glyphicon-print"></i> Cetak Nota</a>';?>
                      <?php } ?>
                    <?php } ?>
                  </div>
              </div>  
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
      </div>    
    </section>
    <!-- /.content -->                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    