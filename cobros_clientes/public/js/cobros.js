
/* -- FUNCIONES NUEVA VENTA -- */

/* Para que deje scrollear el modal anterior!! */
$(document).on('hidden.bs.modal', '.modal', function () {
    $('.modal:visible').length && $(document.body).addClass('modal-open');
});

 $('.soloNumeros').keypress(function (tecla) {
    if (tecla.charCode < 48 || tecla.charCode > 57) return false; });


$('#combo_fpago').on('input',function(){
  var i = $(this).val();
  if(i == 1){ // Contado
    $('.div-pagos').css('display','none');
    $('#div-contado').css('display','block');
    $('.input-pagos').attr('disabled',true);
    $('.form-contado').attr('disabled',false);
  }
  if(i == 6){ // Cheque
    $('.div-pagos').css('display','none');
    $('#div-cheque').css('display','block');
    $('.input-pagos').attr('disabled',true);
    $('.form-cheque').attr('disabled',false);
  }
  if(i == 3){ // Tarjeta cred
    $('.div-pagos').css('display','none');
    $('#div-credito').css('display','block');
    $('.input-pagos').attr('disabled',true);
    $('.form-credito').attr('disabled',false);
  }
  if(i == 4){ // Tarjeta deb
    $('.div-pagos').css('display','none');
    $('#div-debito').css('display','block');
    $('.input-pagos').attr('disabled',true);
    $('.form-debito').attr('disabled',false);
  }
  if(i == 5){ // Boleto
    $('.div-pagos').css('display','none');
    $('#div-boleto').css('display','block');
    $('.input-pagos').attr('disabled',true);
    $('.form-boleto').attr('disabled',false);
  }
  if(i == 8){ // Transferencia
    $('.div-pagos').css('display','none');
    $('#div-transfer').css('display','block');
    $('.input-pagos').attr('disabled',true);
    $('.form-transfer').attr('disabled',false);
  }

  if(i == 9){ // Deposito
    $('.div-pagos').css('display','none');
    $('#div-deposito').css('display','block');
    $('.input-pagos').attr('disabled',true);
    $('.form-deposito').attr('disabled',false);
  }

});

