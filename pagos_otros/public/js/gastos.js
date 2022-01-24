
/* -- FUNCIONES NUEVA VENTA -- */

/* Para que deje scrollear el modal anterior!! */
$(document).on('hidden.bs.modal', '.modal', function () {
    $('.modal:visible').length && $(document.body).addClass('modal-open');
});



function getPagosOtros(id,categoria) {
  console.log(1);
  $.ajax({
      url: 'ajax_getPagosOtros.php',
      type: 'POST',
      data: {id : id,
            categoria : categoria},
      dataType: 'html'
  }).done(function(data){
      // console.log(data);
      $('#modal-edit-gasto').html('');
      $('#modal-edit-gasto').html(data);
      $('#modal-edit-gasto').modal('show');
  }).fail(function(xhr, textStatus, errorThrown) {
      console.log(xhr.responseText);
  }).always(function(){
      // console.log('The ajax call ended.');
  });
}