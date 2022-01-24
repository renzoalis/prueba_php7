
/* -- FUNCIONES NUEVA VENTA -- */

/* Para que deje scrollear el modal anterior!! */
$(document).on('hidden.bs.modal', '.modal', function () {
    $('.modal:visible').length && $(document.body).addClass('modal-open');
});


function getCliente(id,premium) {
  $.ajax({
      url: 'ajax_getCliente.php',
      type: 'POST',
      data: {id : id,
              premium: premium},
      dataType: 'html'
  }).done(function(data){
      $('#modal-edit-cliente').html('');
      $('#modal-edit-cliente').html(data);
      $('#modal-edit-cliente').modal('show');
  }).fail(function(xhr, textStatus, errorThrown) {
      console.log(xhr.responseText);
  }).always(function(){
      // console.log('The ajax call ended.');
  });
}

function eliminarCliente(){
  if(confirm('Desea eliminar el cliente de forma permanente?')){
    $('#eliminar_cliente').submit();
  }

}

function getVenta(id,tipo) {
  $.ajax({
      url: 'ajax_getVenta.php',
      type: 'POST',
      data: {id : id , tipo:tipo},
      dataType: 'html'
  }).done(function(data){
    //console.log(data);
      $('#modal-edit-venta').html('');
      $('#modal-edit-venta').html(data);
      $('#modal-edit-venta').modal('show');
  }).fail(function(xhr, textStatus, errorThrown) {
      console.log(xhr.responseText);
  }).always(function(){
      // console.log('The ajax call ended.');
  });
}

function getCobro(id) {
  $.ajax({
      url: 'ajax_getCobro.php',
      type: 'POST',
      data: {id : id},
      dataType: 'html'
  }).done(function(data){
    console.log(data);
      $('#modal-edit-cobro').html('');
      $('#modal-edit-cobro').html(data);
      $('#modal-edit-cobro').modal('show');
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
// si es tipo venta o venta anulada
  if(tipo == 8 | tipo == 1 ){
    getVenta(id,tipo);
  } 
  if(tipo == 3){
    getCobro(id);
  }
  if(tipo == 5 || tipo == 6){
    getNota(id);
  }
}

function getVentaDespachada(id) {
  $.ajax({
      url: 'ajax_getVentaDespachada.php',
      type: 'POST',
      data: {id : id},
      dataType: 'html'
  }).done(function(data){
    //console.log(data);
    $('#modal-edit-venta').html('');
      $('#modal-edit-venta').html(data);
      $('#modal-edit-venta').modal('show');
      
  }).fail(function(xhr, textStatus, errorThrown) {
      console.log(xhr.responseText);
  }).always(function(){
      // console.log('The ajax call ended.');
  });
}