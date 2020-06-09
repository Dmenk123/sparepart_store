<!-- modal detail -->
<div class="modal fade" id="modal_kat" role="dialog" aria-labelledby="modal_kat" aria-hidden="true">
   <div class="modal-dialog modal-md">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title"></h4>
         </div>
         <div class="modal-body">
            <form id="form_add_kat" name="form_add_kat">
               <div class="form-row">
                  <div class="form-group col-md-12">
                     <label for="lblKatErr" class="lblKatErr">Nama Kategori</label>
                     <select class="form-control" id="kat" name="kat" style="width: 100%;" required>
                        <option value="">-- Pilih Kategori --</option>
                     </select>
                  </div> 
               </div>
            </form>         
         </div>
         <div class="modal-footer">
            <br><br><br>
            <button type="button" id="btnSave" onclick="save()" class="btn btn-success">Simpan</button>
            <button type="button" class="btn btn-primary" data-dismiss="modal">Kembali</button>
         </div>
      </div>
   </div>
<div>
