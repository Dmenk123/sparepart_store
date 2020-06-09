    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Pengaturan Produk
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i>Data transaksi</a></li>
        <li class="active">Atur Produk</li>
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
                    <div class="form-group col-md-12">
                      <label for="lblKatErr" class="lblKatErr">Nama Kategori</label>
                      <input type="hidden" value="<?= $id_vendor; ?>" name="id_vendor" id="id_vendor">
                      <select class="form-control" id="id_kategori" name="id_kategori" style="width: 100%;" required>
                          <option value="">-- Pilih Kategori --</option>
                          <?php foreach ($data_kat as $value) { ?>
                            <option value="<?=$value['id_kategori']; ?>"
                              <?php if($this->input->get('id_kategori') == $value['id_kategori']) { ?>
                                selected
                              <?php }?> >
                                <?=$value['nama_kategori']; ?>
                            </option>
                          <?php } ?>
                      </select>
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

          <!-- ------------------------------------------------------------------------------------------------- -->
          <?php if ($this->input->get('id_vendor') != '' && $this->input->get('id_kategori') != '' ) { ?>
          <div class="box">
            <div class="box-header">
              <button class="btn btn-success" onclick="add_produk()"><i class="glyphicon glyphicon-plus"></i> Add Produk</button>
              <button class="btn btn-default" onclick="reload_table()"><i class="glyphicon glyphicon-refresh"></i> Reload</button>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <div class="table-responsive"> 
                <table id="tabel_produk" class="table table-bordered table-hover" cellspacing="0" width="100%">
                  <thead>
                    <tr>
                      <th>No</th>
                      <th>Gambar</th>
                      <th>Kode</th>
                      <th>Nama</th>
                      <th>Satuan</th>
                      <th>Stok</th>
                      <th>Harga</th>
                      <th>Status</th>
                      <th style="width: 100px;">Action</th>
                    </tr>
                  </thead>
                  <tbody>
                  </tbody>
                </table>
               </div>
               <!-- responsive --> 
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div> 
        <?php } ?>
      </div>    
    </section>
    <!-- /.content -->
