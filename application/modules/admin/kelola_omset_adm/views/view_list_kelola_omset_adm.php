    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Kelola Omset
      </h1>

      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i>Data Transaksi</a></li>
        <li class="active">Kelola Omset</li>
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
                <table id="tableKelolaOmset" class="table table-bordered table-hover" cellspacing="0" width="100%">
                  <thead>
                    <tr>
                      <th style="text-align: center;">ID Pembelian</th>
                      <th style="text-align: center;">Rekening</th>
                      <th style="text-align: center;">Layanan</th>
                      <th style="text-align: center;">Bea Layanan</th>
                      <th style="text-align: center;">Nilai Total</th>
                      <th style="text-align: center;">Status</th>
                      <th style="width: 50px; text-align: center;">Action</th>
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