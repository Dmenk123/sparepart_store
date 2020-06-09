<div class="container">
   
      <ul class="breadcrumb">
         <li><a href="#">Home</a></li>
         <li><?php echo $this->uri->segment(1); ?></li>
         <li><?php echo $this->uri->segment(2); ?></li>
      </ul>
  

   <div class="col-md-12">
         <form method="post" enctype="multipart/form-data" id="form_update_profil_vendor" name="formUpdateProfilVendor">
         <div class="form-group col-md-12">
            <h2>Edit Data Profil Vendor</h2>
         </div>
         <?php if (isset($data_user)) { ?>
            <input type="hidden" class="form-control" id="idUser" name="frm_id_user" value="<?php echo $data_user->id_user; ?>">
            <input type="hidden" class="form-control" id="idVendor" name="frm_id_vendor" value="<?php echo $data_user->id_vendor; ?>">  
            <div class="form-group col-md-6">
               <label class="lblNameVendorErr">Nama Vendor*</label>
               <input type="text" class="form-control" id="name_vendor" name="frm_name_vendor" value="<?php echo $data_user->nama_vendor; ?>">
            </div>
            <div class="form-group col-md-6">
               <label class="lblJenisUsahaErr">Jenis Usaha</label>
               <input type="text" class="form-control" id="jenis_usaha" name="frm_jenis_usaha" value="<?php echo $data_user->jenis_usaha_vendor; ?>">
            </div>
            <div class="form-group col-md-6">
               <label class="lblFnameErr">Nama Depan*</label>
               <input type="text" class="form-control" id="fname_user" name="frm_fname_user" value="<?php echo $data_user->fname_user; ?>">
            </div>
            <div class="form-group col-md-6">
               <label class="lblLnameErr">Nama Belakang</label>
               <input type="text" class="form-control" id="lname_user" name="frm_lname_user" value="<?php echo $data_user->lname_user; ?>">
            </div>
            <div class="form-group col-md-6">
               <label class="lblEmailErr">Email*</label>
               <input type="email" class="form-control" id="email_user" name="frm_email_user" value="<?php echo $data_user->email; ?>">
               <input type="hidden" id="email_hdn" value="<?php echo $data_user->email; ?>">
            </div>
            <div class="form-group col-md-6">
               <label class="lblPassErr">Password*</label>
               <input type="text" class="form-control" id="pass_user" name="frm_pass_user" value="<?php echo $data_user->password; ?>">
            </div>
            <div class="form-group col-md-6">
               <label class="lblTgllhrErr">Tgl Lahir*</label>
               <input type="text" class="form-control" id="tgllhr_user" name="frm_tgllhr_user">
            </div>
            <div class="form-group col-md-6">
               <label class="lblTelpErr">Nomor Telp*</label>
               <input type="text" class="form-control numberinput" id="telp_user" name="frm_telp_user" value="<?php echo $data_user->no_telp_user; ?>">
            </div>
            <div class="form-group col-md-6">
               <label class="lblProvErr">Provinsi Tempat usaha*</label>
               <select class="form-control" id="prov_user" name="frm_prov_user" style="width: 100%;"></select>
            </div>
            <div class="form-group col-md-6">
               <label class="lblKotaErr">Kota/Kabupaten Tempat usaha*</label>
               <select class="form-control" id="kota_user" name="frm_kota_user" style="width: 100%;"></select>
            </div>
            <div class="form-group col-md-6">
               <label class="lblKecErr">Kecamatan Tempat usaha*</label>
               <select class="form-control" id="kec_user" name="frm_kec_user" style="width: 100%;"></select>
            </div>
            <div class="form-group col-md-6">
               <label class="lblKelErr">Kelurahan/Desa Tempat usaha*</label>
               <select class="form-control" id="kel_user" name="frm_kel_user" style="width: 100%;"></select>
            </div>
            <div class="form-group col-md-12">
               <label class="lblAlamatErr">Alamat Tempat usaha*</label>
               <textarea class="form-control" id="alamat_user" name="frm_alamat_user" style="width: 100%;"><?php echo $data_user->alamat_user; ?></textarea>
            </div>
            <div class="form-group col-md-6">
               <label class="lblKdposErr">Kode Pos*</label>
               <input type="text" class="form-control numberinput" id="kode_pos" name="frm_kode_pos" value="<?php echo $data_user->kode_pos; ?>">
            </div>
            <div class="form-group col-md-6">
               <label class="lblKtpErr">NO KTP*</label>
               <input type="text" class="form-control numberinput" id="ktp" name="frm_ktp" value="<?php echo $data_user->ktp_pemilik; ?>">
            </div>
            <div class="form-group col-md-6">
               <label class="lblFotoErr">Foto Profil</label>
               <input type="file" id="foto" name="frm_foto" accept=".png, .jpg, .jpeg" value="<?php echo $data_user->foto_user; ?>">
               Foto Tersimpan :  <a href="#" onclick="tampil_foto('<?php echo $data_user->foto_user; ?>')">
                        <?php echo $data_user->foto_user; ?>
                     </a>
               <p class="help-block"><strong>Catatan : Apabila tidak ingin merubah foto, Mohon lewati pilihan ini</strong></p>
            </div>
            <div class="form-group col-md-6">
               <label class="lblFotoKtp">Foto KTP</label>
               <input type="file" id="foto_ktp" name="frm_foto_ktp" accept=".png, .jpg, .jpeg" value="<?php echo $data_user->img_ktp; ?>">
               KTP Tersimpan :  <a href="#" onclick="tampil_ktp('<?php echo $data_user->img_ktp; ?>')">
                        <?php echo $data_user->img_ktp; ?>
                     </a>
               <p class="help-block"><strong>Catatan : Apabila tidak ingin merubah foto, Mohon lewati pilihan ini</strong></p>
            </div>
             <div class="form-group col-md-6">
               <label class="lblFotoKtp">Foto Usaha</label>
               <input type="file" id="foto_usaha" name="frm_foto_usaha" accept=".png, .jpg, .jpeg" value="<?php echo $data_user->img_vendor; ?>">
               Foto Usaha Tersimpan :  <a href="#" onclick="tampil_usaha('<?php echo $data_user->img_vendor; ?>')">
                        <?php echo $data_user->img_vendor; ?>
                     </a>
               <p class="help-block"><strong>Catatan : Apabila tidak ingin merubah foto, Mohon lewati pilihan ini</strong></p>
            </div>
            <div class="form-group col-md-12">
               <input type="submit" value="Update" id="btnUpdateProfilVendor" class="btn btn-primary"/>
               <button type="button" class="btn btn-danger" onclick="javascript:history.back()">kembali</button>
            </div>
         <?php } ?>          
         </form>
   </div>
