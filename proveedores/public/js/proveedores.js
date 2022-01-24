
/* -- FUNCIONES NUEVA VENTA -- */

/* Para que deje scrollear el modal anterior!! */
$(document).on('hidden.bs.modal', '.modal', function () {
    $('.modal:visible').length && $(document.body).addClass('modal-open');
});

function getProveedor(id,premium) {
  $.ajax({
      url: 'ajax_getProveedor.php',
      type: 'POST',
      data: {id : id,
            premium : premium},
      dataType: 'html'
  }).done(function(data){
      $('#modal-edit-proveedor').html('');
      $('#modal-edit-proveedor').html(data);
      $('#modal-edit-proveedor').modal('show');
  }).fail(function(xhr, textStatus, errorThrown) {
      console.log(xhr.responseText);
  }).always(function(){
      // console.log('The ajax call ended.');
  });
}

function eliminarProveedor(){
  if(confirm('Desea eliminar el proveedor de forma permanente?')){
    $('#eliminar_proveedor').submit();
  }
}
// A partir de aca para la vista de la CC nueva
function getCompra(id) {
  $.ajax({
      url: 'ajax_getCompra.php',
      type: 'POST',
      data: {id : id},
      dataType: 'html'
  }).done(function(data){
    //console.log(data);
      $('#modal-edit-compra').html('');
      $('#modal-edit-compra').html(data);
      $('#modal-edit-compra').modal('show');
  }).fail(function(xhr, textStatus, errorThrown) {
      console.log(xhr.responseText);
  }).always(function(){
      // console.log('The ajax call ended.');
  });
}

function getPago(id) {
  $.ajax({
      url: 'ajax_getPago.php',
      type: 'POST',
      data: {id : id},
      dataType: 'html'
  }).done(function(data){
    console.log(data);
      $('#modal-edit-pago').html('');
      $('#modal-edit-pago').html(data);
      $('#modal-edit-pago').modal('show');
  }).fail(function(xhr, textStatus, errorThrown) {
      console.log(xhr.responseText);
  }).always(function(){
      // console.log('The ajax call ended.');
  });
}

function getNota(id) {
  $.ajax({
      url: 'ajax_getNota.php',
      type: 'POST',
      data: {id : id},
      dataType: 'html'
  }).done(function(data){
    console.log(data);
      $('#modal-edit-nota').html('');
      $('#modal-edit-nota').html(data);
      $('#modal-edit-nota').modal('show');
  }).fail(function(xhr, textStatus, errorThrown) {
      console.log(xhr.responseText);
  }).always(function(){
      // console.log('The ajax call ended.');
  });
}

function getDetalleCC(tipo,id){
  // compra de mercaderia
  if(tipo == 15){
    getCompra(id);
  } 
  if(tipo == 4){
    getPago(id);
  }
  if(tipo == 5 || tipo == 6){
    getNota(id);
  }

}