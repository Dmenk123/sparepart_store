let key;
let baseUrl = "";
$(document).ready(function () {
	baseUrl = $('#base_url').text();

	//set active class to navbar
	$('#li_nav_home').removeClass('active');
	$('#li_nav_kontak').removeClass('active');
	$('#li_nav_faq').removeClass('active');
	$('#li_nav_login').removeClass('active');
	$('#li_nav_produk').addClass('active');

	//set active class to sidebar
	let i;
	key = 1;
	let segment = $('#segment').text();
	$.ajax({
		url: baseUrl + 'produk/get_kategori',
		type: "POST",
		dataType: "JSON",
		async: false,
		data: { segment: segment },
		success: function (data) {
			for (i = 1; i <= data.count_kategori; i++) {
				if (i == data.kategori[key - 1].id_kategori) {
					$('.li_submenu' + data.kategori[key - 1].id_kategori + '').addClass('active');
				}
			}
		}
	});

	//sort per page
	$('#select_show').change(function () {
		let num_show = $(this).val();
		let satu = 10;
		let dualima = 25;
		let limapuluh = 50;

		if (num_show == satu) {
			setParam('per_page', satu);
		} else if (num_show == dualima) {
			setParam('per_page', dualima);
		} else if (num_show == limapuluh) {
			setParam('per_page', limapuluh);
		}
	});

	//sort kategori tampil
	$('#select_sort').change(function () {
		let sort_type = $(this).val();
		let nama_produk = "nama_produk";
		let harga = "harga";
		let created = "created";

		if (sort_type == nama_produk) {
			setParam('sort_by', nama_produk);
		} else if (sort_type == harga) {
			setParam('sort_by', harga);
		} else if (sort_type == created) {
			setParam('sort_by', created);
		}
	});

	//selected per page
	let perPage = $('#id_show').text();
	$('#select_show option[value=' + perPage + ']').attr('selected', 'selected');

	//selected sort
	let sortBy = $('#id_sort').text();
	$('#select_sort option[value=' + sortBy + ']').attr('selected', 'selected');

	//add to cart
	$('.add_cart').click(function () {
		let idProduk = $(this).data("idproduk");
		let namaProduk = $(this).data("namaproduk");
		let hargaProduk = $(this).data("hargaproduk");
		let gambarProduk = $(this).data("gambarproduk");
		let idVendor = $(this).data("idvendor");
		let namaVendor = $(this).data("namavendor");
		let qtyProduk = $('#qty_' + idProduk).val();
		if (qtyProduk == "") {
			alert("Mohon Mengisi Qty Produk");
		}
		else {
			$.ajax({
				url: baseUrl + 'cart/add_to_cart',
				method: "POST",
				data: { idProduk: idProduk,
						namaProduk: namaProduk,
						hargaProduk: hargaProduk,
				 		qtyProduk: qtyProduk,
				 		gambarProduk: gambarProduk,
				 		idVendor:idVendor,
				 		namaVendor:namaVendor,
				},
				success: function (data) {
					window.location.href = baseUrl + 'cart';
				}
			});
		}
	});

	//add jasa
	$('.add_jasa').click(function () {
		let idProdukJasa = $(this).data("idproduk");
		let namaProdukJasa = $(this).data("namaproduk");
		let hargaProdukJasa = $(this).data("hargaproduk");
		let gambarProdukJasa = $(this).data("gambarproduk");
		let idVendorJasa = $(this).data("idvendor");
		let namaVendorJasa = $(this).data("namavendor");
		let qtyProdukJasa = $('#qty_' + idProdukJasa).val();
		let qtyDurasiJasa = $('#durasi_' + idProdukJasa).val();
		let idRekberJasa = $('#rekber_' + idProdukJasa).val();
		if (qtyProdukJasa == "") {
			alert("Mohon Mengisi Qty Produk");
		}
		else {
			$.ajax({
				url: baseUrl + 'cart/add_temp_jasa',
				dataType : 'JSON',
				method: "POST",
				data: { idProduk: idProdukJasa,
						namaProduk: namaProdukJasa,
						hargaProduk: hargaProdukJasa,
				 		qtyProduk: qtyProdukJasa,
				 		durasi: qtyDurasiJasa,
				 		gambarProduk: gambarProdukJasa,
				 		idVendor:idVendorJasa,
				 		idRekber:idRekberJasa,
				 		namaVendor:namaVendorJasa,
				},
				success: function (response) {
					if (response.status) {
						//var coba = JSON.parse(response);
						// var uri3 = coba.id;
						window.location.href = baseUrl + 'checkout/summary_jasa/' + response.id;
					}else{
						swal("Transaksi Dibatalkan", response.pesan, "error");
					}
				}
			});
		}
	});

	//event onchange selectSize
	// $('.selectSize').change(function (event) {
	$('.selectQty').empty();
	$('.selectQty').append('<option value="">Pilih Qty Produk</option>');
	let id_produk = $('.txtIdProduk').val();
	key = 1;
	$.ajax({
		url: baseUrl + 'produk/get_stok_produk',
		type: "POST",
		dataType: "JSON",
		data: { id_produk: id_produk },
		success: function (data) {
			Object.keys(data.stok).forEach(function () {
				$('.selectQty').append('<option value="' + data.stok[key - 1] + '">' + data.stok[key - 1] + '</option>');
				key++;
			});
		}
	});
});

//set uri string
function setParam(name, value) {
	let l = window.location;
	/* build params */
	let params = {};
	let x = /(?:\??)([^=&?]+)=?([^&?]*)/g;
	let s = l.search;
	for (let r = x.exec(s); r; r = x.exec(s)) {
		r[1] = decodeURIComponent(r[1]);
		if (!r[2]) r[2] = '%%';
		params[r[1]] = r[2];
	}

	/* set param */
	params[name] = encodeURIComponent(value);

	/* build search */
	let search = [];
	for (let i in params) {
		let p = encodeURIComponent(i);
		let v = params[i];
		if (v != '%%') p += '=' + v;
		search.push(p);
	}
	search = search.join('&');

	/* execute search */
	l.search = search;
}