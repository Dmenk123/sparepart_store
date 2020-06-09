    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Laporan Omset Vendor
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i>Laporan</a></li>
        <li class="active">Laporan Omset Vendor</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
              <form id="form_add_kat" name="form_add_kat" method="get">
                <div class="form-row">
                  <div class="form-group col-md-6">
                    <label for="lblTanggal" class="lblTanggal">Tanggal Awal</label>
                    <input type="text" class="form-control tanggal" id="tgl_awal" name="tgl_awal" placeholder="DD-MM-YYYY" autocomplete="off" value="<?= $this->input->get('tgl_awal'); ?>">
                  </div>
                  <div class="form-group col-md-6">
                    <label for="lblTanggal" class="lblTanggal">Tanggal Akhir</label>
                    <input type="text" class="form-control tanggal" id="tgl_akhir" name="tgl_akhir" placeholder="DD-MM-YYYY" autocomplete="off" value="<?= $this->input->get('tgl_akhir');?>">
                  </div> 
                </div>
                <div class="form-row">
                  <div class="form-group col-md-12">
                    <input type="submit" value="Sumbit" class="btn btn-primary pull-right">
                  </div> 
                </div>
              </form> 
            </div>
          </div>
          
          <!-- ------------------------------------------------------------------------------ -->
          <?php if ($this->input->get('tgl_awal') != '' && $this->input->get('tgl_akhir') != '' ) { ?>
            <div class="box">
              <!-- /.box-header -->
              <div class="box-body">
                <div class="table-responsive">
                  <div class="col-xs-12">
                    <h4 style="text-align: center;"><strong>Laporan Omset</strong></h4>
                    <h4 style="text-align: center;"><?= $nama_vendor; ?></h4>
                  </div>
                  <div class="col-xs-12" style="padding-bottom: 30px;">
                    <h4 style="text-align: center;">Periode Tanggal : <?= $tgl_awal;?> s/d <?= $tgl_akhir;?></h4>
                  </div>
                    <table id="laporanOmsetVendor" class="table table-bordered table-hover" cellspacing="0" width="100%">
                      <thead>
                        <tr>
                          <th style="width: 5%; text-align: center;">No</th>
                          <th style="width: 15%; text-align: center;">ID Pembelian</th>
                          <th style="width: 15%; text-align: center;">Tgl Dibayarkan</th>
                          <th style="width: 15%; text-align: center;">Bea Ongkir</th>
                          <th style="width: 15%; text-align: center;">Omset (Bruto)</th>
                          <th style="width: 15%; text-align: center;">Potongan (20%)</th>
                          <th style="width: 20%; text-align: center;">Omset (Nett)</th>
                        </tr>
                      </thead>
                      <tbody>
                      <?php if (count($hasil_data) != 0): ?>
                      <?php $no = 1; ?>
                      <?php 
                        $tot_ongkir = 0;
                        $tot_omset_b = 0;
                        $tot_potongan = 0;
                        $tot_omset_n = 0;
                      ?>
                        <?php foreach ($hasil_data as $val ) : ?>
                          <?php $tot_ongkir += $val->ongkir; ?>
                          <?php $tot_omset_b += $val->omset_bruto; ?>
                          <?php $tot_potongan += $val->potongan; ?>
                          <?php $tot_omset_n += $val->omset_nett; ?>
                          <tr>
                            <td><?php echo $no++; ?></td> 
                            <td><?php echo $val->id_pembelian; ?></td>
                            <td style="text-align: center;"><?php echo date('Y-m-d', strtotime($val->tgl_bayar)); ?></td>
                            <td>
                              <div class="pull-left">Rp. </div>
                              <div class="pull-right"><?php echo number_format($val->ongkir,2,",","."); ?></div>
                            </td>
                            <td>
                              <div class="pull-left">Rp. </div>
                              <div class="pull-right"><?php echo number_format($val->omset_bruto,2,",","."); ?></div>
                            </td>
                            <td>
                              <div class="pull-left">Rp. </div>
                              <div class="pull-right"><?php echo number_format($val->potongan,2,",","."); ?></div>
                            </td>
                            <td>
                              <div class="pull-left">Rp. </div>
                              <div class="pull-right"><?php echo number_format($val->omset_nett,2,",","."); ?></div>
                            </td>
                          </tr>
                        <?php endforeach ?>
                        <tr>
                          <td colspan="3" style="text-align: center;">
                            Jumlah
                          </td>
                          <td>
                            <div class="pull-left">Rp. </div>
                            <div class="pull-right"><?= number_format($tot_ongkir,2,",","."); ?></div>
                          </td>
                          <td>
                            <div class="pull-left">Rp. </div>
                            <div class="pull-right"><?= number_format($tot_omset_b,2,",","."); ?></div>
                          </td>
                          <td>
                            <div class="pull-left">Rp. </div>
                            <div class="pull-right"><?= number_format($tot_potongan,2,",","."); ?></div>
                          </td>
                          <td>
                            <div class="pull-left">Rp. </div>
                            <div class="pull-right"><?= number_format($tot_omset_n,2,",","."); ?></div>
                          </td>
                        </tr>
                        <tr>
                          <td colspan="6" style="text-align: center;">
                            <strong>Total Pendapatan (Nett)</strong>
                          </td>
                          <td>
                            <div class="pull-left"><strong>Rp. </strong></div>
                            <div class="pull-right"><strong><?= number_format($tot_omset_n,2,",","."); ?></strong></div>
                          </td>
                        </tr>  
                      <?php endif ?>   
                      </tbody>
                    </table>
                    <div style="padding-top: 30px; padding-bottom: 10px;">
                      <a class="btn btn-sm btn-danger" title="Kembali" onclick="javascript:history.back()"><i class="glyphicon glyphicon-menu-left"></i> Kembali</a>
                      <?php $link_print = site_url("lap_omset_vendor/cetak_report/".$tgl_awal."/".$tgl_akhir); ?>
                      <?php echo '<a class="btn btn-sm btn-success" href="'.$link_print.'" target="_blank" title="Print Laporan Stok" id="btn_print_laporan_order"><i class="glyphicon glyphicon-print"></i> Cetak</a>';?>
                    </div>
                </div>  
              </div>
              <!-- /.box-body -->
            </div>
          <?php } ?>
          <!-- /.box -->
        </div>
      </div>    
    </section>
    <!-- /.content -->                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    