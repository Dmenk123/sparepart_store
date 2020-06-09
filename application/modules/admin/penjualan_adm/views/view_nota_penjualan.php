<?php require_once(APPPATH.'views/temp_img_laporan.php'); ?>
<html>
<head>
  <title><?php echo $title; ?></title>
  <link rel="shortcut icon" href="<?php echo config_item('assets'); ?>img/logo_thumb.png" />
  <style type="text/css">
    #outtable{
      padding: 20px;
      border:1px solid #e3e3e3;
      width:600px;
      border-radius: 5px;
    }
    .short{
      width: 50px;
    }
    .normal{
      width: 150px;
    }

    .tbl-outer{
      color:#070707;
      margin-bottom: 35px;
    }
    
    .outer-left{
      padding: 2px;
      border: 0px solid white;
      border-color: white;
      margin: 0px;
      background: white;
    }

    .head-left{
      padding-top: 5px;
      padding-bottom: 0px;
      border: 0px solid white;
      border-color: white;
      margin: 0px;
      background: white;
    }

    .tbl-footer{
      width: 100%;
      color:#070707;
      border-top: 0px solid white;
      border-color: white;
      padding-top: 30px;
    }

    .head-right{
       padding-bottom: 0px;
       border: 0px solid white;
       border-color: white;
       margin: 0px;
    }

    .tbl-header{
      width: 100%;
      color:#070707;
      border-color: #070707;
      border-top: 2px solid #070707;
    }

    #tbl_content{
      padding-top: 10px;
    } 

    .tbl-footer td{
      border-top: 0px;
      padding: 10px;
    }

    .tbl-footer tr{
      background: white;
    }

    .foot-center{
      padding-left: 70px;
    }

    .inner-head-left{
       padding-top: 20px;
       border: 0px solid white;
       border-color: white;
       margin: 0px;
       background: white;
    }

    .tbl-content-footer{
      width: 100%;
      color:#070707;
      padding-top: 0px;
    }

    table{
      border-collapse: collapse;
      font-family: arial;
      color:black;
      font-size: 12px;
    }

    thead th{
      text-align: left;
      padding: 10px;
      font-style: bold;
    }

    tbody td{
      padding: 10px;
    }

    tbody tr:nth-child(even){
      background: #F6F5FA;
    }

    tbody tr:hover{
      background: #EAE9F5
    }
  </style>
