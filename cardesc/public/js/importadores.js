/* -- FUNCIONES NUEVA VENTA -- */

/* Para que deje scrollear el modal anterior!! */
$(document).on('hidden.bs.modal', '.modal', function () {
    $('.modal:visible').length && $(document.body).addClass('modal-open');
});

function getImportador(id) {
  $.ajax({
      url: 'ajax_getImportador.php',
      type: 'POST',
      data: {id : id},
      dataType: 'html'
  }).done(function(data){
    $('#modal-edit-importador').html('');
      $('#modal-edit-importador').html(data);
      $('#modal-edit-importador').modal('show');
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

function eliminarImportador(){
  if(confirm('Desea eliminar el importador de forma permanente?')){
    $('#eliminar_importador').submit();
  }
}

/* -- FUNCIONES NUEVA VENTA -- */

/* Para que deje scrollear el modal anterior!! */
$(document).on('hidden.bs.modal', '.modal', function () {
    $('.modal:visible').length && $(document.body).addClass('modal-open');
});

function getImportador(id) {
  $.ajax({
      url: 'ajax_getImportador.php',
      type: 'POST',
      data: {id : id},
      dataType: 'html'
  }).done(function(data){
    $('#modal-edit-importador').html('');
      $('#modal-edit-importador').html(data);
      $('#modal-edit-importador').modal('show');
  }).fail(function(xhr, textStatus, errorThrown) {
      console.log(xhr.responseText);
  }).always(function(){
      // console.log('The ajax call ended.');
  });
}



function eliminarImportador(){
  if(confirm('Desea eliminar el importador de forma permanente?')){
    $('#eliminar_importador').submit();
  }
}

function getDetalleCC(tipo,id){
  //console.log(tipo);
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
  if(tipo == 13){
    getDescarga(id);
  }
  if(tipo == 14){
    getCarga(id);
  }
}

function getDescarga(id) {
  $.ajax({
      url: 'ajax_getDescarga.php',
      type: 'POST',
      data: {id : id},
      dataType: 'html'
  }).done(function(data){
    $('#modal-ver-descarga').html('');
    $('#modal-ver-descarga').html(data);
    $('#modal-ver-descarga').modal('show');
  }).fail(function(xhr, textStatus, errorThrown) {
      console.log(xhr.responseText);
  }).always(function(){
      // console.log('The ajax call ended.');
  });
}

function getCarga(id) {
  $.ajax({
      url: 'ajax_getCarga.php',
      type: 'POST',
      data: {id : id},
      dataType: 'html'
  }).done(function(data){
    $('#modal-ver-carga').html('');
    $('#modal-ver-carga').html(data);
    $('#modal-ver-carga').modal('show');
  }).fail(function(xhr, textStatus, errorThrown) {
      console.log(xhr.responseText);
  }).always(function(){
      // console.log('The ajax call ended.');
  });
}