    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Monitoring Penyewaan
      </h1>

      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i>Data Transaksi</a></li>
        <li class="active">Monitoring Penyewaan</li>
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
                      <th style="text-align: center; width: 5%;">NO</th>
                      <th style="text-align: center; width: 5%;">ID</th>
                      <th style="text-align: center; width: 10%;">Nama User</th>
                      <th style="text-align: center; width: 15%;">Alamat</th>
                      <th style="text-align: center; width: 10%;">Email</th>
                      <th style="text-align: center; width: 10%;">Telp</th>
                      <th style="text-align: center; width: 10%;">Tgl Checkout</th>
                      <th style="text-align: center; width: 10%;">Produk</th>
                      <th style="text-align: center; width: 5%;">Qty</th>
                      <th style="text-align: center; width: 5%;">Durasi</th>
                      <th style="text-align: center; width: 12%;">Tgl Expired</th>
                      <th style="text-align: center; width: 5%;">Action</th>
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