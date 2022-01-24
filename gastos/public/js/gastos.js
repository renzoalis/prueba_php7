
/* -- FUNCIONES NUEVA VENTA -- */

/* Para que deje scrollear el modal anterior!! */
$(document).on('hidden.bs.modal', '.modal', function () {
    $('.modal:visible').length && $(document.body).addClass('modal-open');
});



function getSalidaCaja(id,categoria) {
  // console.log(1);
  $.ajax({
      url: 'ajax_getSalidasCaja.php',
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

function actualizarObs(id) {

    if(id == 1){ //Alquiler/Piso
      $('#input_concepto').attr('placeholder',"Especifique Mes");
      $('#input_concepto').prop('required',true);
    }

    if(id == 2){ // Entradas (Guias)
      $('#input_concepto').attr('placeholder',"Especifique Chofer");
      $('#input_concepto').prop('required',true);
    }

    if(id == 3){ // Reparto Clientes
      $('#input_concepto').attr('placeholder',"Especifique Detalle");
      $('#input_concepto').prop('required',true);
    }

    if(id == 4){ // Repaso
      $('#input_concepto').attr('placeholder',"Especifique Detalle");
      $('#input_concepto').prop('required',true);
    }

    if(id == 5){ // Servicios
      $('#input_concepto').attr('placeholder',"Especifique Servicio");
      $('#input_concepto').prop('required',true);
    }

    if(id == 6){ // Servicios
      $('#input_concepto').attr('placeholder',"Especifique Detalle");
      $('#input_concepto').prop('required',true);
    }

    if(id == 7){ // Pago de mercaderia recibida por Transferencia
      $('#input_concepto').attr('placeholder',"Especifique Puesto que Transfirió");
      $('#input_concepto').prop('required',true);
    }

    if(id == 8){ // Retiro de Caja
      $('#input_concepto').attr('placeholder',"Especifique Detalle");
      $('#input_concepto').prop('required',true);
    }

    if(id == 11){ // CHOFERES
      $('#input_concepto').attr('placeholder',"Especifique chofer");
      $('#input_concepto').prop('required',true);
    }

    // if(id == 10){ // Devolucion de mercaderia
    //   $('#input_concepto').attr('placeholder',"Especifique Sobrante/Faltante");
    //   $('#input_concepto').prop('required',true);
    // }

    if(id == 9){ // CONCILIACION DE CAJA
      $('#input_concepto').attr('placeholder',"Especifique si es sobrante o faltante");
      $('#input_concepto').prop('required',true);
    }

}
  function validarYsubmitear(monto_caja) {
    /* Valido montos */
    var error = false;
    var monto = false;

    monto = $("#input_monto").val(); // 1. Contado
    if (monto > monto_caja) {
      alert("El monto no debe superar al efectivo de la caja");
      error = true;
    }
   
    if (!monto || monto < 1) {
      alert("Debe ingresar un monto válido");
      error = true;
    }
    
    if (!error) {
      $("#detalle_gasto_add").submit();
    } 
  }
