<!-- modal add_user -->
<div class="modal fade" id="modal_satuan_form" role="dialog" aria-labelledby="add_user" aria-hidden="true">
   <div class="modal-dialog modal-lg">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title"></h4>
         </div>
         <div class="modal-body">
            <form id="form_user" name="formUser">
               <div class="form-row">
                  <input type="hidden" name="satId">
                  <div class="form-group col-md-12">
                     <label for="lblsat" class="lblSatErr">Nama Satuan</label>
                     <input type="text" class="form-control" name="sat" placeholder="Nama Satuan">
                  </div>
               </div> 
            </form>
         </div>
         <div class="modal-footer">
            <div class="form-group col-md-12">
               <button type="button" id="btnSave" onclick="save()" class="btn btn-primary">Save</button>
               <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
            </div>
         </div>
      </div>
   </div>
<div>