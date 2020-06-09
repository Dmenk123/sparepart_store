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
			"url": "<?php echo site_url('kat_penjualan_adm/list_kat') ?>",
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

    //validasi form update profil
    $("[name='form_add_kat']").validate({
        // Specify validation rules
        errorElement: 'div',
        /*errorLabelContainer: '.errMsg',*/
        errorPlacement: function(error, element) {
            if (element.attr("name") == "kat") {
                error.insertAfter(".lblKatErr");
            } else {
                error.insertAfter(element);
            }
        },
        rules:{
            kat: "required",
        },
        // Specify validation error messages
        messages: {
            kat: "Harus memilih KATEGORI",
        },
        submitHandler: function(form) {
            form.submit();
        }
    });

    $(".modal").on("hidden.bs.modal", function(){
        $('.apdSel').remove();
    });	

});

function add_kategori()
{
    var sel = "";
    $.ajax({
        url : "<?php echo site_url('kat_penjualan_adm/get_vendor')?>",
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {
            $.each(data, function (i, item) {
                $('#kat').append($('<option>', { 
                    value: item.id_kategori,
                    text : item.nama_kategori,
                    class: "apdSel"
                }));
            });
                        
            save_method = 'add';
            $('#modal_kat').modal('show'); 
            $('.modal-title').text('Add Kategori'); 
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
        url = "<?php echo site_url('kat_penjualan_adm/add_kat ')?>";
    }

    var IsValid = $("form[name='form_add_kat']").valid();
    if(IsValid) {
        // Get form
        var form = $('#form_add_kat')[0];
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
                        $('#modal_kat').modal('hide');
                        reload_table();
                    }else{
                        alert(data.pesan);
                        $("#CssLoader").addClass('hidden');
                        $('#modal_kat').modal('hide');
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