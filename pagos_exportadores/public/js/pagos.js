
/* -- FUNCIONES NUEVA VENTA -- */

/* Para que deje scrollear el modal anterior!! */
$(document).on('hidden.bs.modal', '.modal', function () {
    $('.modal:visible').length && $(document.body).addClass('modal-open');
});

/* Solo numeros en los inputs numericos */
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
  if(i == 2){ // Cheque
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
  if(i == 5){ // Bono
    $('.div-pagos').css('display','none');
    $('#div-bono').css('display','block');
    $('.input-pagos').attr('disabled',true);
    $('.form-bono').attr('disabled',false);
  }
   if(i == 6){ // Cheuque terceros
    $('.div-pagos').css('display','none');
    $('#div-terceros').css('display','block');
    $('.input-pagos').attr('disabled',true);
    $('.form-terceros').attr('disabled',false);
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

  if(i == 11){ // Con dinero de otro puesto
    $('.div-pagos').css('display','none');
    $('#div-otropuesto').css('display','block');
    $('.input-pagos').attr('disabled',true);
    $('.form-otropuesto').attr('disabled',false);
    $('#input_obs_pago').attr('placeholder',"Especifique el puesto que realiza el pago");
    $('#input_obs_pago').prop('required',true);
  }else{
    $('#input_obs_pago').attr('placeholder',"Observación del pago");
    $('#input_obs_pago').prop('required',false);

  }

});


function validarYsubmitear() {

  var exportad = $('#combo_cli').val();
  var forma = $('#combo_fpago').val();
  var obs = $('#input_obs_pago').val();
  var sigue = 1;

  /* Valido montos */
    var monto = false;

    if(!forma) {
      alert('Debe seleccionar una forma de pago');
      return false;
    } else {
      switch(forma) {
        case '1':
          monto = $('#input_monto').val(); // 1. Contado
          break;
        case '2':
          monto = $('#input_monto_cheque').val(); // 2. Cheque propio
          break;
        case '6':
          monto = $('#input_monto_cheque_tercero').val(); // 6. Cheque terceros
          break;
        case '8':
          monto = $('#input_monto_transfer').val(); // 8. Transferencia bancaria
          break;
        case '9':
          monto = $('#input_monto_deposito').val(); // 9. Deposito bancario
          break;
        case '11':
          monto = $('#input_monto_otropuesto').val(); // 9. Deposito bancario
          if(obs == ''){
            alert('Especifique el puesto que realiza el pago');
            return false;
          }
          break;
      }

      if(!monto || monto < 1) {
        alert('Debe ingresar un monto válido');
        return false;
      } else {
        sigue = sigue * 1;
      }
    }

  // Valido cliente
    if(!exportad) {
      alert('Debe ingresar un exportador existente.');
      return false;
    }

  // Valido cheque

    if(forma == 2){
      var nro = $('#input_numero_cheque').val();
      var fcobro = $('#input_cobro_cheque').val();
      var fcobromax = $('#input_cobro_cheque').attr('max');
      var bco = $('#input_banco_cheque').val();
      var titular = $('#input_titular_cheque').val();
      var femision = $('#input_emision_cheque').val();
      var femisionmax = $('#input_emision_cheque').attr('max');
      
      console.log(femisionmax);

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

    if (sigue){
      $('#form_pago').submit();
    }

}

function getPago(id) {
  $.ajax({
      url: 'ajax_getPago.php',
      type: 'POST',
      data: {id : id},
      dataType: 'html'
  }).done(function(data){
      console.log(data);
      $('#modal-ver-pago').html('');
      $('#modal-ver-pago').html(data);
      $('#modal-ver-pago').modal('show');
  }).fail(function(xhr, textStatus, errorThrown) {
      console.log(xhr.responseText);
  }).always(function(){
      // console.log('The ajax call ended.');
  });
}

function getEstadoCC(id) {
  $.ajax({
      url: 'ajax_getEstadoCuenta.php',
      type: 'POST',
      data: {id : id},
      dataType: 'json'
  }).done(function(data){
    //console.log(id);
    $('#deuda_prov').html('');
    $('#deuda_prov').html(data.texto);
    $('#deuda_prov').removeClass().addClass(data.clase);
  }).fail(function(xhr, textStatus, errorThrown) {
      console.log(xhr.responseText);
  }).always(function(){
      // console.log('The ajax call ended.');
  });
}

$('#combo_cli').on('change',function(){
  id = $('#combo_cli').val();
  getEstadoCC(id);
});