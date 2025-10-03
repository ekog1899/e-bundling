$(document).ready(function(){
   
   
    // onchange pa pengaju
	$('select[name=satker]').on('change', function() {
		$('#np_satsing').html('/' + $(this).val());
	});



    telusur = function(){
        $('.msg_satker').hide();
        $('#resultArea').html('');
		satkersing = $("select[name=satker]").val();
		np_nomor = $("input[name=np_nomor]").val();
		np_pg = $("select[name=np_pg]").val();
        np_tahun = $("select[name=np_tahun]").val();
        if ( satkersing == '' ){
            $('.msg_satker').show();
            return;
        }

        if ( np_nomor == '' ){
            $("input[name=np_nomor]").addClass('bg-warning');
            return;
        }
		form_data = new FormData();
		form_data.append('satkersing', satkersing);
		form_data.append('np_nomor', np_nomor);
		form_data.append('np_pg', np_pg);
		form_data.append('np_tahun', np_tahun);
		$('#loading').css('display','block');
		$.ajax({
			url: BASE_URL + 'ajax/telusur', // point to server-side PHP script 
			dataType: 'html',  // what to expect back from the PHP script, if anything
			cache: false,
			contentType: false,
			processData: false,
			data: form_data,                         
			type: 'post',
			success: function(response){
				$('#resultArea').html(response);
                $('#loading').css('display','none');
				
			},
			error: function(response){
				alert('gagal');			
				$('#loading').css('display','none');
			}
		 });

         
    }     

    $('.btn-telusur').on('click',function(){
        telusur();
    });

})