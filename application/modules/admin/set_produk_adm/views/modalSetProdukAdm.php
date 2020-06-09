<!-- modal detail -->
<div class="modal fade" id="modal_produk" role="dialog" aria-labelledby="modal_produk" aria-hidden="true">
   <div class="modal-dialog modal-lg">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title"></h4>
         </div>
         <div class="modal-body">
            <form id="form_produk" name="formProduk">
               <div class="form-row">
                  <input type="hidden" name="idProduk">
                  <input type="hidden" name="idVendor" id="kategori_vendor">
                  <input type="hidden" name="idKategori" id="kategori_produk">
                  <input type="hidden" name="idStok" id="id_stok">
                  <input type="hidden" name="idVendorProduk" id="id_vendor_produk">
                  <div class="form-group col-md-12">
                     <label for="lblName" class="lblNamaErr">Nama Produk</label>
                     <input type="text" class="form-control" name="namaProduk" placeholder="Nama Produk">
                  </div>
               </div>                  
               <div class="form-group">
                  <div class="form-group col-md-12">
                     <label for="lblSubKategori" class="lblSubKetegoriErr">Sub Kategori</label>
                     <select class="form-control" id="sub_kategori_produk" name="subKategoriProduk" style="width: 100%;"></select>
                  </div>
               </div>
               <div class="form-row">
                  <div class="form-group col-md-6">
                     <label for="lblHarga" class="lblHargaErr">Harga Jual Produk</label>
                     <input type="text" class="form-control numberinput" name="hargaProduk" placeholder="Langsung Tulis Angka ex : 50000">
                  </div>
                  <div class="form-group col-md-6">
                     <label for="lblStokAwal" class="lblStokAwalErr">Stok Awal</label>
                     <input type="text" class="form-control numberinput" id="stok_awal" name="stokAwal" placeholder="Stok Awal">
                  </div>
               </div>
               <div class="form-row">
                  <div class="form-group col-md-6">
                     <label for="lblStokMin" class="lblStokMinErr">Stok Minimum</label>
                     <input type="text" class="form-control numberinput" id="stok_min" name="stokMin" placeholder="Stok Minimum">
                  </div>
                  <div class="form-group col-md-6">
                     <label for="lblSatuan" class="lblSatuanErr">Satuan</label>
                     <select class="form-control" id="satuan_produk" name="satuanProduk" style="width: 100%;"></select>
                  </div>
               </div>
               <div class="form-row">
                  <div class="form-group col-md-12">
                     <label for="LblBerat" class="lblBeratErr">Berat Satuan (Gram)</label>
                     <input type="text" class="form-control numberinput" id="berat" name="berat" placeholder="Berat Satuan / Gram">
                  </div>
               </div> 
               <div class="form-row">
                  <div class="form-group col-md-12">
                     <label for="LblKeterangan" class="lblKeteranganErr">Keterangan Produk</label>
                     <textarea class="form-control" id="keterangan_produk" name="keteranganProduk" rows="2"></textarea>
                  </div>
               </div>                  
               <div class="form-group col-md-12">
                  <label for="lblVarian" class="lblVarianErr">Varian</label>
                  <input type="text" class="form-control" name="varianProduk" placeholder="Varian Produk">
               </div>
               <div class="form-group col-md-12">
                  <label for="lblGambarDisplay" class="lblGbrDisplayErr">Gambar Display Produk</label>
                  <input type="file" id="gambar_display" name="gambarDisplay" accept=".png, .jpg, .jpeg">
                  <input type="hidden" value="" name="idGbrDisplay">
                  <span style="font-style: italic;" class="txtGbrProduk"></span>
               </div>
               <div class="form-group col-md-12">
                  <label>Gambar Detail Produk <strong>(Minimal upload 1 Gambar)</strong></label>
                  <br>
                  <label>
                     <span id="txt_wajib_det1" style="color: red;font-weight: bold">Wajib Diisi !!</span>
                     <input type="file" id="gambar_detail1" name="gambarDetail1" accept=".png, .jpg, .jpeg">
                     <input type="hidden" value="" name="idGbrDet1">
                  </label>
                  <span style="font-weight: normal; font-style: italic;" class="txtGbrDet1"></span>
                  <br>
                  <label>
                     <span style="color: blue;">Gambar detail tambahan (bila Ada)</span>
                     <input type="file" id="gambar_detail2" name="gambarDetail2" accept=".png, .jpg, .jpeg">
                     <input type="hidden" value="" name="idGbrDet2">
                  </label>
                  <span style="font-weight: normal; font-style: italic;" class="txtGbrDet2"></span>
                  
                  <br>
               </div>
            </form>       
         </div>
         <div class="modal-footer">
            <br><br><br>
            <div class="form-group col-md-12">
               <button type="button" id="btnSave" onclick="save()" class="btn btn-success">Simpan</button>
               <button type="button" class="btn btn-danger" data-dismiss="modal">Kembali</button>
         </div>
      </div>
   </div>
<div>
