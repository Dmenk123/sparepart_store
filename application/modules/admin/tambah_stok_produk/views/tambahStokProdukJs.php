<script type="text/javascript">
	var save_method;
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
			"url": "<?php echo site_url('tambah_stok_produk/list_data') ?>",
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

    //validasi form 
    $("[name='form_add']").validate({
        // Specify validation rules
        errorElement: 'div',
        /*errorLabelContainer: '.errMsg',*/
        errorPlacement: function(error, element) {
            if (element.attr("name") == "tambah") {
                error.insertAfter(".lblStokErr");
            } else {
                error.insertAfter(element);
            }
        },
        rules:{
            tambah: "required",
        },
        // Specify validation error messages
        messages: {
            tambah: "Harus Di isi",
        },
        submitHandler: function(form) {
            form.submit();
        }
    });

    $(".modal").on("hidden.bs.modal", function(){
        $('#form_add')[0].reset();
    });	

});

function tambah_stok(id_stok)
{
    var sel = "";
    $.ajax({
        url : "<?php echo site_url('tambah_stok_produk/get_data/')?>"+id_stok,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {                  
            save_method = 'add';
            $('#i_nama').val(data.nama_produk);
            $('#i_id_stok').val(data.id_stok);
            $('#i_id_produk').val(data.id_produk);
            $('#i_sisa').val(data.stok_sisa);
            $('#i_awal').val(data.stok_awal);
            $('#modal_tambah_stok').modal('show'); 
            $('.modal-title').text('Tambah Stok Produk');
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error get data from ajax');
        }
    });
}

function save()
{
    var url;
    if(save_method == 'add') {
        url = "<?php echo site_url('tambah_stok_produk/add_data')?>";
    }

    var IsValid = $("form[name='form_add']").valid();
    if(IsValid) {
        // Get form
        var form = $('#form_add')[0];
        // Create an FormData object
        var data = new FormData(form);
        // add an extra field for the FormData
        // data.append("CustomField", "This is some extra data, testing");
        // show loader
        $("#CssLoader").removeClass('hidden');
        $.ajax({
                type: "POST",
                enctype: 'multipart/form-data',
                url: url,
                data: data,
                dataType: "JSON",
                processData: false, // false, it prevent jQuery form transforming the data into a query string
                contentType: false, 
                cache: false,
                timeout: 600000,
                success: function (data) {
                    if (data.status) {
                        $("#CssLoader").addClass('hidden');
                        alert(data.pesan);
                        $('#modal_tambah_stok').modal('hide');
                        reload_table();
                    }else{
                        alert(data.pesan);
                        $("#CssLoader").addClass('hidden');
                        $('#modal_tambah_stok').modal('hide');
                        reload_table();
                    }
                },
                error: function (e) {
                    console.log("ERROR : ", e);
                    $("#btnSave").prop("disabled", false);
                }
        });
    } 
}

function reload_table()
{
    table.ajax.reload(null,false); //reload datatable ajax 
}
</script>	