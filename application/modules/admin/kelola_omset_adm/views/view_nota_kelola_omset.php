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

    .pull-right{
      text-align: right;
    }

    .pull-left{
      text-align: left;
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
      <h2 style="text-align: center;"><strong>Nota Pengelolaan Omset</strong></h2>
      <table class="tbl-header">
        <tr>
          <td align="left">
              <p style="text-align: left;" class="head-left">ID Pembelian : <?php echo $val->id_pembelian; ?></p>
              <p style="text-align: left;" class="head-left">ID Checkout : <?php echo $val->id_checkout; ?></p>
          </td>
          <td align="center">
              <p style="text-align: center;" class="head-left"><?php echo $val->penyedia." (".$val->rekening.")"; ?></p>
              <p style="text-align: center;" class="head-left">Tanggal Transaksi : <?php echo date('d-m-Y', strtotime($val->tanggal)); ?></p>
          </td>
          <td align="right">
              <p style="text-align: right; font-size: 14px" class="head-left">Total Nominal : Rp. <?php echo number_format($val->nominal,0,",","."); ?></p>
              <p style="text-align: right; font-size: 14px" class="head-left">Bea Layanan : Rp. <?php echo number_format($val->biaya_adm,0,",","."); ?></p>
          </td>
        </tr>
        <tr> 
          <td align="left" class="head-left" colspan="3">
            <h3 style="text-decoration: underline; text-align: center;">Rincian Pengelolaan Omset</h3>
          </td>
        </tr>
       <!--  <tr class="inner-head-left"> 
          <td align="left" class="inner-head-left" colspan="3">
            <p style="text-align: left; font-size: 12px" class="inner-head-left">Terimakasih telah membeli produk dari kami, berikut adalah produk-produk pembelian dari anda yang akan kami kirimkan :</p>
          </td>
        </tr>  -->   
      </table>
    <?php endforeach ?>

    <table id="tbl_content" class="table table-bordered table-hover" cellspacing="0" width="100%" border="1">
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
              <td class="pull-right">
                <span class="pull-left">Rp.</span>
                <span class="pull-right"><?php echo number_format($val->h_ongkir,2,",","."); ?></span>
              </td>
              <td class="pull-right">
                <span class="pull-left">Rp.</span>
                <span class="pull-right"><?php echo number_format($val->h_total,2,",","."); ?></span>
              </td>
              <td class="pull-right">
                <span class="pull-left">Rp.</span>
                <span class="pull-right"><?php echo number_format($potongan,2,",","."); ?></span>
              </td>
              <td class="pull-right">
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
          <td colspan="2" class="pull-right">
              <span class="pull-left">Rp.</span>
              <span class="pull-right"><?php echo number_format($pendapatan_jasa,2,",","."); ?></span>
          </td>
        </tr>
        <tr>
          <td colspan="4" align="center"><strong>Pendapatan Jasa Web dikurangi Bea rekber (Nett)</strong></td>
          <td colspan="2" class="pull-right">
            <strong>
              <span class="pull-left">Rp.</span>
              <span class="pull-right"><?php echo number_format($pendapatan_jasa - $hasil_header[0]->biaya_adm,2,",","."); ?></span>
            </strong>
          </td>
        </tr>  
      </tbody>
    </table>
    <table class="tbl-footer">
        <tr>
          <td style="text-align: right;">
            <p>Surabaya, <?= date('d-m-Y');?></p>
            <p><strong>Dibuat Oleh</strong> </p>
          </td>
        </tr>
        <tr>
          <td style="text-align: right;">
            <p>
              ( <?php echo $this->session->userdata('fname_user')." ".$this->session->userdata('lname_user');?> )
            </p>      
          </td>
        </tr>
      </table>
  </div>          
</body>
</html>