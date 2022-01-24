
/* -- FUNCIONES NUEVA VENTA -- */

/* Para que deje scrollear el modal anterior!! */
$(document).on('hidden.bs.modal', '.modal', function () {
    $('.modal:visible').length && $(document.body).addClass('modal-open');
});

function getEmpleado(id) {
  $.ajax({
      url: 'ajax_getEmpleado.php',
      type: 'POST',
      data: {id : id},
      dataType: 'html'
  }).done(function(data){
    $('#modal-edit-empleado').html('');
      $('#modal-edit-empleado').html(data);
      $('#modal-edit-empleado').modal('show');
  }).fail(function(xhr, textStatus, errorThrown) {
      console.log(xhr.responseText);
  }).always(function(){
      // console.log('The ajax call ended.');
  });
}

function getEntrega(id) {
  $.ajax({
      url: 'ajax_getPago.php',
      type: 'POST',
      data: {id : id},
      dataType: 'html'
  }).done(function(data){
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

function getPagoFlete(id) {
  $.ajax({
      url: 'ajax_getPagoFlete.php',
      type: 'POST',
      data: {id : id},
      dataType: 'html'
  }).done(function(data){
      $('#modal-edit-pago').html('');
      $('#modal-edit-pago').html(data);
      $('#modal-edit-pago').modal('show');
  }).fail(function(xhr, textStatus, errorThrown) {
      console.log(xhr.responseText);
  }).always(function(){
      // console.log('The ajax call ended.');
  });
}

function eliminarEmpleado(){
  if(confirm('Desea eliminar el empleado de forma permanente?')){
    $('#eliminar_empleado').submit();
  }
}

function getDetalleCC(tipo,id){
  if(tipo == 10){
    getPagoFlete(id);
  } 
  if(tipo == 9){
    getEntrega(id);
  } 
  if(tipo == 4){
    getPago(id);
  }
  if(tipo == 5 || tipo == 6){
    getNota(id);
  }
}

function actualizarMatriz(id) {
  $.ajax({
      url: 'service_actualizarCCMatriz.php',
      type: 'POST',
      data: {id : id},
      dataType: 'json'
  }).done(function(data){
    console.log(data);
  }).fail(function(xhr, textStatus, errorThrown) {
      console.log(xhr.responseText);
  }).always(function(){
      // console.log('The ajax call ended.');
  });
}