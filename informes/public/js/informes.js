// Funcion que abre el modal cuando se clickea el ver 
function traerInfodetallada(id,calibre, nombre){
  $('#variacionesprecio').modal("show");
  $('#nombre_producto').text(nombre);
  $('#contenedor-esperando').show();
    $.ajax({
      url: 'ajax_get_info_variaciones_precio.php',
      type: 'POST',
      data: {id : id , calibre : calibre, nombre :nombre},
      dataType: 'html'
  }).done(function(data){
    $('#contenedor-esperando').hide();
    $('#info_traida_x_ajax').html('');
    $('#info_traida_x_ajax').html(data);
  }).fail(function(xhr, textStatus, errorThrown) {
        $('#contenedor-esperando').hide();
    $('#info_traida_x_ajax').html('');
    $('#info_traida_x_ajax').html("<h2 style='text-align:center;'>Hubo un error al traer los datos, intentelo mas tarde..</h2>");
      console.log(xhr.responseText);
  }).always(function(){
      // console.log('The ajax call ended.');
  });
}


function getVentaSaldada(id) {
  $.ajax({
      url: 'ajax_getVentaSaldada.php',
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


function verVentas(ids,fila) {
 $.ajax({
      url: 'ajax_get_ventas.php',
      type: 'POST',
      data: {ids : ids},
      dataType: 'html'
  }).done(function(data){
     $('#botonVer_'+fila+'').hide();
      $('#botonOcultar_'+fila+'').show('');
    $('#div_'+fila+'').html('');
      $('#div_'+fila+'').html(data);
      $('#div_'+fila+'').slideDown();

  }).fail(function(xhr, textStatus, errorThrown) {
      console.log(xhr.responseText);
  }).always(function(){
      // console.log('The ajax call ended.');
  });
}

 function OcultarVentas(fila) {
   $('#div_'+fila+'').slideUp();
   $('#botonOcultar_'+fila+'').hide();
   $('#botonVerSinAjax_'+fila+'').show();

 }
 function verVentassinAjax(fila){
   $('#div_'+fila+'').slideDown();
   $('#botonVerSinAjax_'+fila+'').hide();
   $('#botonOcultar_'+fila+'').show();
 }


