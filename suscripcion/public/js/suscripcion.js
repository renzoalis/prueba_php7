
/* -- FUNCIONES Config Suscripcion -- */



/* Cargar modal al editar una cancha */
function modificarCancha(id){

  $.ajax({

      url: 'ajax_getCancha.php',
      type: 'POST',
      data: {id : id},
      dataType: 'json'

  }).done(function(data){
      $('#cancha_id').val(data.id);
      $('#cancha_nombre').val(data.nombre);
      $('#monto_pago').val(data.valor);
      
  }).fail(function(xhr, textStatus, errorThrown) {
      console.log(errorThrown);
  });
  
}
