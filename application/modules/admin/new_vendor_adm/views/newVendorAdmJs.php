<script type="text/javascript">
	var save_method; //for save method string
	var table;

$(document).ready(function() {

    //force integer input in textfield
    $('input.numberinput').bind('keypress', function (e) {
        return (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57) && e.which != 46) ? false : true;
    });

	//datatables
	table = $('#tabelUser').DataTable({
		
		"processing": true, //feature control the processing indicator
		"serverSide": true, //feature control DataTables server-side processing mode
		"order":[], //initial no order

		//load data for table content from ajax source
		"ajax": {
			"url": "<?php echo site_url('new_vendor_adm/list_new_vendor') ?>",
			"type": "POST" 
		},

		//set column definition initialisation properties
		"columnDefs": [
			{
				"targets": [-1], //last column
				"orderable": false, //set not orderable
			},
		],
	});

    // select class modal whenever bs.modal hidden
    // $(".modal").on("hidden.bs.modal", function(){
    //     $('#form_user')[0].reset(); // reset form on modals
    //     $('[name="userProvinsi"]').empty(); 
    //     $('[name="userKota"]').empty();
    //     $('[name="userKecamatan"]').empty();
    //     $('[name="userKelurahan"]').empty();
    //     $("#user_level option[value='']").attr("selected", true);
    //     $("[name='formUser']").validate().resetForm();
    // });
});	

function add_user()
{
    save_method = 'add';
	$('#modal_user_form').modal('show'); //show bootstrap modal
	$('.modal-title').text('Add User'); //set title modal
}

function detail_vendor(id)
{
    $.ajax({
        url : "<?php echo site_url('new_vendor_adm/get_detail_vendor')?>/" + id,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {
            var baseUrl = $('#base_url').text();
            var alamat = data.alamat_user+' Kelurahan '+data.nama_kelurahan+' Kecamatan '+data.nama_kecamatan+' '+data.nama_kota+' Provinsi '+data.nama_provinsi;
            
            var img_ktp = "<a target='_blank' rel='noopener noreferrer' href='"+baseUrl+"assets/img/foto_ktp/"+data.img_ktp+"'>"+data.img_ktp+"</a>"
            var img_toko = "<a target='_blank' rel='noopener noreferrer' href='"+baseUrl+"assets/img/foto_usaha/"+data.img_vendor+"'>"+data.img_vendor+"</a>"
            //ambil data ke json->modal
            $('#det_nama').text(data.id_user);
            $('#det_email').text(data.email);
            $('#det_alamat').text(alamat);
            $('#det_tgl_lahir').text(data.tgl_lahir_user);
            $('#det_telp').text(data.no_telp_user);
            $('#det_nama_vendor').text(data.nama_vendor);
            $('#det_jenis_usaha').text(data.jenis_usaha_vendor);
            $('#det_ktp').text(data.ktp_pemilik);
            $('#det_img_ktp').html(img_ktp);
            $('#det_img_toko').html(img_toko);
            $('#modal_detail').modal('show');
            $('.modal-title').text('Detail Calon Pelapak');

        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error get data from ajax');
        }
    });
}

//confirm vendor
function confirm_vendor(idUser){
    if(confirm('Konfirmasi vendor ini ?. Vendor akan dikirim email berupa password akun.'))
    {
        $("#CssLoader").removeClass('hidden');
        $.ajax({
            url : "<?php echo site_url('new_vendor_adm/konfirmasi_vendor')?>",
            type: "POST",
            dataType: "JSON",
            data : {idUser : idUser},
            success: function(data)
            {
                $("#CssLoader").addClass('hidden');
                alert(data.pesan);
                reload_table();
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                $("#CssLoader").addClass('hidden');
                alert('Error remove data');
            }
        });
    }   
}


function reload_table()
{
    table.ajax.reload(null,false); //reload datatable ajax 
}
</script>	