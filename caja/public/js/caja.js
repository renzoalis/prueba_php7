
/* -- FUNCIONES NUEVA VENTA -- */

/* Para que deje scrollear el modal anterior!! */
$(document).on('hidden.bs.modal', '.modal', function () {
    $('.modal:visible').length && $(document.body).addClass('modal-open');
});

function abrirCaja() {
    var monto = $('#monto_inicial').val();
    if(!monto) {
      alert('Debe ingresar un monto.');
    } else {
      $('#abrir_caja').submit();
    }
}

function enviarPPVenta() {
  $.ajax({
      url: 'service_enviarPPVenta.php',
      type: 'POST',
      dataType: 'json'
  }).done(function(data){
      notificacion('Precios promedio de venta','Los precios de venta se encuentran actualizados.','<icon class=\"fa fa-check\">','success',5000);
  }).fail(function(xhr, textStatus, errorThrown) {
      notificacion('Precios promedio de venta','El puesto no pudo conectarse con el sistema matriz. <br> Intente de nuevo más tarde','<icon class=\"fa fa-warning\">','warning',5000);
  }).always(function(){
      // console.log('The ajax call ended.');
  });
}

function actualizarCaja() {
  $('#contenedor-datos-caja').html('');
  $('#contenedor-esperando').css('display','block');

  var fecha = $('#fecha_historial').val();

  $.ajax({
      url: 'ajax_actualizarCaja.php',
      type: 'POST',
      data: {fecha : fecha},
      dataType: 'html'
  }).done(function(data){
      //console.log(data);
      $('#contenedor-datos-caja').css('display','none');
      $('#contenedor-datos-caja').html(data);
      $('#contenedor-esperando').delay( 1500 ).fadeOut( 400 );
      $('#contenedor-datos-caja').delay( 2000 ).fadeIn( 900 );
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
  if(tipo == 1){
    getVenta(id);
  } 
  if(tipo == 3){
    getCobro(id);
  }
  if(tipo == 5 || tipo == 6){
    getNota(id);
  }
}

function modalConciliarStock(id) {
  $('#div-cerrar-caja').html('');

  $.ajax({
      url: 'ajax_getDatosConciliacion.php',
      type: 'POST',
      data: {id : id},
      dataType: 'html'
  }).done(function(data){
      // console.log(data);
      $('#div-conciliar').html(data);
      $('#modalConciliar').modal('show');
  }).fail(function(xhr, textStatus, errorThrown) {
      console.log(xhr.responseText);
  }).always(function(){
      // console.log('The ajax call ended.');
  });
}

function modificarReal(id) {
  $('#modificoAlguna').val(1);
  var real = $('#cant_'+id+'').val();
  var actual = $('#real_'+id+'').val();
  var total = actual - real;
  $('#dif_'+id+'').html(total);
  if(total != 0) {
    $('#tipo_'+id+'').show();
    $('#tipo_'+id+'').attr('disabled',false);
  } else {
    $('#tipo_'+id+'').hide();
    $('#tipo_'+id+'').attr('disabled',true);
  }
}

function modalCerrarCaja(id) {
  $('#div-cerrar-caja').html('');

  $.ajax({
      url: 'ajax_getDatosCerrarCaja.php',
      type: 'POST',
      data: {id : id},
      dataType: 'html'
  }).done(function(data){
      $('#div-cerrar-caja').html(data);
      $('#modalCerrarCaja_').modal('show');
  }).fail(function(xhr, textStatus, errorThrown) {
      console.log(xhr.responseText);
  }).always(function(){
      // console.log('The ajax call ended.');
  });
}

$('.soloNumeros').keypress(function (tecla) {
  if (tecla.charCode < 48 || tecla.charCode > 57) return false;
});

function checkEnviarDatosCierre() {
  $.ajax({
      url: 'ajax_hayDatosParaEnviar.php',
      type: 'POST',
      dataType: 'json'
  }).done(function(data){
      // Hay cajas para enviar datos?
      if(data > 0) {
        servicioEnviarDatosCierre();
      }

  }).fail(function(xhr, textStatus, errorThrown) {
      console.log(xhr.responseText);
  }).always(function(){
      // console.log('The ajax call ended.');
  });

}

function servicioEnviarDatosCierre(){
  // Si hay:
  if(navigator.onLine) { // Servicios si el navegador esta online

    $.ajax({
        url: 'ajax_servicioEnviarDatosCaja.php',
        type: 'POST',
        dataType: 'json'
    }).done(function(data){
        if(data) {
          notificacion('Resumen enviado','El resumen diario fue enviado al sistema matriz.','<icon class=\"fa fa-check\">','success',10000);
          enviarPPVenta();
        }

    }).fail(function(xhr, textStatus, errorThrown) {
        console.log(xhr.responseText);
    }).always(function(){
        // console.log('The ajax call ended.');
    });

  } else {
    notificacion('Sin conexión','El puesto se encuentra desconectado de internet. <br> Sincronización de datos pendiente.','<icon class=\"fa fa-times\">','error',10000);
  }

}