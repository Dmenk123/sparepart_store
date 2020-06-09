<script type="text/javascript">
	var save_method; //for save method string
$(document).ready(function() {

	//set input/textarea/select event when change value, remove class error and remove text help block
	$("input").change(function() {
		$(this).parent().parent().removeClass('has-error');
		$(this).next().empty();
	});

    //datatable
    // $('#laporanOmsetVendor').DataTable({
    //     "pageLength": 25
    // });

    //datepicker
    $('.tanggal').datepicker({
        autoclose: true,
        format: "dd-mm-yyyy",
        todayHighlight: true,
        todayBtn: true,
        todayHighlight: true,
    });
});	

function reload_table()
{
    table.ajax.reload(null,false); //reload datatable ajax 
}

</script>	