<!-- modal detail -->
<div class="modal fade" id="modal_tambah_stok" role="dialog" aria-labelledby="modal_tambah_stok" aria-hidden="true">
   <div class="modal-dialog modal-md">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title"></h4>
         </div>
         <div class="modal-body">
            <form id="form_add" name="form_add">
               <div class="form-row">
                  <div class="form-group col-md-12">
                     <label>Nama Produk</label>
                     <input type="text" name="nama" id="i_nama" readonly class="form-control">
                     <input type="hidden" name="id_stok" id="i_id_stok" class="form-control">
                     <input type="hidden" name="id_produk" id="i_id_produk" class="form-control">
                  </div>
                  <div class="form-group col-md-12">
                     <label>Stok Sisa</label>
                     <input type="text" name="sisa" id="i_sisa" readonly class="form-control numberinput">
                     <input type="hidden" name="awal" id="i_awal" class="form-control">
                  </div> 
                  <div class="form-group col-md-12">
                     <label class="lblStokErr">Stok Ditambahkan</label>
                     <input type="text" name="tambah" id="i_tambah" class="form-control numberinput">
                  </div> 
               </div>
            </form>         
         </div>
         <div class="modal-footer">
            <div class="form-group col-md-12">
               <button type="button" id="btnSave" onclick="save()" class="btn btn-success">Simpan</button>
               <button type="button" class="btn btn-danger" data-dismiss="modal">Kembali</button>
            </div>   
         </div>
      </div>
   </div>
<div>
