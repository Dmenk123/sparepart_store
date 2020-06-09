    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Detail Kelola Omset
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i>Data Transaksi</a></li>
        <li>Kelola Omset</li>
        <li class="active">Detail Kelola Omset</li>
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
                  <!-- <h4 style="text-align: center;"><strong>Detail Kelola Omset</strong></h4> -->
                </div>
                <?php foreach ($hasil_header as $val ) : ?>
                <div class="col-xs-4">
                  <h4 style="text-align: left;">ID Pembelian : <?php echo $val->id_pembelian; ?></h4>
                  <h4 style="text-align: left;">ID Checkout : <?php echo $val->id_checkout; ?></h4>
                </div>
                <div class="col-xs-4">
                 <h4 style="text-align: center;"><?php echo $val->penyedia." (".$val->rekening.")"; ?></h4>
                 <h4 style="text-align: center;">Tanggal Transaksi : <?php echo date('d-m-Y', strtotime($val->tanggal)); ?></h4>
                </div>
                <div class="col-xs-4">
                  <h4 style="text-align: right;">Total Nominal : Rp. <?php echo number_format($val->nominal,0,",","."); ?></h4>
                  <h4 style="text-align: right;">Bea Layanan : Rp. <?php echo number_format($val->biaya_adm,0,",","."); ?></h4>
                </div>

                <div class="col-xs-12">
                  <h3 style="text-decoration: underline; text-align: center;">Rincian Pengelolaan Omset</h3>
                </div>

                <?php endforeach ?>
                  <table id="tabelTransOrderDetail" class="table table-bordered table-hover" cellspacing="0" width="100%">
                    <thead>
                      <tr>
                        <th style="width: 5%; text-align: center;">No</th>
                        <th style="width: 30%; text-align: center;">Pelapak</th>
                        <th style="width: 15%; text-align: center;">Ongkos Kirim</th>
                        <th style="width: 15%; text-align: center;">Omset (Bruto)</th>
                        <th style="width: 15%; text-align: center;">Potongan omset 20%</th>
                        <th style="width: 15%; text-align: center;">Omset Total (nett)</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php if (count($hasil_data) != 0): ?>
                        <?php $no = 1; ?>
                        <?php $omset_total = 0; ?>
                        <?php $pendapatan_vendor = 0; ?>
                        <?php $pendapatan_jasa = 0; ?>
                        <?php foreach ($hasil_data as $val ) : ?>
                          <?php $potongan = (float)$val->h_total * 20 / 100; ?>  
                          <?php $omset_total = ((float)$val->h_total - $potongan ) + (float)$val->h_ongkir; ?>
                          <?php $pendapatan_vendor += $omset_total; ?>
                          <?php $pendapatan_jasa += $potongan; ?>
                          <tr>
                            <td><?php echo $no++; ?></td>  
                            <td><?php echo $val->nama_vendor; ?></td>
                            <td>
                              <span class="pull-left">Rp.</span>
                              <span class="pull-right"><?php echo number_format($val->h_ongkir,2,",","."); ?></span>
                            </td>
                            <td>
                              <span class="pull-left">Rp.</span>
                              <span class="pull-right"><?php echo number_format($val->h_total,2,",","."); ?></span>
                            </td>
                            <td>
                              <span class="pull-left">Rp.</span>
                              <span class="pull-right"><?php echo number_format($potongan,2,",","."); ?></span>
                            </td>
                            <td>
                              <span class="pull-left">Rp.</span>
                              <span class="pull-right"><?php echo number_format($omset_total,2,",","."); ?></span>
                            </td>
                          </tr>
                        <?php endforeach ?>
                      <?php endif ?>
                      <tr>
                        <td colspan="4" align="center"><strong>Dibayarkan Pada Pelapak/Vendor</strong></td>
                        <td colspan="2" align="center">
                          <span class="pull-left">Rp.</span>
                          <span class="pull-right"><?php echo number_format($pendapatan_vendor,2,",","."); ?></span>
                        </td>
                      </tr>
                      <tr>
                        <td colspan="4" align="center"><strong>Pendapatan Jasa Web (Bruto)</strong></td>
                        <td colspan="2">
                            <span class="pull-left">Rp.</span>
                            <span class="pull-right"><?php echo number_format($pendapatan_jasa,2,",","."); ?></span>
                        </td>
                      </tr>
                      <tr>
                        <td colspan="4" align="center"><strong>Pendapatan Jasa Web dikurangi Bea rekber (Nett)</strong></td>
                        <td colspan="2">
                          <strong>
                            <span class="pull-left">Rp.</span>
                            <span class="pull-right"><?php echo number_format($pendapatan_jasa - $hasil_header[0]->biaya_adm,2,",","."); ?></span>
                          </strong>
                        </td>
                      </tr>  
                    </tbody>
                  </table>
                  <div>
                    <?php if ($hasil_header[0]->status_penarikan == '1') { ?>
                      <p>Apabila data sudah masuk pada tahap ini, maka <strong>Uang pada rekening bersama dapat ditarik agar dibayarkan ke vendor dan sebagian menjadi pendapatan OnPets</strong> Mohon di konfirmasi. Terima Kasih</p>
                    <?php } ?>
                  </div>
                  <div style="padding-top: 30px; padding-bottom: 10px;">
                    <a class="btn btn-sm btn-danger" title="Kembali" onclick="javascript:history.back()"><i class="glyphicon glyphicon-menu-left"></i> Kembali</a>

                    <?php $id = $this->uri->segment(3); ?>
                    <?php $link_cetak = site_url('kelola_omset_adm/cetak_nota/').$id; ?>
                    <?php if ($hasil_header[0]->status_penarikan == '2') { ?>
                      <?php echo '<a class="btn btn-sm btn-success" href="'.$link_cetak.'" title="Print Nota" id="btn_print_surat_beli" target="_blank"><i class="glyphicon glyphicon-print"></i> Cetak Nota</a>';?>
                    <?php }else{ ?>
                      <?php echo '<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Confirm" onclick="confirmOmset()"><i class="glyphicon glyphicon-check"></i> Confirm</a>';?>
                      <?php echo '<a class="btn btn-sm btn-success" href="'.$link_cetak.'" title="Print Nota" id="btn_print_surat_beli" target="_blank"><i class="glyphicon glyphicon-print"></i> Cetak Nota</a>';?>
                    <?php } ?>
                  </div>
              </div> 

              <form id="form_confirm_omset" name="formConfirmOmset">
                <input type="hidden" class="form-control" id="id_t_rekber" name="id_t_rekber" 
                  value="<?=$id;?>">
                <input type="hidden" class="form-control" id="id_pembelian" name="id_pembelian" 
                  value="<?=$hasil_header[0]->id_pembelian;?>">
                <input type="hidden" class="form-control" id="id_checkout" name="id_checkout"
                  value="<?=$hasil_header[0]->id_checkout;?>">
                <input type="hidden" class="form-control" id="nominal" name="nominal"
                  value="<?=$hasil_header[0]->nominal;?>">
                <input type="hidden" class="form-control" id="bea_layanan" name="bea_layanan"
                  value="<?=$hasil_header[0]->biaya_adm;?>">
                <?php $omset_total = 0; ?>
                <?php $pendapatan_vendor = 0; ?>
                <?php $pendapatan_jasa = 0; ?>
                <?php foreach ($hasil_data as $val ) : ?>
                  <?php $potongan = (float)$val->h_total * 20 / 100; ?>  
                  <?php $omset_total = ((float)$val->h_total - $potongan ) + (float)$val->h_ongkir; ?>
                  <?php $pendapatan_vendor += $omset_total; ?>
                  <?php $pendapatan_jasa += $potongan; ?>
                  <input type="hidden" class="form-control" id="ongkir" name="ongkir[]" 
                    value="<?=$val->h_ongkir;?>">
                  <input type="hidden" class="form-control" id="omset_bruto" name="omset_bruto[]"
                    value="<?=$val->h_total;?>">
                  <input type="hidden" class="form-control" id="potongan" name="potongan[]"
                    value="<?=$potongan?>">
                  <input type="hidden" class="form-control" id="omset_nett" name="omset_nett[]"
                    value="<?=$omset_total?>">
                  <input type="hidden" class="form-control" id="id_vendor" name="id_vendor[]"
                    value="<?=$val->id_vendor?>">
                <?php endforeach ?>
                <input type="hidden" class="form-control" id="bea_vendor" name="bea_vendor"
                  value="<?=$pendapatan_vendor;?>">
                <input type="hidden" class="form-control" id="laba_adm_bruto" name="laba_adm_bruto"
                  value="<?=$pendapatan_jasa;?>">
                <input type="hidden" class="form-control" id="laba_adm_nett" name="laba_adm_nett"
                  value="<?=$pendapatan_jasa - $hasil_header[0]->biaya_adm;?>">
              </form>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
      </div>    
    </section>
    <!-- /.content -->                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    