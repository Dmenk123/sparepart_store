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
			"url": "<?php echo site_url('master_satuan_adm/list_satuan') ?>",
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
    
    //validasi form master produk
    $("[name='formUser']").validate({
        // Specify validation rules
        errorElement: 'div',
        /*errorLabelContainer: '.errMsg',*/
        errorPlacement: function(error, element) {
            if (element.attr("name") == "sat") {
                error.insertAfter(".lblSatErr");
            } else {
                error.insertAfter(element);
            }
        },
            rules:{
                sat: "required"
            },
            // Specify validation error messages
            messages: {
                sat: " (Harus diisi !!)",
            },
            submitHandler: function(form) {
              form.submit();
            }
    });

    // select class modal whenever bs.modal hidden
    $(".modal").on("hidden.bs.modal", function(){
        $('#form_user')[0].reset();
        $("[name='formUser']").validate().resetForm();
    });

    //change status
    $(document).on('click', '.btn_edit_status', function(){
        if(confirm('Apakah anda yakin ubah status satuan ini ?'))
        {
            var id = $(this).attr('id');
            var status = $(this).attr('data-id');
            $("#CssLoader").removeClass('hidden');
            // ajax delete data to database
            $.ajax({
                url : "<?php echo site_url('master_satuan_adm/edit_status_satuan')?>",
                type: "POST",
                dataType: "JSON",
                data : {id:id, status:status},
                success: function(data)
                {
                    if (data.status) {
                        $("#CssLoader").addClass('hidden');
                        alert(data.pesan);
                        reload_table();   
                    }
                },
                error: function (jqXHR, textStatus, errorThrown)
                {
                    alert('Error remove data');
                }
            });
        }   
    });

});	

function add_satuan()
{
    save_method = 'add';
	$('#modal_satuan_form').modal('show'); //show bootstrap modal
	$('.modal-title').text('Add Satuan'); //set title modal
}

function edit_satuan(id)
{
    save_method = 'update';
    $.ajax({
        url : "<?php echo site_url('master_satuan_adm/edit_data_satuan')?>/" + id,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {
            //ambil data ke json->modal
            $('[name="satId"]').val(data.id_satuan);
            $('[name="sat"]').val(data.nama_satuan);
            $('#modal_satuan_form').modal('show'); // show bootstrap modal when complete loaded
            $('.modal-title').text('Edit Satuan'); // Set title to Bootstrap modal title
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error get data from ajax');
        }
    });
}

function reload_table()
{
    table.ajax.reload(null,false); //reload datatable ajax 
}

function save()
{
    var url;
    if(save_method == 'add') {
        url = "<?php echo site_url('master_satuan_adm/add_data_satuan')?>";
    } else {
        url = "<?php echo site_url('master_satuan_adm/update_data_satuan')?>";
    }

    // ajax adding data to database
    var IsValid = $("form[name='formUser']").valid();
    if(IsValid)
    {
        // Get form
        var form = $('#form_user')[0];
        // Create an FormData object
        var data = new FormData(form);
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
                        $('#modal_satuan_form').modal('hide');
                        reload_table();
                    }
                },
                error: function (e) {
                    $("#CssLoader").addClass('hidden');
                    console.log("ERROR : ", e);
                    alert('Terjadi Kesalahan');
                }
        });
    } 
}

function reload_table()
{
    table.ajax.reload(null,false); //reload datatable ajax
}
</script>	