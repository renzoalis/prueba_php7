
/* -- Solicitudes -- */

/* Para que deje scrollear el modal anterior!! */
$(document).on('hidden.bs.modal', '.modal', function () {
    $('.modal:visible').length && $(document.body).addClass('modal-open');
});

function getProducto(id,premium){
	$.ajax({
      url: 'ajax_getProducto.php',
      type: 'POST',
      data: {id : id,
              premium: premium},
      dataType: 'html'
  }).done(function(data){
      $('#modal-edit-producto').html('');
      $('#modal-edit-producto').html(data);
      $('#modal-edit-producto').modal('show');
  }).fail(function(xhr, textStatus, errorThrown) {
      console.log(xhr.responseText);
  }).always(function(){
      // console.log('The ajax call ended.');
  });
}

function getTipo(id,premium) {
  $.ajax({
      url: 'ajax_getTipo.php',
      type: 'POST',
      data: {id : id,
              premium: premium},
      dataType: 'html'
  }).done(function(data){
      $('#modal-edit-tipo').html('');
      $('#modal-edit-tipo').html(data);
      $('#modal-edit-tipo').modal('show');
  }).fail(function(xhr, textStatus, errorThrown) {
      console.log(xhr.responseText);
  }).always(function(){
      // console.log('The ajax call ended.');
  });
}

function getCategoria(id,premium) {
  $.ajax({
      url: 'ajax_getCategoria.php',
      type: 'POST',
      data: {id : id,
              premium: premium},
      dataType: 'html'
  }).done(function(data){
      $('#modal-edit-categoria').html('');
      $('#modal-edit-categoria').html(data);
      $('#modal-edit-categoria').modal('show');
  }).fail(function(xhr, textStatus, errorThrown) {
      console.log(xhr.responseText);
  }).always(function(){
      // console.log('The ajax call ended.');
  });
}

$('#input_categoria').keypress(function() {
  if(event.keyCode == 13){
    validarCategoriaAdd();
    return false;
  }
});

function getPS(id) {
  $.ajax({
      url: 'ajax_getPs.php',
      type: 'POST',
      data: {id : id},
      dataType: 'html'
  }).done(function(data){
      $('#modal-edit-lote').html('');
      $('#modal-edit-lote').html(data);
      $('#modal-edit-lote').modal('show');
  }).fail(function(xhr, textStatus, errorThrown) {
      console.log(xhr.responseText);
  }).always(function(){
      // console.log('The ajax call ended.');
  });
}


function getListadoProductos() {
  $.ajax({
      url: 'service_getListadoMatriz.php',
      type: 'POST',
      dataType: 'json'
  }).done(function(data){
    console.log(data);
    if(data){
      notificacion('Productos actualizados','Los productos se encuentran actualizados.','<icon class=\"fa fa-check\">','success',5000);
    }
  }).fail(function(xhr, textStatus, errorThrown) {
    console.log(xhr, textStatus, errorThrown);
      notificacion('Error','El puesto no pudo conectarse con el sistema matriz. <br> Intente de nuevo más tarde','<icon class=\"fa fa-warning\">','warning',5000);
  }).always(function(){
      // console.log('The ajax call ended.');
  });
}

// function liquidarMercaderia(id) {
//   $('#liquidar').hide();
//   $('#liquidarMercaderia').html("<i class='fa fa-spinner fa-pulse fa-2x fa-fw'></i>");


//   $.ajax({
//       url: 'ajax_liquidarMercaderia.php',
//       type: 'POST',
//       data: {id : id},
//       dataType: 'json'
//   }).done(function(data){
//     if(data){
//     $('#liquidarMercaderia').html("");
//     $('#modal-edit-lote').modal('hide');
//     console.log(id);
//     notificacion('Lote Liquidado','El lote fue liquidado con exito.','<icon class=\"fa fa-check\">','success',5000);
//     }
//   }).fail(function(xhr, textStatus, errorThrown) {
//     console.log(xhr, textStatus, errorThrown);
//   $('#liquidarMercaderia').html("");
//       notificacion('Error','Error al liquidar mercadería. <br> Intente de nuevo más tarde','<icon class=\"fa fa-warning\">','warning',5000);
//   }).always(function(){
//       // console.log('The ajax call ended.');
//   });
// }



