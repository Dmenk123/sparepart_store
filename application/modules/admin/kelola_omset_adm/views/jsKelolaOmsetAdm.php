<script type="text/javascript">
	let save_method; //for save method string
	let table;
   let baseUrl = '<?php echo base_url(); ?>';

$(document).ready(function() {
   //force integer input in textfield
   $('input.numberinput').bind('keypress', function (e) {
      return (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57) && e.which != 46) ? false : true;
   });

   //datatables  
   // tabel trans masuk
	table = $('#tableKelolaOmset').DataTable({
		
		"processing": true, 
		"serverSide": true, 
		//"order":[[ 2, 'desc' ]],
      "order":[], //initial no order 
		//load data for table content from ajax source
		"ajax": {
			"url": "<?php echo site_url('kelola_omset_adm/list_omset') ?>",
			"type": "POST" 
		},

		"columnDefs": [
			{
				"targets": [-1], //last column
				"orderable": false, //set not orderable
			},
		],
	});
  
   //change status pembelian (aktif/batal)
   $(document).on('click', '.btn_edit_status', function(){
      if(confirm('Apakah anda yakin ubah status Penjualan ini ?'))
      {
         var id = $(this).attr('id');
         var status = $(this).text();
         // ajax delete data to database
         $.ajax({
            url : "<?php echo site_url('confirm_penjualan_adm/edit_status_penjualan')?>/"+id,
            type: "POST",
            dataType: "JSON",
            data : {status : status},
            success: function(data)
            {
               alert(data.pesan);
               reload_table();
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
               alert('Error remove data');
            }
         });
      }   
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

function confirmOmset(id)
{
   swal({
      title: "Konfirmasi Kelola Omset",
      text: "Penarikan uang dari Rekber dan kelola Laba",
      icon: "warning",
      buttons: [
      'Tidak',
      'Ya'
      ],
      dangerMode: true,
   }).then(function(isConfirm) {
         if (isConfirm) {
            $("#CssLoader").removeClass('hidden');
            var form = $('#form_confirm_omset')[0];
            var data = new FormData(form);
            $.ajax({
               url: "<?php echo site_url('kelola_omset_adm/konfirmasi_omset_adm')?>",
               type: 'POST',
               dataType: "JSON",
               data : data,
               processData: false, // false, it prevent jQuery form transforming the data into a query string
               contentType: false, 
               cache: false,
               timeout: 600000,
               success: function (data) {
                  swal("Sukses", data.pesan, "success").then(function() {
                     $("#CssLoader").addClass('hidden');
                     window.location.href = baseUrl+"kelola_omset_adm";
                  });
               },
               error: function (e) {
                  swal("Error", 'Terjadi Kesalahan', "error").then(function() {
                     $("#CssLoader").addClass('hidden');
                     window.location.href = baseUrl+"kelola_omset_adm";
                  });
               }
            });
         } else {
         swal("Batal", "Aksi dibatalkan", "error");
      }
   });
}

function reload_table()
{
    table.ajax.reload(null,false); //reload datatable ajax 
}

function saveKonfirmasi()
{
   let IsValid = $("form[name='formConfirmJual']").valid();
   if(IsValid)
   {
      if($("#cfrm_check").prop('checked') == true)
      {
         // Get form
         let form = $('#form_confirm_jual')[0];
         let data = new FormData(form);
         let idBeli = $('#field_id_beli').val();
         // ajax adding data to database
         $("#CssLoader").removeClass('hidden');
         $.ajax({
            type: "POST",
            enctype: 'multipart/form-data',
            url: "<?php echo site_url('penjualan_adm/konfirmasi_penjualan/'); ?>"+idBeli,
            data: data,
            dataType: "JSON",
            processData: false, // false, it prevent jQuery form transforming the data into a query string
            contentType: false, 
            cache: false,
            timeout: 600000,
            success: function (data) {
               if (data.status) {
                  alert(data.pesan);
                  $("#CssLoader").addClass('hidden');
                  window.location.href = "<?php echo site_url('penjualan_adm'); ?>";
               }
            },
            error: function (e) {
               console.log("ERROR : ", e);
               $("#CssLoader").addClass('hidden');
            }
         });
      }
      else
      {
         alert("Mohon centang \"Saya memastikan data telah valid\"");
      }
   }
}

</script>	
