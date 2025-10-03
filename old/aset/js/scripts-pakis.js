$(document).ready(function(){
	//Date range picker

	$('.datepicker').datepicker({ format: 'dd-mm-yyyy',autoHide:true });
	
	$('.file_edoc').change(function(){
			// manipulasi DOM
			form_data = new FormData();
			var perkara_id = $('input[name=perkara_id]').val();
			var file_data = $(this).prop("files")[0]; 
			var td_response = $(this).parent().parent().find('td.status');
			td_response.html('Sedang mengupload File ...');
			form_data.append('file_edoc', file_data);
			form_data.append('perkara_id', perkara_id);
			form_data.append('jenis_edoc', $(this).attr('name'));
			$.ajax({
				url: BASE_URL + 'edoc/do_upload', // point to server-side PHP script 
				dataType: 'text',  // what to expect back from the PHP script, if anything
				cache: false,
				contentType: false,
				processData: false,
				data: form_data,                         
				type: 'post',
				success: function(response){
					td_response.html(response);
				},
				error: function(response){
					alert('gagal');			
					//location.reload();
				}
			 });
		
			});


			$('.file_edoc_sidang').change(function(){
				// manipulasi DOM
				form_data = new FormData();
				var perkara_id = $('input[name=perkara_id]').val();
				var file_data = $(this).prop("files")[0]; 
				var td_response = $(this).parent().find('span.status');
				var tanggal_sidang = $(this).parent().parent().find('td.row_tgl_sidang').html();
				td_response.html('<span>Sedang mengupload File ...</span>');
				form_data.append('file_edoc', file_data);
				form_data.append('perkara_id', perkara_id);
				form_data.append('jenis_edoc', $(this).attr('name'));
				form_data.append('tanggal_sidang', tanggal_sidang);
				$.ajax({
					url: BASE_URL + 'edoc/do_upload_sidang', // point to server-side PHP script 
					dataType: 'text',  // what to expect back from the PHP script, if anything
					cache: false,
					contentType: false,
					processData: false,
					data: form_data,                         
					type: 'post',
					success: function(response){
						td_response.html(response);
					},
					error: function(response){
						alert('gagal');			
						//location.reload();
					}
				 });
			
				});		

	// save text info 	
	
	$('.save_text').click(function(){
		// manipulasi DOM
		form_data = new FormData();
		var perkara_id = $('input[name=perkara_id]').val();
		var value = $(this).parent().parent().find('input[name=value]').val();
		var td_response = $(this).parent();
		var jenis_edoc = $(this).parent().parent().find('input[name=jenis_edoc]').val();
		td_response.html('Sedang mengupload File ...');
		form_data.append('value', value);
		form_data.append('perkara_id', perkara_id);
		form_data.append('jenis_edoc', jenis_edoc);
		$.ajax({
			url: BASE_URL + 'edoc/save_text', // point to server-side PHP script 
			dataType: 'text',  // what to expect back from the PHP script, if anything
			cache: false,
			contentType: false,
			processData: false,
			data: form_data,                         
			type: 'post',
			success: function(response){
				td_response.html(response);
			},
			error: function(response){
				alert('gagal');			
				//location.reload();
			}
		 });
	
		});
	

		// toogle validasi select box
		$('select[name=select_validasi').on('change', function() { 
			if ( this.value == 1 )
			{
				// disable catatan
				$(this).parent().parent().find('input.catatan_validasi').attr('readonly',true);
			}
			else{
				$(this).parent().parent().find('input.catatan_validasi').attr('readonly',false);
			}
		});

		// toogle validasi select box
		$('select[name=select_validasi_sidang').on('change', function() { 
			if ( this.value == 1 )
			{
				// disable catatan
				$(this).parent().find('input.catatan_validasi').attr('readonly',true);
			}
			else{
				$(this).parent().find('input.catatan_validasi').attr('readonly',false);
			}
		});
		
		// save catatan validasi
		// klo ada tanggal sidang = validasi edoc sidang
		$('a.btn-validasi').click(function(){
			form_data = new FormData();
			var this_a = $(this);
			var id_edoc = $(this).parent().parent().find('input[name=id_edoc]').val();
			var jenis_edoc = $(this).parent().parent().find('input[name=jenis_edoc]').val();
			var perkara_id = $('input[name=perkara_id').val();
			var satkercode = $('input[name=satkercode').val();
			var select_validasi = $(this).parent().parent().find('select[name=select_validasi]').val();
			var catatan_validasi = $(this).parent().parent().find('input[name=catatan_validasi]').val();
			
			form_data.append('id_edoc', id_edoc);
			form_data.append('perkara_id', perkara_id);
			form_data.append('jenis_edoc', jenis_edoc);
			form_data.append('satkercode', satkercode);
			form_data.append('select_validasi', select_validasi);
			form_data.append('catatan_validasi', catatan_validasi);
			// kalau tidak valid tapi gak isi catatan, tolak
			if ( select_validasi == 0 && catatan_validasi == '' ) {
				alert('Mohon isi Catatan Perbaikan terlebih dahulu');
				return false;
			}

			if ( select_validasi == '' ) {
				alert('Pilih Pilih hasil Validasi');
				return false;
			}

			
			//  

			$.ajax({
				url: BASE_URL + 'edoc/do_validasi', // point to server-side PHP script 
				dataType: 'text',  // what to expect back from the PHP script, if anything
				cache: false,
				contentType: false,
				processData: false,
				data: form_data,                         
				type: 'post',
				success: function(response){
					this_a.parent().html(response);
				},
				error: function(response){
					alert('gagal');			
					//location.reload();
				}
			 });
		})




		$('a.btn-validasi-sidang').click(function(){
			form_data = new FormData();
			var this_a = $(this);
			var id_edoc = $(this).parent().find('input[name=id_edoc]').val();
			var jenis_edoc = $(this).parent().find('input[name=jenis_edoc]').val();
			var perkara_id = $('input[name=perkara_id').val();
			var satkercode = $('input[name=satkercode').val();
			var select_validasi = $(this).parent().find('select[name=select_validasi_sidang]').val();
			var catatan_validasi = $(this).parent().find('input[name=catatan_validasi]').val();
			var tgl_sidang = $(this).parent().find('input[name=tgl_sidang]').val();
			
		//	form_data.append('id_edoc', id_edoc);
			form_data.append('perkara_id', perkara_id);
			form_data.append('jenis_edoc', jenis_edoc);
			form_data.append('satkercode', satkercode);
			form_data.append('select_validasi', select_validasi);
			form_data.append('catatan_validasi', catatan_validasi);
			form_data.append('tgl_sidang', tgl_sidang);
			// kalau tidak valid tapi gak isi catatan, tolak
			if ( select_validasi == 1 && catatan_validasi != '' ) {
				alert('Maaf Catatan Harus diisi');
				return false;
			}

			if ( select_validasi == '' ) {
				alert('Pilih Pilih hasil Validasi');
				return false;
			}

			
			//  

			$.ajax({
				url: BASE_URL + 'edoc/do_validasi_sidang', // 
				dataType: 'text',  // 
				cache: false,
				contentType: false,
				processData: false,
				data: form_data,                         
				type: 'post',
				success: function(response){
					this_a.parent().html(response);
				},
				error: function(response){
					alert('gagal');			
					//location.reload();
				}
			 });
		})
			
	// open modal edoc
	  popup_edoc = function(src) {
		  $("#edocViewerModal").modal();
		 // src = "";
		  $('#edocViewerModal iframe').attr('src', BASE_URL+'/uploads/edoc/'+src);
		  }	


		$('select#switch_tahun').on('change', function(){
			tahun = $(this).val();
			location.href = $(this).parent().attr('action') + '/' + tahun;
		});
		
		
	  $('.dataTable').DataTable({ 
		destroy: true,
		ordering: false
	 });


	 var current_satkercode = '';
	 var current_perkara_id_enc = '';

	 // load jadwal sidang
	 load_sidang_sidang = function(satkercode,perkara_id_enc){
		current_satkercode = satkercode;
		current_perkara_id_enc = perkara_id_enc;
		url = BASE_URL + 'ajax/get_jsidang/'+satkercode+'/'+perkara_id_enc;
		$('#tab_jsidang').load(url);
	 }



	 save_jadwal_sidang = function(){
		enc_id = $("#tundaSidangModal #enc_id").val();
		var enc_perkara_id = $("#tundaSidangModal #enc_perkara_id").val();
		var satkercode = $("#tundaSidangModal #satkercode").val();
		tanggal = $("#tundaSidangModal #tanggal").val();
		agenda = $("#tundaSidangModal #agenda").val();
		alasan_tunda = $("#tundaSidangModal #alasan_tunda").val();

		form_data = new FormData();
		form_data.append('enc_id', enc_id);
		form_data.append('enc_perkara_id', enc_perkara_id);
		form_data.append('satkercode', satkercode);
		form_data.append('tanggal', tanggal);
		form_data.append('agenda', agenda);
		form_data.append('alasan_tunda', alasan_tunda);

		$.ajax({
			url: BASE_URL + 'ajax/save_jsidang', // point to server-side PHP script 
			dataType: 'text',  // what to expect back from the PHP script, if anything
			cache: false,
			contentType: false,
			processData: false,
			data: form_data,                         
			type: 'post',
			success: function(response){
				alert('Berhasil disimpan');
				close_modal('tundaSidangModal');
				
			},
			error: function(response){
				alert('gagal');			
				//location.reload();
			}
		 });

		 load_sidang_sidang(current_satkercode,current_perkara_id_enc);


	 }

	 popup_tunda = function(enc_id,enc_perkara_id,satkercode,tanggal,agenda,alasan_tunda){
		$("#tundaSidangModal").modal();
		$("#tundaSidangModal #enc_id").val(enc_id);
		$("#tundaSidangModal #enc_perkara_id").val(enc_perkara_id);
		$("#tundaSidangModal #satkercode").val(satkercode);
		$("#tundaSidangModal #tanggal").val(tanggal);
		$("#tundaSidangModal #agenda").val(agenda);
		$("#tundaSidangModal #alasan_tunda").val(alasan_tunda);

	 }

	 popup_unlock = function(enc_perkara_id,satkercode,jenis_edoc){
		$("#unlockModal").modal();
		$("#unlockModal #enc_perkara_id").val(enc_perkara_id);
		$("#unlockModal #satkercode").val(satkercode);
		$("#unlockModal #jenis_edoc").val(jenis_edoc);

		$('#unlockModal .error-msg').html("");
	 }



	save_req_unlock = function(){
		var enc_perkara_id = $("#unlockModal #enc_perkara_id").val();
		var satkercode = $("#unlockModal #satkercode").val();
		jenis_edoc = $("#unlockModal #jenis_edoc").val();
		req_alasan = $("#unlockModal #req_alasan").val();
		$('#unlockModal .error-msg').html('');	
		if (req_alasan == '') {
			$('#unlockModal .error-msg').html('Mohon uraikan alasan');
			exit;	
		}
		form_data = new FormData();
		form_data.append('enc_perkara_id', enc_perkara_id);
		form_data.append('satkercode', satkercode);
		form_data.append('jenis_edoc', jenis_edoc);
		form_data.append('req_alasan', req_alasan);

		$.ajax({
			url: BASE_URL + 'ajax/save_req_unlock', // point to server-side PHP script 
			dataType: 'text',  // what to expect back from the PHP script, if anything
			cache: false,
			contentType: false,
			processData: false,
			data: form_data,                         
			type: 'post',
			success: function(response){
				$('#unlockModal .error-msg').html(response);				
			},
			error: function(error){
				console.log(error);
				$('#unlockModal .error-msg').html(error.responseText);
				//location.reload();
			}
		 });


	}

	unlock_upload = function(id,perkara_id){
		$.ajax({
			url: BASE_URL + 'ajax/process_unlock', // point to server-side PHP script 
			dataType: 'text',  // what to expect back from the PHP script, if anything
			cache: false,
			contentType: false,
			data: {"id":id,"perkara_id":perkara_id},                         
			type: 'post',
			success: function(response){
				$('#unlockModal .error-msg').html(response);				
			},
			error: function(error){
				console.log(error);
				$('#unlockModal .error-msg').html(error.responseText);
				//location.reload();
			}
		 });

		 $.post( BASE_URL + 'ajax/process_unlock', {"id":id,"perkara_id":perkara_id})
		 .done(function( data ) {
		   $('.col-action-'+id).html(data);
		 }); 
	}



	 
	del_tunda = function(id){
		var confirmText = "Anda Yakin Akan Menghapus tundaan ini";
    	if(confirm(confirmText)) {
			$.ajax({
				url: BASE_URL + 'ajax/del_jsidang', // point to server-side PHP script 
				cache: false,
				data: {id:id},                         
				type: 'post',
				success: function(response){
					close_modal('tundaSidangModal');
				},
				error: function(response){
					alert('gagal menghapus');			
					//location.reload();
				}
			 });
		}
		
		load_sidang_sidang(current_satkercode,current_perkara_id_enc);
	 }

	 close_modal=function(id){
		$('#'+id).modal('hide');
	 }

	

})

