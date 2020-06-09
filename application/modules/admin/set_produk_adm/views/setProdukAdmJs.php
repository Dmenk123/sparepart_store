<script type="text/javascript">
	var save_method;
	var table;

$(document).ready(function() {
    
    //force integer input in textfield
    $('input.numberinput').bind('keypress', function (e) {
        return (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57) && e.which != 46) ? false : true;
    });

	//datatables
    <?php if ($this->input->get('id_vendor') != '' && $this->input->get('id_kategori') != '' ) { ?>
    var idVendor    = $('#id_vendor').val();
    var idKategori  = $('#id_kategori').val();
    table = $('#tabel_produk').DataTable({
		
		"processing": true, //feature control the processing indicator
		"serverSide": true, //feature control DataTables server-side processing mode
		"order":[], //initial no order

		//load data for table content from ajax source
		"ajax": {
			"url" : "<?php echo site_url('set_produk_adm/list_set_produk') ?>",
            "data": {idVendor:idVendor, idKategori:idKategori},
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
    <?php }?>
	

    //validasi form master produk
    $("[name='formProduk']").validate({
        // Specify validation rules
        errorElement: 'span',
        /*errorLabelContainer: '.errMsg',*/
        errorPlacement: function(error, element) {
            if (element.attr("name") == "namaProduk") {
                error.insertAfter(".lblNamaErr");
            } else if (element.attr("name") == "subKategoriProduk") {
                error.insertAfter(".lblSubKetegoriErr");
            } else if (element.attr("name") == "hargaProduk") {
                error.insertAfter(".lblHargaErr");
            } else if (element.attr("name") == "satuanProduk") {
                error.insertAfter(".lblSatuanErr");
            } else if (element.attr("name") == "berat") {
                error.insertAfter(".lblBeratErr");
            } else if (element.attr("name") == "keteranganProduk") {
                error.insertAfter(".lblKeteranganErr");
            }else if (element.attr("name") == "stokAwal") {
                error.insertAfter(".lblStokAwalErr");
            }else if (element.attr("name") == "stokMin") {
                error.insertAfter(".lblStokMinErr");
            } else if (element.attr("name") == "varianProduk") {
                error.insertAfter(".lblVarianErr");
            } else {
                error.insertAfter(element);
            }
        },
            rules:{
                namaProduk: "required",
                subKategoriProduk: "required",
                hargaProduk: "required",
                satuanProduk: "required",
                berat: "required",
                keteranganProduk: "required",
                stokAwal: "required",
                stokMin: "required",
                varianProduk: "required"
            },
            // Specify validation error messages
            messages: {
                namaProduk: " (Harus diisi !!)",
                subKategoriProduk: " (Harus diisi !!)",
                hargaProduk: " (Harus diisi !!)",
                satuanProduk: " (Harus diisi !!)",
                berat: " (Harus diisi !!)",
                keteranganProduk: " (Harus diisi !!)",
                stokAwal: " (Harus diisi !!)",
                stokMin: " (Harus diisi !!)",
                varianProduk: " (Harus diisi !!)"
            },
            submitHandler: function(form) {
              form.submit();
            }
    });

     //validasi form detail produk
    $("[name='formProdukDetail']").validate({
        // Specify validation rules
        errorElement: 'span',
        /*errorLabelContainer: '.errMsg',*/
        errorPlacement: function(error, element) {
            if (element.attr("name") == "ukuranProdukDet") {
                error.insertAfter(".lblUkuranDetErr");
            } else if (element.attr("name") == "beratSatuanDet") {
                error.insertAfter(".lblBeratDetErr");
            } else if (element.attr("name") == "stokAwalDet") {
                error.insertAfter(".lblStokAwalDetErr");
            } else if (element.attr("name") == "stokMinDet") {
                error.insertAfter(".lblStokMinDetErr");
            } else {
                error.insertAfter(element);
            }
        },
            rules:{
                ukuranProdukDet: "required",
                beratSatuanDet: "required",
                stokAwalDet: "required",
                stokMinDet: "required"
            },
            // Specify validation error messages
            messages: {
                ukuranProdukDet: " (Harus diisi !!)",
                beratSatuanDet: " (Harus diisi !!)",
                stokAwalDet: " (Harus diisi !!)",
                stokMinDet: " (Harus diisi !!)"
            },
            submitHandler: function(form) {
              form.submit();
            }
    });

    //fix to issue select2 on modal when opening in firefox, thanks to github
    //$.fn.modal.Constructor.prototype.enforceFocus = function() {};

    // inisialisai select2
    $("#sub_kategori_produk").select2({
        ajax: {
            url: '<?php echo site_url('set_produk_adm/get_master_sub_kategori'); ?>/'+ idKategori,
            dataType: 'json',
            type: "GET",
            data: function (params) {
                var queryParameters = {
                    term: params.term
                }
                return queryParameters;
            },
            processResults: function (data) {
                return {
                    results: $.map(data, function (item) {
                        return {
                            text: item.text,
                            id: item.id
                        }
                    })
                };
            },
            cache: true
        }, 
    });

    $("#satuan_produk").select2({
        ajax: {
            url: '<?php echo site_url('set_produk_adm/get_satuan'); ?>',
            dataType: 'json',
            type: "GET",
            data: function (params) {
                var queryParameters = {
                    term: params.term
                }
                return queryParameters;
            },
            processResults: function (data) {
                return {
                    results: $.map(data, function (item) {
                        return {
                            text: item.text,
                            id: item.id
                        }
                    })
                };
            },
            cache: true
        }, 
    });

    //change user status
    $(document).on('click', '.btn_edit_status', function(){
        if(confirm('Apakah anda yakin ubah status Produk ini ?'))
        {
            var id = $(this).attr('id');
            var status = $(this).attr('data-id');
            $("#CssLoader").removeClass('hidden');
            // ajax delete data to database
            $.ajax({
                url : "<?php echo site_url('set_produk_adm/edit_status_produk')?>",
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

    $(".modal").on("hidden.bs.modal", function(){
        $('#form_produk')[0].reset(); // reset form on modals
        $('[name="kategoriProduk"]').empty(); 
        $('[name="subKategoriProduk"]').empty();
        $('[name="satuanProduk"]').empty();
        $('[name="idGbrDet1"]').val("");
        $('[name="idGbrDet2"]').val("");
        $("[name='formProduk']").validate().resetForm();
        $('#note_modal').addClass('hidden');
        $('#txt_wajib_det1').removeClass('hidden');
    });	

});

function add_produk()
{
    save_method = 'add';
    var idvendor = "<?php echo $this->input->get('id_vendor');?>";
    var idkategori = "<?php echo $this->input->get('id_kategori');?>";
    $("[name='idVendor']").val(idvendor);
    $("[name='idKategori']").val(idkategori);
    $('#note_modal').addClass('hidden');
    $('.txtGbrProduk').text("");
    $('.txtGbrDet1').text("");
    $('.txtGbrDet2').text("");
    $('#modal_produk').modal('show'); 
    $('.modal-title').text('Tambah Produk');     
}

function edit_produk(id)
{
    save_method = 'update';
    $('#modal_produk').modal('show'); 
    $('.modal-title').text('Edit Produk'); 
    
    $.ajax({
        url : "<?php echo site_url('set_produk_adm/edit_produk')?>/" + id,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {
            //remove class hidden pada div span catatan
            $('#note_modal').removeClass('hidden');
            //ambil data ke json->modal
            $('[name="idProduk"]').val(data.id_produk);
            $('[name="idVendor"]').val(data.id_vendor);
            $('[name="idKategori"]').val(data.id_kategori);
            $('[name="namaProduk"]').val(data.nama_produk);
            $('[name="hargaProduk"]').val(parseInt(data.harga));
            $('[name="idStok"]').val(data.id_stok);
            $('[name="idVendorProduk"]').val(data.id_vendor_produk);
            $('[name="stokAwal"]').val(parseInt(data.stok_awal));
            $('[name="stokMin"]').val(parseInt(data.stok_minimum));
            $('[name="berat"]').val(parseInt(data.berat_satuan));
            $('[name="keteranganProduk"]').val(data.keterangan_produk);
            $('[name="varianProduk"]').val(data.varian_produk);
            $('[name="idGbrDisplay"]').val(data.id_gambar);
            $('[name="idGbrDet1"]').val(data.id_gambar_detail1);
            $('[name="idGbrDet2"]').val(data.id_gambar_detail2);

            var selectedSubKategori = $("<option></option>").val(data.id_sub_kategori).text(data.nama_sub_kategori);
            var selectedSatuan = $("<option></option>").val(data.id_satuan).text(data.nama_satuan);            
            
            //tanpa trigger event
            $('[name="subKategoriProduk"]').append(selectedSubKategori);
            $('[name="satuanProduk"]').append(selectedSatuan);
            //set value of span
            $('.txtGbrProduk').text(data.nama_gambar);
            $('.txtGbrDet1').text(data.nama_gambar_detail1);
            $('.txtGbrDet2').text(data.nama_gambar_detail2);
            $('#txt_wajib_det1').addClass('hidden', true);
            $('#modal_produk_form').modal('show');
            $('.modal-title').text('Edit Produk');
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
        url = "<?php echo site_url('set_produk_adm/add_produk')?>";
    }else{
        url = "<?php echo site_url('set_produk_adm/update_produk')?>";
    }

    var IsValid = $("form[name='formProduk']").valid();
    if(IsValid) {
        // Get form
        var form = $('#form_produk')[0];
        // Create an FormData object
        var data = new FormData(form);
        $("#CssLoader").removeClass('hidden');
        $.ajax({
            type: "POST",
            enctype: 'multipart/form-data',
            url: url,
            data: data,
            dataType: "JSON",
            processData: false,
            contentType: false, 
            cache: false,
            timeout: 600000,
            success: function (data) {
                if (data.status == true) {
                    $("#CssLoader").addClass('hidden');
                    alert(data.pesan);
                    $('#modal_produk').modal('hide');
                    reload_table();
                }else if(data.status == 'gbrDetailKosong'){
                    $("#CssLoader").addClass('hidden');
                    alert(data.pesan);
                }else{
                    $("#CssLoader").addClass('hidden');
                    alert(data.pesan);
                    $('#modal_produk').modal('hide');
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