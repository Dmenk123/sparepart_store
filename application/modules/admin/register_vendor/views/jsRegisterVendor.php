<script type="text/javascript">

	$(document).ready(function() {
        var idProvinsi = '35';
        var idKota = '3578';
		//set active class to navbar
		var uriValue = "produk";
		$('#li_nav_home').removeClass('active');
		$('#li_nav_kontak').removeClass('active');
		$('#li_nav_faq').removeClass('active');
		$('#li_nav_login').removeClass('active');
		$('#li_nav_produk').removeClass('active');
        $('#li_nav_register').addClass('active');
		
        $('#regProvinsi').select2();

        //select2 kota/kabupaten with no content
        $( "#regKota" ).select2();

        //select2 kecamatan with no content
        $( "#regKecamatan" ).select2({ 
        });

        //select2 kelurahan with no content
        $( "#regKelurahan" ).select2({ 
        });
        
        $( "#regKecamatan" ).select2({ 
            ajax: {
                url: '<?php echo site_url('register/suggest_kecamatan'); ?>/'+ idKota,
                dataType: 'json',
                delay: 250,
                processResults: function (data) {
                    return {
                        results: data
                    };
                },
                cache: true
            },
        });
      

        // event onchange to modify select2 kelurahan content
        $('#regKecamatan').change(function(){
            $('#regKelurahan').empty(); 
            var idKecamatan = $('#regKecamatan').val();
            $( "#regKelurahan" ).select2({ 
                ajax: {
                    url: '<?php echo site_url('register/suggest_kelurahan'); ?>/'+ idKecamatan,
                    dataType: 'json',
                    delay: 250,
                    processResults: function (data) {
                        return {
                            results: data
                        };
                    },
                    cache: true
                },
            });
        });

        // event onchange to select kelurahan content
        $('#regKelurahan').change(function(){ 
            var idKecamatan = $('#regKecamatan').val();
            $( "#regKelurahan" ).select2({ 
                ajax: {
                    url: '<?php echo site_url('register/suggest_kelurahan'); ?>/'+ idKecamatan,
                    dataType: 'json',
                    delay: 250,
                    processResults: function (data) {
                        return {
                            results: data
                        };
                    },
                    cache: true
                },
            });
        }); 

        //datepicker
        $('#regTglLahir').datepicker({
            autoclose: true,
            format: "dd-mm-yyyy",
            todayHighlight: true,
            todayBtn: true,
            todayHighlight: true,
        });

        //force integer input in textfield
        $('input.numberinput').bind('keypress', function (e) {
            return (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57) && e.which != 46) ? false : true;
        });


        $('#regEmail').focusout(function(){
            $('#regEmail').filter(function(){
                var emil=$('#regEmail').val();
                var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
                if( !emailReg.test( emil ) ) {
                    alert('Format email anda tidak valid');
                    $('#regEmail').val("");
                } /*else {
                    alert('Thank you for your valid email');
                }*/
            })
        });

        //jquery validation
        $("form[name='formRegisterVendor']").validate({
            // Specify validation rules
            errorElement: 'div',
            rules:{
                // The key name on the left side is the name attribute
                // of an input field. Validation rules are defined
                // on the right side
                reg_nama_vendor: "required",
                reg_jenis_usaha: "required",
                reg_nama: "required",
                reg_telp: "required",
                reg_tgl_lahir: "required",
                reg_email: {
                    required: true,
                    email: true,
                },
                reg_provinsi: "required",
                reg_kota: "required",
                reg_kecamatan: "required",
                reg_kelurahan: "required",
                reg_alamat: "required",
                reg_kode_pos: {
                    required: true,
                    minlength: 5
                },
                reg_no_ktp: {
                    required: true,
                    minlength: 16
                },
                reg_foto_ktp: "required",
                reg_foto_usaha: "required",
                reg_captcha: {
                    required: true,
                    minlength: 5
                }
            },
            // Specify validation error messages
            messages: {
                reg_nama_vendor: "Nama Pelapak is required",
                reg_jenis_usaha: "Jenis Usaha is required",
                reg_nama: "Nama Pemilik is required",
                reg_telp: "Nommor telp is required",
                reg_tgl_lahir: "Tanggal Lahir is required",
                reg_email: "Valid email is required",
                reg_provinsi: "Provinsi is required",
                reg_kota: "Kota is required",
                reg_kecamatan: "Kecamatan is required",
                reg_kelurahan: "Kelurahan is required",
                reg_alamat: "Alamat is required",
                reg_kode_pos: {
                    required: "Kode pos is required",
                    minlength: "Kode pos anda setidaknya minimal 5 karakter"
                },
                reg_no_ktp: {
                    required: "Nomor KTP is required",
                    minlength: "Kode pos anda setidaknya minimal 16 karakter"
                },
                reg_foto_ktp: "Nama Pemilik is required",
                reg_foto_usaha: "Nommor telp is required",
                reg_captcha: {
                    required: "Captcha is required",
                    minlength: "Captcha anda setidaknya minimal 5 karakter"
                },
            },
            submitHandler: function(form) {
              form.submit();
            }
        });

        $('.refreshCaptcha').on('click', function(){
            $.get('<?php echo base_url().'register/refresh_captcha'; ?>', function(data){
                $('#imgCaptcha').html(data);
            });
        });

        //add register
        $("#btnRegister").click(function (event) {
            //stop submit the form
            event.preventDefault();
            var IsValid = $("form[name='formRegisterVendor']").valid();
            if(IsValid)
            {
                // Get form
                var form = $('#form_register_vendor')[0];
                // Create an FormData object
                var data = new FormData(form);
                // add an extra field for the FormData
                // data.append("CustomField", "This is some extra data, testing");
                // disabled the register button
                $("#btnRegister").prop("disabled", true);
                $.ajax({
                    type: "POST",
                    enctype: 'multipart/form-data',
                    url: "<?php echo site_url('register_vendor/add_register_vendor'); ?>",
                    data: data,
                    dataType: "JSON",
                    processData: false, // false, it prevent jQuery form transforming the data into a query string
                    contentType: false, 
                    cache: false,
                    timeout: 600000,
                    success: function (data) {
                        if (data.status) {
                            alert(data.pesan);
                            $("#btnRegister").prop("disabled", false);
                            window.location.href = "<?php echo site_url('home'); ?>";
                        }
                    },
                    error: function (e) {
                        console.log("ERROR : ", e);
                        $("#btnRegister").prop("disabled", false);
                    }
                });
            }
        });
	

	}); // end jquery  

	//set uri string
	function setParam(name, value) {
        var l = window.location;

        /* build params */
        var params = {};        
        var x = /(?:\??)([^=&?]+)=?([^&?]*)/g;        
        var s = l.search;
        for(var r = x.exec(s); r; r = x.exec(s))
        {
            r[1] = decodeURIComponent(r[1]);
            if (!r[2]) r[2] = '%%';
            params[r[1]] = r[2];
        }

        /* set param */
        params[name] = encodeURIComponent(value);

        /* build search */
        var search = [];
        for(var i in params)
        {
            var p = encodeURIComponent(i);
            var v = params[i];
            if (v != '%%') p += '=' + v;
            search.push(p);
        }
        search = search.join('&');

        /* execute search */
        l.search = search;
    }

    function login_proc2() {
            $.ajax({
                url: "<?php echo site_url('login/login_proc'); ?>",
                type: 'POST',
                dataType: "JSON",
                // data: {email: email, password: password},
                data: $('#form_login2').serialize(),
                success :function(data){
                    if (data.level == "2") 
                    {
                        alert(data.pesan);
                        window.location.href = "<?php echo site_url('home'); ?>";
                    }
                    else
                    {
                        alert(data.pesan);
                        window.location.href = "<?php echo site_url('dashboard_adm'); ?>";
                    }
                }, 
                error: function (jqXHR, textStatus, errorThrown)
                {
                    alert('password / username anda tidak cocok');
                }
            });
    }


		
</script>