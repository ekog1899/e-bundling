$(document).ready(function(){

	// proses submit, create konsep
	form = $('#frmCreateDraft');
	$('.editor #submit').click(function(){
		save_editor();
        // A4
        if ($('#editorArea').height() > 800) {
            alert('Sepertinya surat anda melebih 1 halaman, anda juga menyisipkan PAGE BREAK sesuai keinginan');
        }
        console.log($('#editorArea').height());
		return false;
        // max height A4 print 860, screen
	});

	save_editor = function(){
		v_id_draft_revisi = $('input[name=id_draft_revisi]').val();
		v_nomor_surat = $('.editor #nomor_surat').html();
		v_lampiran = $('.editor #lampiran').html();
		v_perihal = $('.editor #perihal').html();
		v_kepada = $('.editor #kepada').html();
        v_sifat = $('.editor #sifat').html();
		v_isi = $('.editor #editor1').html();
		v_ttd = $('.editor #ttd').html();
        v_tembusan = $('.editor #tembusan').html();
        v_tgl = $('.editor #tgl').html();
		if ( v_id_draft_revisi != '' )
			$.ajax({
			  type: "POST",
			  url: BASE_URL + 'draft/save_rev',
			  data: {
					id_draft_revisi:v_id_draft_revisi,
					kepada:v_kepada,
					perihal:v_perihal,
					nomor_surat:v_nomor_surat,
					lampiran:v_lampiran,
					isi:v_isi,
					ttd:v_ttd,
                    sifat:v_sifat,
                    tgl:v_tgl,
                    tembusan:v_tembusan
					},
			  success: function( response ) {
				alert('Perubahan Data tersimpan');
			  }
			});
		else{
			console.log("id_draft_revisi " + v_id_draft_revisi);
		}
	}
    /*
    $('button#send').click(function(){
		save_editor();
		v_id_draft_revisi = $('input[name=id_draft_revisi]').val();
		if ( v_id_draft_revisi != '' )
			$.ajax({
			  type: "POST",
			  url: BASE_URL + 'draft/send/korektor',
			  data: {
					id_draft_revisi:v_id_draft_revisi
					},
			  success: function( response ) {
				console.log(response);exit;
                  if (response.status == 'OK' ){
					$('#customModal .modal-body').html(response.msg);
                    $('#customModal').modal();
					$('.btnArea').html(response.msg);
                   // location.reload();
				}
			  }
			});
		else{
			//console.log("id_draft_revisi " + v_id_draft_revisi);
		}
	});
    */

    $('button#show-catatan').click(function(){
      $('.form-catatan').toggle();
    });


    process_send = function(tujuan){
        v_id_draft_revisi = $('input[name=id_draft_revisi]').val();
		v_catatan = $('#editor-catatan').html();
        if ( v_id_draft_revisi != '' )
			$.ajax({
			  type: "POST",
			  url: BASE_URL +'draft/send/'+tujuan,
			  data: {
					id_draft_revisi:v_id_draft_revisi,
                    catatan:v_catatan
					},
			  success: function( response ) {
				if (response.status == 'OK' ){
				    $('#customModal .modal-body').html(response.msg);
                    $('#customModal').modal();
                    location.reload();
				}
			  }
			});
		else{
			console.log("id_draft_revisi " + v_id_draft_revisi);
		}
    }

    $('button#send-konseptor').click(function(){
		process_send('konseptor');
	});

    $('button#send-korektor').click(function(){
		process_send('korektor');
	});

    $('button#send-pejabat').click(function(){
		process_send('pejabat');
	});

     $('button#send-valid').click(function(){
		process_send('valid');
	});

    $('button#perbaiki').click(function(){
		$('#perbaikiModal').modal('hide');
        v_id_draft_revisi = $('input[name=id_draft_revisi]').val();
		v_catatan = $('#editor-catatan').html();
        if ( v_id_draft_revisi != '' )
			$.ajax({
			  type: "POST",
			  url: BASE_URL +'draft/create_new_ver',
			  data: {
					id_draft_revisi:v_id_draft_revisi
					},
			  success: function( response ) {
				if (response.msg){
                    $('#customModal .modal-body').html(response.msg);
                    $('#customModal').modal();
				//	alert('OK');
				//	$('.btnArea').html(response.msg);
				}
			  }
			});
		else{
		//	console.log("id_draft_revisi " + v_id_draft_revisi);
		}
	});

    var koreksi = "";
    var startOffset = 0;  // where the range starts
    var endOffset =0;      // where the range ends
    var saveNode = "";
    var nodeData = "";                     // the actual selected text
    var nodeHTML =  "";   // parent element innerHTML
    var nodeTagName =  "";

$('.ko-true #editorArea').mouseup(function() {
    // show modal notes;
     sel = window.getSelection();
        //sel.removeAllRanges();
        range= sel.getRangeAt(0);
         koreksi = sel.toString();
         startOffset = range.startOffset;  // where the range starts
         endOffset = range.endOffset;      // where the range ends
         saveNode = range.startContainer;
         nodeData = saveNode.data;                       // the actual selected text
         nodeHTML = saveNode.parentElement.innerHTML;    // parent element innerHTML
         nodeTagName = saveNode.parentElement.tagName;   // parent element tag name


    if ( sel.toString() != '')
    {
        $('#catatanModal').modal();
        $('#catatanModal #editor-catatan').val('');
        $('#catatanModal #editor-catatan').focus();

        $('span.select-text').html(sel.toString());


    }


});

$('#btn-add-note').on('click',function(){
           v_id_draft_revisi = $('input[name=id_draft_revisi]').val();
           v_catatan = $('#editor-catatan').val();

           if ( v_id_draft_revisi != '' && koreksi != '' && v_catatan !='' )
                 $.ajax({
                  type: "POST",
                  url: BASE_URL +'draft/add_notes',
                  data: {
                        id_draft_revisi:v_id_draft_revisi,
                        catatan:v_catatan,
                        startOffset:startOffset,
                        endOffset:endOffset,
                        nodeData:nodeData,
                        nodeHTML:nodeHTML,
                        nodeTagName:nodeTagName,
                        koreksi:koreksi
                        },
                  success: function( response ) {
                    if (response.msg){
                        $('#catatanModal').modal('hide');
                   //     $('#customModal .modal-body').html(response.msg);
                    //    $('#customModal').modal();
                        load_catatan();
                    }
                  }
                });

        });

   del_note = function(id){
        $.ajax({
			  type: "GET",
              url: BASE_URL +'draft/del_note/'+id,
			  success: function( response ) {
              load_catatan();
            }
    });

    load_catatan();
   }

    load_catatan = function() {
    let sel = window.getSelection();
    sel.removeAllRanges();

         v_id_draft_revisi = $('input[name=id_draft_revisi]').val();
        $.ajax({
			  type: "POST",
              url: BASE_URL +'draft/list_notes/'+v_id_draft_revisi,
			  success: function( response ) {
				if (response){
                    $('table#tableHistoryNotes tbody').empty();
                    if (response.notes.length == 0)
                        {
                             $('#send-konseptor').hide();
                             $('.btn-send').show();
                           } else{
                            $('.btn-send').hide();
                            $('#send-konseptor').show();
                        }
                    $.each(response.notes, function(k, v) {
                      var new_range = buildRange(v.startOffset, v.endOffset, v.nodeData, v.nodeHTML, v.nodeTagName);
                     // console.log(v.startOffset);
                      sel.addRange(new_range);
                         link_del = '<a href="javascript:del_note('+v.id+')" class="btn btn-danger btn-sm" title="Hapus Data"><i class="icon-trash icon-white">  </i> Del</a>';
                        html_row = '<tr><td>'+v.koreksi+'</td><td>'+v.catatan+'</td><td>'+link_del+'</td></tr>';
                        $('table#tableHistoryNotes tbody').append(html_row);

                    });

                }
			  }
			});
    }

    load_catatan();

buildRange = function (startOffset, endOffset, nodeData, nodeHTML, nodeTagName){
    var cDoc = document;
    var tagList = cDoc.getElementsByTagName(nodeTagName);
   // console.log(cDoc);
    // find the parent element with the same innerHTML
    for (var i = 0; i < tagList.length; i++) {
        if (tagList[i].innerHTML == nodeHTML) {
            var foundEle = tagList[i];
        }
    }

    // find the node within the element by comparing node data
    var nodeList = foundEle.childNodes;
    for (var i = 0; i < nodeList.length; i++) {
        if (nodeList[i].data == nodeData) {
            var foundNode = nodeList[i];
        }
    }

    // create the range
    var range = cDoc.createRange();

    range.setStart(foundNode, startOffset);
    range.setEnd(foundNode, endOffset);
    return range;
}




});
