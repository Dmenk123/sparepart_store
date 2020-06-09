    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Penjualan
      </h1>

      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i>Data Transaksi</a></li>
        <li class="active">Penjualan</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-s12">
          <div class="box">
            <div class="box-header">
              <button class="btn btn-default" onclick="reload_table()"><i class="glyphicon glyphicon-refresh"></i> Reload</button>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <div class="table-responsive">
                <table id="tableConfirmJual" class="table table-bordered table-hover" cellspacing="0" width="100%">
                  <thead>
                    <tr>
                      <th style="text-align: center;">ID</th>
                      <th style="text-align: center;">Nama User</th>
                      <th style="text-align: center;">Nama Kirim</th>
                      <th style="text-align: center;">Alamat</th>
                      <th style="text-align: center;">Kode Ref</th>
                      <th style="text-align: center;">Confirm Vendor</th>
                      <?php if ($this->session->userdata('id_level_user') == '1') { ?>
                        <th style="text-align: center;">Confirm Cust</th>
                        <th style="text-align: center;">Confirm Adm</th>
                      <?php } ?>
                      <th style="width: 160px; text-align: center;">Action</th>
                    </tr>
                  </thead>
                  <tbody>

                  </tbody>
                </table>
              </div>  
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
      </div>    
    </section>
    <!-- /.content -->