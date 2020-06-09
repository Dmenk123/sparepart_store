<script type="text/javascript">
	let save_method; //for save method string
	let table;
   let baseUrl = '<?php echo base_url(); ?>';

$(document).ready(function() {
   //force integer input in textfield
   $('input.numberinput').bind('keypress', function (e) {
      return (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57) && e.which != 46) ? false : true;
   });

   $(document).on('click', '.btn_remove', function(){
      let button_id = $(this).attr('id');
      $('#row'+button_id+'').remove();
   });

   // select class modal apabila bs.modal hidden
   $("#modal_confirm_jual").on("hidden.bs.modal", function(){
      $('#form_confirm_jual')[0].reset();
      //clear tr append in modal
      $('tr').remove('.tbl_modal_row');
      $('option').remove('.appendOpt');
   });

   //datatables  
	table = $('#tableConfirmJual').DataTable({
		
		"processing": true, 
		"serverSide": true, 
		//"order":[[ 2, 'desc' ]],
      "order":[], //initial no order 
		//load data for table content from ajax source
		"ajax": {
			"url": "<?php echo site_url('monitoring_sewa/list_sewa') ?>",
			"type": "POST" 
		},

		"columnDefs": [
			{
				"targets": [-1], //last column
				"orderable": false, //set not orderable
			},
		],
	});

   //modal gambar transfer detail
   $('.modalGbrTransfer').click(function() {
      let nmGbr = $(this).data("id");
      $('#imgGbrDetail').attr('src', baseUrl+'assets/img/bukti_transfer/'+nmGbr);
      $('.txtJudul').text('Gambar Bukti Transfer');
      $('#modal_gambar_detail').modal('show');
   });
  
   function editConfirmAdm(id)
   {
      swal({
         title: "Konfirmasi Transaksi Final",
         text: "Apakah Anda Yakin ingin Konfirmasi!",
         icon: "warning",
         buttons: [
         'Tidak',
         'Ya'
         ],
         dangerMode: true,
      }).then(function(isConfirm) {
            if (isConfirm) {
               $("#CssLoader").removeClass('hidden');
               $.ajax({
                  url: "<?php echo site_url('penjualan_adm/konfirmasi_penjualan_adm')?>",
                  type: 'POST',
                  dataType: "JSON",
                  data : {id : id},
                  success: function (data) {
                     swal("Sukses", data.pesan, "success").then(function() {
                        $("#CssLoader").addClass('hidden');
                        window.location.href = baseUrl+"penjualan_adm";
                     });
                  },
                  error: function (e) {
                     swal("Error", 'Terjadi Kesalahan', "error").then(function() {
                        $("#CssLoader").addClass('hidden');
                        window.location.href = baseUrl+"penjualan_adm";
                     });
                  }
               });
            } else {
            swal("Batal", "Aksi dibatalkan", "error");
         }
      });
   }

   //change status penyewaan (aktif)
   $(document).on('click', '.btn_edit_status', function(){
      var id = $(this).attr('id');
      swal({
         title: "Tandai Sebagai Peminjaman Selesai ?",
         text: "Perhatian, Ketika sudah ditandai maka tidak dapat dibatalkan",
         icon: "warning",
         buttons: [
            'Tidak',
            'Ya'
         ],
         dangerMode: true,
      }).then(function(isConfirm) {
         if (isConfirm) {
            $("#CssLoader").removeClass('hidden');
            $.ajax({
               url: "<?php echo site_url('monitoring_sewa/konfirmasi_sewa_selesai')?>",
               type: 'POST',
               dataType: "JSON",
               data : {id : id},
               success: function (data) {
                  swal("Sukses", data.pesan, "success").then(function() {
                     $("#CssLoader").addClass('hidden');
                     reload_table();
                  });
               },
               error: function (e) {
                  swal("Error", 'Terjadi Kesalahan', "error").then(function() {
                     $("#CssLoader").addClass('hidden');
                     reload_table();
                  });
               }
            });
         } else {
            swal("Batal", "Aksi dibatalkan", "error");
         }
      }); 
   });

//end jquery
});	

function randString(angka) 
{
   let text = "";
   let possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";

   for (let i = 0; i < angka; i++)
      text += possible.charAt(Math.floor(Math.random() * possible.length));

   return text;
}

function reload_table()
{
    table.ajax.reload(null,false); //reload datatable ajax 
}
</script>	