function validarYsubmitear() {
  var cli = $('#combo_cli_cobro').val();
  var forma = $('#combo_fpago').val();
  var sigue = 1;

  /* Valido montos */
    var monto = false;

    if(!forma) {
      alert('Debe seleccionar una forma de pago');
      return false;
    } else {
      switch(forma) {
        case '1':
          monto = $('#input_monto_contado').val(); // 1. Contado
          break;
        case '6':
          monto = $('#input_monto_cheque').val(); // 2. Cheque
          break;
        case '3':
          monto = $('#input_monto_credito').val(); // 3. Credito 
          break;
        case '4':
          monto = $('#input_monto_debito').val(); // 4. Debito
          break;
        case '5':
          monto = $('#input_monto_pesos_boleto').val(); // 5. Boleto
          break;
        case '8':
          monto = $('#input_monto_transfer').val(); // 8. Transferencia bancaria
          break;
        case '9':
          monto = $('#input_monto_deposito').val(); // 9. Deposito bancario
          break;
      }

      if(!monto) {
        alert('Debe ingresar un monto válido');
        return false;
      } else {
        sigue = sigue * 1;
      }
    }

  // Valido cliente
    if(!cli) {
      alert('Debe ingresar un cliente existente.');
      return false;
    }

  // Valido cheque

    if(forma == 6){
      var nro = $('#input_numero_cheque').val();
      var fcobro = $('#input_cobro_cheque').val();
      var fcobromax = $('#input_cobro_cheque').attr('max');
      var bco = $('#input_banco_cheque').val();
      var titular = $('#input_titular_cheque').val();
      var femision = $('#input_emision_cheque').val();
      var femisionmax = $('#input_emision_cheque').attr('max');
      
      if(!fcobro || !femision || fcobro < femision){
        alert('La fecha de cobro debe ser mayor que la fecha de emisión del cheque.');
        return false;
      } else if(femision > femisionmax){
        alert('La fecha de emision no puede ser mayor a '+femisionmax);
        return false;
      } else if(fcobro > fcobromax){
        alert('La fecha de cobro no puede ser mayor a '+fcobromax);
        return false;
      } else if(!nro){
        alert('Número de cheque incorrecto.');
        return false;
      } else if(!bco){
        alert('Por favor seleccione un banco');
        return false;
      } else if(!titular){
        alert('Por favor ingrese titular.');
        return false;
      } else {
        sigue = sigue * 1;
      }
    }

    // Valido boletos

    if(forma == 5){
      var numeroboleto = $('#input_numero_boleto').val();
      var bancoboleto = $('#input_banco_boleto').val()
      var femisionboleto = $('#input_emision_boleto').val();
      var femisionboletomax = $('#input_emision_boleto').attr('max');
      var fevencimientoboleto = $('#input_venc_boleto').val();

      if(!numeroboleto){
        alert('Ingrese el numero del boleto.');
        return false;
      }else if(!bancoboleto) {
        alert('Ingrese el banco.');
        return false;
      }else if(!femisionboleto) {
        alert('Ingrese la fecha de emision.');
         return false;
       }else if(!fevencimientoboleto) {
        alert('Ingrese la fecha de vencimiento.');
         return false;
      }else if(femisionboleto > femisionboletomax) {
        alert('la fecha de emision no puede ser superior a hoy.');
         return false;
      }

       else {
        sigue = sigue * 1;
      }
    }

    // Valido transferencias

    if(forma == 8){
      var emisor = $('#input_banco_emisor_t').val();
      var receptor = $('#input_banco_receptor_t').val();

      if(!emisor || !receptor){
        alert('Ingrese todos los datos necesarios.');
        return false;
      } else {
        sigue = sigue * 1;
      }
    }

    // Valido depositos

    if(forma == 9){
      var emisor = $('#input_banco_d').val();

      if(!emisor){
        alert('Debe seleccionar un banco.');
        return false;
      } else {
        sigue = sigue * 1;
      }
    }

    if (sigue){
      $('#form_cobro').submit();
    }

}

$('.soloNumeros').keypress(function (tecla) {
  if (tecla.charCode < 48 || tecla.charCode > 57) return false;
});

function getEstadoCC(id) {
  $.ajax({
      url: 'ajax_getEstadoCuenta.php',
      type: 'POST',
      data: {id : id},
      dataType: 'json'
  }).done(function(data){
    //console.log(data);
    $('#deuda_cliente').html('');
    $('#deuda_cliente').html(data.texto);
    $('#deuda_cliente').removeClass().addClass(data.clase);
  }).fail(function(xhr, textStatus, errorThrown) {
      console.log(xhr.responseText);
  }).always(function(){
      // console.log('The ajax call ended.');
  });
}

$('#combo_cli_cobro').on('change',function(){
  id = $('#combo_cli_cobro').val();
  if (id){
    getEstadoCC(id);
  }else{
    $('#deuda_cliente').html('');
  }
});

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

function existeVentaPendiente(){
  var id= $('#combo_cli_cobro').val();
   $.ajax({
      url: 'ajax_getVentaPendiente.php',
      type: 'POST',
      data: {id : id},
      dataType: 'html'
  }).done(function(data){
      if(data != 0){
        alert("Para realizar un cobro de cuenta corriente a Clientes, los mismos no deben tener ventas en estado pendiente. Por favor diríjase al módulo Gestión de Ventas.")
        $('#deuda_cliente').text('');
        $('#combo_cli_cobro').val('').trigger('change');
      }
  }).fail(function(xhr, textStatus, errorThrown) {
      console.log(xhr.responseText);
  }).always(function(){
      // console.log('The ajax call ended.');
  });
}
