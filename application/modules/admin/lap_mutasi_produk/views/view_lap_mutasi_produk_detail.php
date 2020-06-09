    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Laporan Mutasi Produk
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i>Laporan</a></li>
        <li class="active">Laporan Mutasi Produk</li>
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
                    <h4 style="text-align: center;"><strong>Laporan Mutasi Produk</strong></h4>
                    <h4 style="text-align: center;"><?= $nama_vendor; ?></h4>
                  </div>
                  <div class="col-xs-12" style="padding-bottom: 30px;">
                    <h4 style="text-align: center;">Periode Tanggal : <?= $tgl_awal;?> s/d <?= $tgl_akhir;?></h4>
                  </div>
                    <table id="laporanOmsetVendor" class="table table-bordered table-hover" cellspacing="0" width="100%">
                      <thead>
                        <tr>
                          <th style="width: 5%; text-align: center;">No</th>
                          <th style="width: 35%; text-align: center;">ID - Nama Produk</th>
                          <th style="width: 15%; text-align: center;">Stok Awal</th>
                          <th style="width: 15%; text-align: center;">Masuk</th>
                          <th style="width: 15%; text-align: center;">Keluar</th>
                          <th style="width: 15%; text-align: center;">Stok Sisa</th>
                        </tr>
                      </thead>
                      <tbody>
                      <?php if (count($hasil_data) != 0): ?>
                      <?php $no = 1; ?>
                        <?php foreach ($hasil_data as $val ) : ?>
                          <tr>
                            <td><?php echo $no++; ?></td> 
                            <td><?php echo $val->id_produk.' - '.$val->nama_produk; ?></td>
                            <td><?php echo $val->stok_awal; ?></td>
                            <td><?php echo $val->masuk; ?></td>
                            <td><?php echo $val->keluar; ?></td>
                            <td><?php echo $val->stok_sisa; ?></td>
                          </tr>
                        <?php endforeach ?> 
                      <?php endif ?>   
                      </tbody>
                    </table>
                    <div style="padding-top: 30px; padding-bottom: 10px;">
                      <a class="btn btn-sm btn-danger" title="Kembali" onclick="javascript:history.back()"><i class="glyphicon glyphicon-menu-left"></i> Kembali</a>
                      <?php $link_print = site_url("lap_mutasi_produk/cetak_report_mutasi/".$tgl_awal."/".$tgl_akhir); ?>
                      <?php echo '<a class="btn btn-sm btn-success" href="'.$link_print.'" target="_blank" title="Print Laporan Mutasi" id="btn_print_laporan_mutasi"><i class="glyphicon glyphicon-print"></i> Cetak</a>';?>
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