</div><!-- /.container -->

<!-- modal_gambar_detail -->
<div class="modal fade" id="modal_tampil_foto" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="padding-bottom: 0px;">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <p class="txtJudul" style="font-size: 20px;">Foto Profil</p>
            </div>
            <div class="modal-body form">
               <div class="col-xs-s12">
                  <img id="imgGbr" src="" class="gbrDetailPenjualanModal" style="
                     display:block;
                     margin-left:auto;
                     margin-right:auto;width: 50%;
                  ">
               </div>
               <span id="pesan_file"></span>
            </div> <!-- modal body -->
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!-- modal_gambar_detail -->
<div class="modal fade" id="modal_tampil_ktp" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="padding-bottom: 0px;">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <p class="txtJudulKtp" style="font-size: 20px;">Foto KTP</p>
            </div>
            <div class="modal-body form">
               <div class="col-xs-s12">
                  <img id="imgGbrKtp" src="" class="gbrDetailPenjualanModal" style="
                     display:block;
                     margin-left:auto;
                     margin-right:auto;width: 50%;
                  ">
               </div>
               <span id="pesan_file_ktp"></span>
            </div> <!-- modal body -->
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!-- modal_gambar_detail -->
<div class="modal fade" id="modal_tampil_usaha" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="padding-bottom: 0px;">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <p class="txtJudulUsaha" style="font-size: 20px;">Foto Usaha</p>
            </div>
            <div class="modal-body form">
               <div class="col-xs-s12">
                  <img id="imgGbrUsaha" src="" class="gbrDetailPenjualanModal" style="
                     display:block;
                     margin-left:auto;
                     margin-right:auto;width: 50%;
                  ">
               </div>
               <span id="pesan_file_usaha"></span>
            </div> <!-- modal body -->
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->