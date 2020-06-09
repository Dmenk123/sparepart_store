<div class="container">
   <div class="col-md-12">
      <ul class="breadcrumb">
         <li><a href="#">Home</a>
         </li>
         <li><?php echo $this->uri->segment(1); ?></li>
      </ul>
   </div><!-- /.col -->

   <div class="col-md-12">
      <div class="box">
         <h1>Register untuk Vendor/Pelapak</h1>
         <p class="lead">Anda apakah saat ini belum memiliki akun ?</p>
         <p>Silahkan daftar akun baru untuk dapat bekerja sama dengan kami sebagai vendor/pelapak, anda akan tergabung dengan komunitas kami dengan anggota yang tersebar di seluruh Indonesia !</p>
         <p class="text-muted">Jika terdapat masalah atau pertanyaan, mohon hubungi ke <a href="<?php echo site_url('kontak'); ?>">kontak kami</a>, Kami dengan senang hati siap membantu anda.</p>
         <hr>
         <form id="form_register_vendor" method="POST" name="formRegisterVendor" enctype="multipart/form-data">   
            <div class="form-group">
               <label for="Nama Vendor">Nama Vendor/Pelapak*</label>
               <input type="text" class="form-control" id="regNamaVendor" name="reg_nama_vendor">
            </div>  
            <div class="form-group">
               <label for="Jenis Usaha">Jenis Usaha*</label>
               <input type="text" class="form-control" id="regJenisUsaha" name="reg_jenis_usaha" placeholder="misal : jual pakan hewan, jual kandang, jasa titip hewan, dll">
            </div>   
            <div class="form-group">
               <label for="Nama Depan">Nama Depan*</label>
               <input type="text" class="form-control" id="regNama" name="reg_nama">
            </div>
            <div class="form-group">
               <label for="Nama Belakang">Nama Belakang</label>
               <input type="text" class="form-control" id="regNamaBlkg" name="reg_nama_blkg">
            </div>
            <div class="form-group">
               <label for="Tanggal Lahir">Tanggal Lahir Pemilik</label>
               <input type="text" class="form-control" id="regTglLahir" name="reg_tgl_lahir">
            </div>
            <div class="form-group">
               <label for="Nomor telepon">Nomor Telp*</label>
               <input type="text" class="form-control numberinput" id="regTelp" name="reg_telp" placeholder="contoh : 081234567890">
            </div>
            <div class="form-group">
               <label for="Email">Email*</label>
               <input type="email" class="form-control" id="regEmail" name="reg_email" placeholder="anda@domain.com">
            </div>
            <div class="form-group">
               <label for="Provinsi tempat usaha">Provinsi Tempat Usaha*</label>
               <select class="form-control" id="regProvinsi" name="reg_provinsi" style="width: 100%;" value="35">
                  <option value="35">JAWA TIMUR</option>
               </select>
            </div>
            <div class="form-group">
               <label for="Alamat tempat usaha">Kota/Kabupaten Tempat Usaha*</label>
               <select class="form-control" id="regKota" name="reg_kota" style="width: 100%;">
                  <option value="3578">SURABAYA</option>
               </select>
            </div>
            <div class="form-group">
               <label for="Kecamatan tempat usaha">Kecamatan Tempat Usaha*</label>
               <select class="form-control" id="regKecamatan" name="reg_kecamatan" style="width: 100%;"></select>
            </div>
            <div class="form-group">
               <label for="Kelurahan tempat usaha">Kelurahan/Desa Tempat Usaha*</label>
               <select class="form-control" id="regKelurahan" name="reg_kelurahan" style="width: 100%;"></select>
            </div>
            <div class="form-group">
               <label for="Alamat tempat usaha">Alamat Tempat Usaha*</label>
               <textarea class="form-control" id="regALamat" name="reg_alamat"></textarea>
            </div>
            <div class="form-group">
               <label for="Kode Pos">Kode Pos*</label>
               <input type="text" class="form-control" id="regKodePos" name="reg_kode_pos">
            </div>
            <div class="form-group">
               <label for="Nomor KTP">Nomor KTP*</label>
               <input type="text" class="form-control" id="regNoKtp" name="reg_no_ktp">
            </div>
            <div class="form-group">
               <label for="Foto KTP">Foto KTP</label>
               <input type="file" id="regFotoKtp" name="reg_foto_ktp" accept=".png, .jpg, .jpeg">
            </div>
            <div class="form-group">
               <label for="Foto Usaha">Foto Usaha</label>
               <input type="file" id="regFotoUsaha" name="reg_foto_usaha" accept=".png, .jpg, .jpeg">
            </div>
            <div class="form-group">
               <label for="Captcha">Captcha* (Case sensitive - Besar kecil huruf berpengaruh)</label>
               <p>
                  <span id="imgCaptcha"><?php echo $img; ?></span> 
                  <a href="javascript:void(0);" class="refreshCaptcha">[Kode Tidak Jelas]</a>
               </p>
               <input type="text" class="form-control" id="regCaptcha" name="reg_captcha">
            </div>
            <div class="form-group">
               <label for="Wajib Diisi"><strong>Keterangan : (*) Wajib diisi.</strong></label>
            </div>
            <div class="text-center">
               <input type="submit" value="Register" id="btnRegister" class="btn btn-primary"/>
            </div>
         </form>
         
      </div><!-- /.box -->
   </div><!-- /.col -->
</div><!-- /.container -->