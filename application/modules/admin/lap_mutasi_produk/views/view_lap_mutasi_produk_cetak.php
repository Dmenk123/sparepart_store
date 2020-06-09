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
      margin-bottom: 10px;
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
      padding-top: 75px;
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

    .pull-left{
      float: left;
    }

    .pull-right{
      float: right;
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
  <div class="container"> 
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
    <table class="tbl-header">
      <tr>
        <td align="center">
          <h2 style="text-align: center;"><strong>Laporan Omset - <?= $nama_vendor; ?></strong></h2>
          <h4 style="text-align: center;"><strong>Periode Tanggal : <?= $tgl_awal;?> s/d <?= $tgl_akhir;?></strong></h4>
        </td>
      </tr>           
    </table>
    <table id="tbl_content" class="table table-bordered table-hover" cellspacing="0" width="100%" border="2">
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
    <table class="tbl-footer">
        <tr>
          <td align="right">
            <strong>Surabaya,  <?php echo date('d-m-Y'); ?></strong>
            <p style="text-align: right;" class="foot-right"><strong>Dibuat Oleh</strong> </p>
          </td>
        </tr>
        <tr>
          <td align="right">
            <p style="text-align: right;" class="foot-right">( <?php echo $nama_vendor;?> ) </p>      
          </td>
        </tr>
    </table>
  </div>          
</body>
</html>