</head>
<body>
  <!-- Main content -->
  <div class="container">
    <?php foreach ($hasil_header as $val ) : ?>
      <table class="tbl-outer">
        <tr>
          <td align="left" class="outer-left">
            <?php echo $img_laporan; ?>
          </td>
          <td align="right" class="outer-left">
            <p style="text-align: left; font-size: 14px" class="outer-left">
              <strong>OnPets E-Marketplace</strong>
            </p>
            <p style="text-align: left; font-size: 12px" class="outer-left">Jl. Dukuh Kupang Barat no. 44, Kota Surabaya - Jawa Timur</p>
          </td>
        </tr>
      </table>
      <?php if ($flag_tipe == 'reguler'): ?>
        <h2 style="text-align: center;"><strong>Nota Penjualan Produk - <?= $nama_vendor; ?></strong></h2>  
      <?php elseif ($flag_tipe == 'jasa'): ?>
        <h2 style="text-align: center;"><strong>Detail Penjualan Jasa - <?= $nama_vendor; ?></strong></h2>
      <?php endif ?>
      <table class="tbl-header">
        <tr>
          <td align="left">
              <p style="text-align: left;" class="head-left">Nama Customer : <?php echo $val->fname_user." ".$val->lname_user; ?></p>
              <p style="text-align: left;" class="head-left">ID Pembelian : <?php echo $val->id_pembelian; ?></p>
          </td>
          <?php if ($flag_tipe == 'reguler'): ?>
          <td align="center">
            <?php if ($val->method_checkout != "COD"): ?>
              <p style="text-align: center;" class="head-left">Ekspedisi : <?php echo $val->jasa_ekspedisi." | ".$val->pilihan_paket." | ".$val->estimasi_datang; ?></p>
            <?php endif ?>
          </td>
          <?php elseif ($flag_tipe == 'jasa'): ?>
            <td align="center">
            </td>
          <?php endif ?>
          <td align="right">
              <p style="text-align: right; font-size: 14px" class="head-left">
                Tanggal Pembelian : <?php echo $val->tgl_pembelian; ?>
              </p>
              <p style="text-align: right; font-size: 14px" class="head-left">
                ID Checkout : <?php echo $val->id_checkout; ?>
              </p>
          </td>
        </tr>   
      </table>
    <?php endforeach ?>
    <br><br>
    <table id="tbl_content" class="table table-bordered table-hover" cellspacing="0" width="100%" border="1">
      <thead>
        <tr>
          <th style="width: 5%; text-align: center;">No</th>
          <th style="width: 15%; text-align: center;">A/n Kirim</th>
          <th style="width: 30%; text-align: center;">Nama Produk</th>
          <th style="width: 10%; text-align: center;">Varian</th>
          <?php if ($flag_tipe == 'jasa'): ?>
          <th style="width: 5%; text-align: center;">Durasi</th>    
          <?php elseif ($flag_tipe == 'reguler'): ?>
          <th style="width: 5%; text-align: center;">Sat</th>
          <?php endif ?> 
          <th style="width: 5%; text-align: center;">Qty</th>
          <th style="width: 15%; text-align: center;">Harga Satuan</th>
          <th style="width: 20%; text-align: center;">Harga Total</th>
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
          <td align="right">
            <span class="pull-left">Rp.</span>
            <span class="pull-right"><?php echo number_format($val->harga,0,",","."); ?></span>
          </td>
          <td align="right">
            <span class="pull-left">Rp.</span>
            <?php if ($flag_tipe == 'jasa'): ?>
            <span class="pull-right"><?php echo number_format(($val->harga * $val->qty)*((int)$val->harga_total/(int)$val->harga),0,",","."); ?></span>
            <?php elseif ($flag_tipe == 'reguler'): ?>
            <span class="pull-right"><?php echo number_format($val->harga,0,",","."); ?></span>
            <?php endif ?>
          </td>
        </tr>
        <?php endforeach ?>
      <?php endif ?>
      <tr>
        <td colspan="6" align="center"><strong>Harga Keseluruhan</strong></td>
        <td colspan="2" align="center">
          <strong>Rp. <?php echo number_format($harga_total_produk,0,",","."); ?></strong>
        </td>
      </tr>
      <tr>
        <?php $bea_ongkir = 0; ?>
        <?php foreach ($hasil_data as $val ) : ?>
          <?php $bea_ongkir += $val->harga_ongkir; ?>
        <?php endforeach ?> 
        <?php $bea_fix = $harga_total_produk + $bea_ongkir;?>
        <td colspan="6" align="center"><strong>+ Biaya Ongkir</strong></td>
        <td align="center">
          <strong>Rp. <?php echo number_format($bea_ongkir,0,",","."); ?></strong>
        </td>
        <td align="center">
          <strong>Rp. <?php echo number_format($bea_fix,0,",","."); ?></strong>
        </td>
      </tr>
      </tbody>
    </table>
    <br><br>
    <table class="tbl-footer">
      <tr>
        <td align="left">
          <p style="text-align: left;" class="foot-left"><strong>Customer</strong> </p>
        </td>
        <td align="right">
          <strong>Surabaya,  <?php echo date('d-m-Y'); ?></strong>
          <p style="text-align: right;" class="foot-right"><strong>Dibuat Oleh</strong> </p>
        </td>
      </tr>
      <tr>
        <td align="left">
          <p style="text-align: left;" class="foot-left">
              ( <?php echo $hasil_header[0]->fname_user." ".$hasil_header[0]->lname_user; ?> )  
          </p>
        </td>
        <td align="right">
          <p style="text-align: right;" class="foot-right">
              ( <?php echo $this->session->userdata('fname_user')." ".$this->session->userdata('lname_user');?> )
          </p>
        </td>
      </tr>
    </table>
  </div>          
</body>
</html>