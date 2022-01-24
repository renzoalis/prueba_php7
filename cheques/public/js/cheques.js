
/* -- FUNCIONES NUEVA VENTA -- */

/* Para que deje scrollear el modal anterior!! */
$(document).on('hidden.bs.modal', '.modal', function () {
    $('.modal:visible').length && $(document.body).addClass('modal-open');
});

function getCheque(id) {
  $.ajax({
      url: 'ajax_getCheque.php',
      type: 'POST',
      data: {id : id},
      dataType: 'html'
  }).done(function(data){
    $('#modal-edit-cheque').html('');
    $('#modal-edit-cheque').html(data);
    $('#modal-edit-cheque').modal('show');
  }).fail(function(xhr, textStatus, errorThrown) {
      console.log(xhr.responseText);
  }).always(function(){
      // console.log('The ajax call ended.');
  });
}

function getChequePropio(id) {
  $.ajax({
      url: 'ajax_getChequePropio.php',
      type: 'POST',
      data: {id : id},
      dataType: 'html'
  }).done(function(data){
    $('#modal-edit-cheque-propio').html('');
    $('#modal-edit-cheque-propio').html(data);
    $('#modal-edit-cheque-propio').modal('show');
  }).fail(function(xhr, textStatus, errorThrown) {
      console.log(xhr.responseText);
  }).always(function(){
      // console.log('The ajax call ended.');
  });
}

function actualizarEstado(i) {
 $('#estado_nuevo').val(i);
 $('#detalle_cheque_edit').submit();
}
