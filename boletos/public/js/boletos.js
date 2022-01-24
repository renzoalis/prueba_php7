
/* -- Solicitudes SAE MLP -- */

function getBoleto(id){
	$.ajax({
      url: 'ajax_getBoleto.php',
      type: 'POST',
      data: {id : id},
      dataType: 'html'
  }).done(function(data){
      $('#modal-edit-boleto').html('');
      $('#modal-edit-boleto').html(data);
      $('#modal-edit-boleto').modal('show');
  }).fail(function(xhr, textStatus, errorThrown) {
      console.log(xhr.responseText);
  }).always(function(){
      // console.log('The ajax call ended.');
  });
}

function cobrarBoleto() {
  $('#input_multa').attr('readonly',false);
  $('#input_multa').css('background-color','#f8ffbb');

  $('#input_interes').attr('readonly',false);
  $('#input_interes').css('background-color','#f8ffbb');
}


