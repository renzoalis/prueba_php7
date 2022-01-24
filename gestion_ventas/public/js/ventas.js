
/* -- FUNCIONES NUEVA VENTA -- */

/* Alta de cliente con Ajax ( ahora se mandan por submit )
function ajax_guardarCliente() {

  var data = JSON.stringify( $('#detalle_cliente_add').serializeArray() ); 
  
    $.ajax({
      url: 'ajax_addCliente.php',
      type: 'POST',
      data: {data : data},
      dataType: 'json'
  }).done(function(data){
      if(data != false){
        var newOption = new Option(data.nombre, data.id, false, false);
        $('#combo_cli').append(newOption).trigger('change');
        $('#combo_cli').val(data.id);
        guardareditarcliente();
        $('#myModalClienteAdd').modal('hide');
        getEstadoCC(data.id);
      }else{
        alert('Existe un cliente con el mismo nombre');
      }
  }).fail(function(xhr, textStatus, errorThrown) {
      console.log(xhr.responseText);
  });
}
*/



/* Calcular montos totales */
function calcular_total() {
  var sum = 0;
  $('.precio_parc').each(function() {
      sum += parseFloat($(this).val());
  });
  $('#saldo_final_total').val(sum);
}

/* Para que deje scrollear el modal anterior!! */
$(document).on('hidden.bs.modal', '.modal', function () {
    $('.modal:visible').length && $(document.body).addClass('modal-open');
});

/* Modificar linea de producto */
function modificar(i) {
  // Actualizo factura
    var precio_unitario = parseFloat($('#precio_u_'+i+'').val());
    var peso = parseFloat($('#peso_'+i+'').val());
    var precio_parcial = (precio_unitario * peso);
    $('#precio_total_'+i+'').val(precio_parcial);
  
  // Calculos + Precio total + Tabla conceptos
    calcular_total();
}

/* Selección de producto (Funcion) */
function cargar_producto_lista (prod_modelo, prod_id) {
  // Agregar el item a la factura

    var i = parseInt($('#cant_prod').val());

    var newRowContent = '<tr>';
    newRowContent += '<td>'+prod_modelo+'<input type="hidden" id="prod_id_'+i+'" name="prod['+i+'][id]" value="'+prod_id+'"></td>';
    newRowContent += '<td><input class="det_venta ultimo" type="number" step="0.10" min="0" id="peso_'+i+'" name="prod['+i+'][peso]" value="" oninput="modificar('+i+');"></td>';
    newRowContent += '<td><input class="det_venta" type="number" step="0.10" min="0" id="precio_u_'+i+'" name="prod['+i+'][precio_u]" value="" oninput="modificar('+i+');"></td>';
    newRowContent += '<td><input type="number" step="0.10" min="0" disabled="disabled" value="" id="precio_total_'+i+'" class="precio_parc"> <a href="#" class="borrar"><i class="fa fa-times" aria-hidden="true"></i></a></td>';
    newRowContent += '</tr>';

    $("#tabla_productos tbody").append(newRowContent);
  // Limpiar el campo del producto
    $("#combo_prod").val('');
  // Calcular el total
    calcular_total();
  // Modifico cantidad de productos
    $('#cant_prod').val(i+1);
    // console.log($('.ultimo').last());
}

/* Borrar producto de la factura */
$(document).on('click', '.borrar', function (event) {
  event.preventDefault();
  $(this).closest('tr').remove();
  calcular_total();
});

function getVentaPendiente(id) {
  $.ajax({
      url: 'ajax_getVentaPendiente.php',
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


function getVenta(id) {
  $.ajax({
      url: 'ajax_getVenta.php',
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

function getVentaArchivada(id) {
  $.ajax({
      url: 'ajax_getVentaArchivada.php',
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

// document.getElementById('detalle_venta').addEventListener('submit', function(evt){
//     evt.preventDefault();
//     if($('#detalle_venta')[0].checkValidity()){
//       if(confirm('Por favor revise los datos de la venta.  \nSi son correctos click en OK para guardar.')){
//         $('#detalle_venta')[0].submit();
//       }
//     }
// });

// Funcion para poner puntos en los 1.000
// Muy experimental!
// Funciona con campo texto, hay que parsear todo, una paja.

// function addCommas(nStr){
//   nStr += '';
//   var x = nStr.split('.');
//   var x1 = x[0];
//   var x2 = x.length > 1 ? '.' + x[1] : '';
//   var rgx = /(\d+)(\d{3})/;
//   while (rgx.test(x1)) {
//   x1 = x1.replace(rgx, '$1' + ',' + '$2');
//   }
//   return x1 + x2;
// }

function editarVenta(){
  // oculto botones menos el de volver
  $('#form_editar_venta').find('input').attr('disabled',false);
  $('#botonImprimir').css('display','none');
  $('#boton-editar').css('display','none');
  $('#boton-pagar').css('display','none');
  $('#boton-descuento').css('display','none');
  $('.sineditar').css('display','none');
  $('#boton_guardar_edit').css('display','inline');
  $('.editando').css('display','block');
}


$('#combo_fpago').on('input',function(){
  var montoVenta = $('#hidden_monto_total_venta').val();
  var i = $(this).val();
  if(i == 1){ // Contado
    $('.div-pagos').css('display','none');
    $('#div-contado').css('display','block');
    $('.input-pagos').attr('disabled',true);
    $('.form-contado').attr('disabled',false);
    $('#input_monto_contado').attr('max',montoVenta);
    $('#input_monto_contado').attr("readonly","true");
  }
  if(i == 6){ // Cheque
    $('.div-pagos').css('display','none');
    $('#div-cheque').css('display','block');
    $('.input-pagos').attr('disabled',true);
    $('.form-cheque').attr('disabled',false);
    $('#input_monto_cheque').attr('max',montoVenta);
  }
  if(i == 3){ // Tarjeta cred
    $('.div-pagos').css('display','none');
    $('#div-credito').css('display','block');
    $('.input-pagos').attr('disabled',true);
    $('.form-credito').attr('disabled',false);
    $('#input_monto_credito').attr('max',montoVenta);
  }
  if(i == 4){ // Tarjeta deb
    $('.div-pagos').css('display','none');
    $('#div-debito').css('display','block');
    $('.input-pagos').attr('disabled',true);
    $('.form-debito').attr('disabled',false);
    $('#input_monto_debito').attr('max',montoVenta);
  }
  if(i == 5){ // Boleto
    $('.div-pagos').css('display','none');
    $('#div-boleto').css('display','block');
    $('.input-pagos').attr('disabled',true);
    $('.form-boleto').attr('disabled',false);
    $('#input_monto_pesos_boleto').attr('max',montoVenta);
  }
  if(i == 8){ // Transferencia
    $('.div-pagos').css('display','none');
    $('#div-transfer').css('display','block');
    $('.input-pagos').attr('disabled',true);
    $('.form-transfer').attr('disabled',false);
    $('#input_monto_transfer').attr('max',montoVenta);
  }

  if(i == 9){ // Deposito
    $('.div-pagos').css('display','none');
    $('#div-deposito').css('display','block');
    $('.input-pagos').attr('disabled',true);
    $('.form-deposito').attr('disabled',false);
    $('#input_monto_deposito').attr('max',montoVenta);
  }

   if(i == 10){ // Cuenta Corriente
    $('.div-pagos').css('display','none');
    $('.input-pagos').attr('disabled',true);
    $('.form-deposito').attr('disabled',false);
  }

});

function validarYsubmitear() {
  var cli = $('#combo_cli_cobro').val();
  var forma = $('#combo_fpago').val();
  var saldo_total = $('#saldo_final_total').val();
  var sigue = 1;

  /* Valido montos */
    var monto = false;
  if(forma != 10){  
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

      if(!monto || monto < 0) {
        alert('Debe ingresar un monto válido');
        return false;
      } else {
          if(parseInt(monto) < parseInt(saldo_total)) {
          
            if(cli == 9999){
              alert('El monto no puede ser menor al saldo total de la venta.');
              return false;
            }else{
              if(confirm('El pago es menor al saldo total de la venta. Desea continuar?')){
                sigue = sigue * 1;
              } else {
                return false;
              }
            }
          } else { // El monto es mayor o igual al saldo
              if(parseInt(monto) == parseInt(saldo_total)) {
                sigue = sigue * 1;
              } else {
                alert('El cobro no puede ser mayor al monto total de la venta.');
                sigue = 0;
                return false;
              }
            }
        }
    }
  }

  // Valido cliente

    if(cli == 9999) {
      if(forma == 6 || forma == 5){
        alert('Debe ingresar un cliente existente para esta forma de pago.');
        return false;
      } else {
        // if(confirm('No seleccionó cliente. Se cargará un pago anónimo sin asiento en cuenta corriente de clientes.')){
        //   sigue = sigue * 1;
        // } else {
        //   return false;
        // }
      }
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


function ponerCosto(){

  var selectedItem = $("#combo_prod_dev").val();
  var cant=$("#combo_prod_dev").find(':selected').data("cant");
  var precio=$("#combo_prod_dev").find(':selected').data("costo");
  $("#concepto_venta_detalle_id").val($("#combo_prod_dev").find(':selected').data("det-id"));
  $("#input_cantidad_dev").attr("max",cant);
  $("#input_costo_dev").val(precio);
}

function inputsConcepto() {


  switch ($('#combo_tipo').val()) {
     case '1':
      $('#div-monto').show();
          $('#div-devolucion').hide();
          $('#combo_prod_dev').removeAttr('required');
          $('#input_cantidad_dev').removeAttr('required');
          $('#input_monto').attr("required", "true");
      break;
      case '2':
          $('#div-monto').show();
          $('#div-devolucion').hide();
          $('#combo_prod_dev').removeAttr('required');
          $('#input_cantidad_dev').removeAttr('required');
          $('#input_monto').attr("required", "true");
       break;
     case '3':
          $('#div-devolucion').hide();
          $('#div-monto').hide();
          $('#combo_prod_dev').removeAttr('required');
          $('#input_cantidad_dev').removeAttr('required');
          $('#input_monto').removeAttr('required');
      break;
      case '4':
          $('#div-monto').show();
          $('#div-devolucion').hide();
          $('#combo_prod_dev').removeAttr('required');
          $('#input_cantidad_dev').removeAttr('required');
          $('#input_monto').attr("required", "true");
      break;
      case '5':
          devolucionMercaderia($('#concepto_venta_id').val());
          $('#combo_prod_dev').attr("required", "true");
          $('#input_cantidad_dev').attr("required", "true");
          $('#div-monto').hide();
          $('#input_monto').removeAttr('required');
      break;
      case '6':
          $('#div-monto').show();
          $('#div-devolucion').hide();
          $('#combo_prod_dev').removeAttr('required');
          $('#input_cantidad_dev').removeAttr('required');
          $('#input_monto').attr("required", "true");
       break;
       case '7':
          $('#div-monto').show();
          $('#div-devolucion').hide();
          $('#combo_prod_dev').removeAttr('required');
          $('#input_cantidad_dev').removeAttr('required');
          $('#input_monto').attr("required", "true");
       break;
       case '8':
          $('#div-monto').show();
          $('#div-devolucion').hide();
          $('#div_tieneBoleto').hide();
          $('#combo_prod_dev').removeAttr('required');
          $('#input_cantidad_dev').removeAttr('required');
          $('#tieneBoleto1').removeAttr('required');
          $('#tieneBoleto2').removeAttr('required');
          $('#input_monto').attr("required", "true");
       break;
     default:
  }


}

function actualizarMonto() {
    var id = $('#combo_prod_dev').val();
    var costo = parseFloat($('#val_'+id).val());
    var cantidad = parseFloat($('#input_cantidad_dev').val());
    var precio_parcial = (parseFloat(costo) * parseFloat(cantidad));
    if(cantidad > $('#input_cantidad_dev').attr('max')){
      $('#input_cantidad_dev').val($('#input_cantidad_dev').attr('max'));
      precio_parcial = parseFloat($('#input_cantidad_dev').val()) * parseFloat(costo);
    }
    $("#saldo_final_dev").val(precio_parcial);

    if(precio_parcial) {
      $("#monto-nc-dev").html($("#saldo_final_dev").val());
      $("#monto-nc-dev2").html($("#saldo_final_dev").val());
      $("#monto-bol-desc").html($("#saldo_final_dev").val());
      $("#div-contable").show();
    } else {
      $("#div-contable").hide();
    }
}

function radios_dev(i) {
  if(i == 1) { // NC
    $('#dev-nota').show();
    $('#dev-descuento').hide();
  }

  if(i == 2) { // DESC
    $('#dev-nota').hide();
    $('#dev-descuento').show();


  }

}

function getEstadoCC(id) {
  $.ajax({
      url: 'ajax_getEstadoCuenta.php',
      type: 'POST',
      data: {id : id},
      dataType: 'json'
  }).done(function(data){
    // console.log(id);
    $('#deuda_cliente').html('');
    $('#deuda_cliente').html(data.texto);
    $('#deuda_cliente').removeClass().addClass(data.clase);
  }).fail(function(xhr, textStatus, errorThrown) {
      console.log(xhr.responseText);
  }).always(function(){
      // console.log('The ajax call ended.');
  });
}

$('.soloNumeros').keypress(function (tecla) {
  if (tecla.charCode < 48 || tecla.charCode > 57) return false;
});

$('#combo_cli_cobro').on('change',function(){
  id = $('#combo_cli_cobro').val();
  getEstadoCC(id);
})

function chequearDescuento(venta){
  
}

function cobrarVenta(venta, cliente,monto){
  $.ajax({
      url: 'ajax_getDescuentosVenta.php',
      type: 'POST',
      data: {id:venta},
      dataType: 'html'
  }).done(function(data){
    if(data > 0) {
      $('#saldo_descuentos').val(data);
      $('#saldo_final').val(monto-data);
    }

  $('#combo_fpago').attr("disabled",false);
  $('#input_monto_contado').attr("readonly",false);


  $('#venta_id').val(venta);
  $('#combo_cli_cobro').val(cliente).trigger('change').attr('disabled',true);
  $('#input_id_cliente').val(cliente);
  $('#combo_fpago').val(1);
  $('#div-contado').show();
  $('#input_monto_contado').val(monto-data);
  $('#input_monto_contado').attr('max',monto-data);

  $('#hidden_monto_total_venta').val(monto-data);
  $('#input_monto_contado').attr("readonly","true");
  if(cliente == 9999){
    $('#combo_fpago').attr("disabled","true");

  }
  }).fail(function(xhr, textStatus, errorThrown) {
      console.log(xhr.responseText);
  }).always(function(){
      // console.log('The ajax call ended.');
  });
}

function setearDevolucion(){
  var id = $('#combo_prod_dev').val();
  $('#concepto_venta_detalle_id').val(id);
  $('#input_obs_devolver').val($("#combo_prod_dev option:selected").text());
  $('.prods_dev').hide();
  $('#det_'+id).show();
  $('#saldo_final_dev').val(0);
  $('#input_cantidad_dev').attr('max',$('#cant_'+id).val());
  $('.mostrar-devolucion').show();
}

function cambiarRestaurar() {
  var ck = $('#input_restaurar_stock').val();
 console.log($('#input_restaurar_stock').checked);
  if($('#input_restaurar_stock').checked) {
    $('#rest_no').hide();
    $('#rest_si').show();
  }
  else {
    $('#rest_no').show();
    $('#rest_si').hide();
  }
}

function abrirModalDevolucion(venta_id, cliente_id){
  $.ajax({
      url: 'ajax_getModalDevolucion.php',
      type: 'POST',
      data: {venta_id:venta_id,
            cliente_id:cliente_id},
      dataType: 'html'
  }).done(function(data){
    $('#form_concepto').html('');
    $('#form_concepto').html(data);
    $('#myModalConcepto').modal('show');
    $('#div-devolucion').show();
  }).fail(function(xhr, textStatus, errorThrown) {
      console.log(xhr.responseText);
  }).always(function(){
      // console.log('The ajax call ended.');
  });
}


function aplicarDescuento(id) {
  $.ajax({
      url: 'ajax_getModalDescuentoPendiente.php',
      type: 'POST',
      data: {id:id},
      dataType: 'html'
  }).done(function(data){
    $('#form_descuento').html('');
    $('#form_descuento').html(data);
    $('#myModalDescuento').modal('show');
  }).fail(function(xhr, textStatus, errorThrown) {
      console.log(xhr.responseText);
  }).always(function(){
      // console.log('The ajax call ended.');
  });
}

function getProductoDescuento() {
  var id = $("#combo_descuento").val();
  if(id) {
    var cant = $("#combo_descuento").find(':selected').data("cant");
    var precio = $("#combo_descuento").find(':selected').data("preciou");
    var producto = $("#combo_descuento").find(':selected').data("prod");
    var descuento = $("#combo_descuento").find(':selected').data("descuento");
    var total = (cant*precio) - descuento;

    // alert(id + cant + precio + producto);
    var nuevaData = '<tr> <input type="hidden" name="id_detalle" value="'+id+'"><input type="hidden" name="nombre_prod" value="'+producto+'">';
    nuevaData += '<td>'+producto+'</td>';
    nuevaData += '<td> <input id="desc_cant" readonly value="'+cant+'"></td>';
    nuevaData += '<td> <input id="desc_precio" readonly value="'+precio+'"></td>';
    nuevaData += '<td><input required oninput="actualizarDescPorNum();" type="number" step="0.1" min="0" name="desc_num" id="desc_num"></td>';
    nuevaData += '<td><input required oninput="actualizarDescPorPorc();" type="number" step="0.1" min="0" max="100" name="desc_porc" id="desc_porc"></td>';
    if(descuento){
      nuevaData += '<td style="color: red;" label="Producto con descuento"><input readonly min="0" value="'+total+'" id="desc_total" title="Este producto tiene descuento"> </td>';
    }else{
      nuevaData += '<td><input readonly value="'+total+'" id="desc_total"> </td>';
    }
    nuevaData += '</tr>';
    
    $('#tabla_descuentos tbody').html('');
    $('#tabla_descuentos tbody').html(nuevaData);
    $('#seccion_descuento').show();
  } else {
    $('#seccion_descuento').hide();
  }
}

function actualizarDescPorNum(){
  var precio_original = $("#desc_precio").val();
  var cantidad = $("#desc_cant").val();

  var descNum = $("#desc_num").val();
  var descPorc = descNum * 100 / (precio_original * cantidad);
  var total_final = (precio_original * cantidad) - descNum;

  $("#desc_total").val(total_final);
  $("#desc_porc").val(descPorc.toFixed(1));
}

function actualizarDescPorPorc(){
  var precio_original = $("#desc_precio").val();
  var cantidad = $("#desc_cant").val();

  var descPorc = $("#desc_porc").val();
  var descNum = descPorc / 100 * (precio_original * cantidad);
  var total_final = (precio_original * cantidad) - descNum;

  $("#desc_total").val(total_final);
  $("#desc_num").val(descNum.toFixed(1));
}

// Actuliza el total en editar venta pendiente
function actualizarTotal(i,oldcantidad,oldvalor,stockmax) {
  var oldcantidad =parseFloat(oldcantidad);
  var oldvalor =parseFloat(oldcantidad);
  var stockmax = parseFloat(stockmax);
 // si trae el valor maximo de stock esta modificando la cantidad ( hay que validar)
 if(stockmax){
  // tomo el valor que ingreso y comparo con el stock disponible
  var cantidad = $('#prod_'+i+'_cant').val();
    if (cantidad > stockmax || cantidad <= 0) {
      alert('la cantidad no debe superar el stock disponible ('+stockmax+')');
      // cargo el viejo valor en el input
      $('#prod_'+i+'_cant').val(oldcantidad);

       }else {
        $('#prod_'+i+'_tot_desc').val(($('#prod_'+i+'_cant').val() * $('#prod_'+i+'_val').val()).toFixed(2) - $('#prod_'+i+'_desc').val()) };
        $('#prod_'+i+'_tot').val(($('#prod_'+i+'_cant').val() * $('#prod_'+i+'_val').val()).toFixed(2)); 

        calcular_total_edit();
  }else {
 $('#prod_'+i+'_tot_desc').val(($('#prod_'+i+'_cant').val() * $('#prod_'+i+'_val').val()).toFixed(2) - $('#prod_'+i+'_desc').val());
 $('#prod_'+i+'_tot').val(($('#prod_'+i+'_cant').val() * $('#prod_'+i+'_val').val()).toFixed(2)); 
  calcular_total_edit();
 };
// valido que los totales parciales no sean nulos
 var total_parcial = $('#prod_'+i+'_tot_desc').val();
  if ( total_parcial <= 0 ) {
     alert('Revise los datos ingresados,los totales no pueden ser negativos ni nulos');
     $('#prod_'+i+'_cant').val(oldcantidad);
     $('#prod_'+i+'_val').val(oldvalor);
     $('#prod_'+i+'_tot_desc').val(($('#prod_'+i+'_cant').val() * $('#prod_'+i+'_val').val()).toFixed(2) - $('#prod_'+i+'_desc').val());
     $('#prod_'+i+'_tot').val(($('#prod_'+i+'_cant').val() * $('#prod_'+i+'_val').val()).toFixed(2)); 
     calcular_total_edit();}
}

function calcular_total_edit() {
  var sum1 = 0;
  var sum2 = 0;
  $('.precio_parc').each(function() {
      sum1 += parseFloat($(this).val());
  });
  $('#saldo_final_total').val(sum1);

  $('.precio_parc_desc').each(function() {
      sum2 += parseFloat($(this).val());
  });
  $('#saldo_final_total_descuento').val(sum2);
}
// editar usuario
function editarusuario(){
$('.sineditarusuario').hide();
$('.editarusuario').show();
}

// guardar el cliente editado
function guardareditarcliente() {
var clienteid = $('#combo_cli').val();
var ventaid = $('#edit_venta_id').val();
      $.ajax({
        type:'POST',
        url: "ajax_postEditarCliente.php",
        data:{clienteid:clienteid,ventaid:ventaid},
          beforeSend: function(){ // esta funcion se ejecuta durante el envio de la peticion
            
          },
          complete:function(data){ // se ejecuta el terminar la peticion
           
          },
          success: function(data){ // se ejecuta cuando termina la peticion y fue correcta
            notificacion("Cliente Editado","Se editaron los datos correctamente","<icon class=\"fa fa-edit\">","success"); //muestra la notificacion
            if(clienteid != 9999 ) {

            }
            $('#nombre_'+ventaid).html(data); // actualizo el usuario en la tabla de ventas
            getVentaPendiente(ventaid);
          },
          error: function(data){ // Se ejecuta si la peticón ha sido erronea

            alert("Problemas al intentar editar los datos, intentelo nuevamente");
          }
        });
      // Nos permite cancelar el envio del formulario para que no recargue la pag
      return false;
    }


function notificacion(titulo,mensaje,icono,tipo){
     notify({
            type: tipo, //alert | success | error | warning | info
            title: titulo,
            position: {
                x: "right", //right | left | center
                y: "top" //top | bottom | center
              },
            autoHide: true, //true | false
            delay: 2500, //number ms
            icon:icono,
            message: mensaje
          });
   }


// DESCARGAS
function abrirModalDescarga(id) {
  $.ajax({
      url: 'ajax_getConceptoDescarga.php',
      type: 'POST',
      data: {venta_id:id
            },
      dataType: 'html'
  }).done(function(data){
    $('#form_concepto').html('');
    $('#form_concepto').html(data);
    $('#myModalDesc').modal('show');
  }).fail(function(xhr, textStatus, errorThrown) {
      console.log(xhr.responseText);
  }).always(function(){
      // console.log('The ajax call ended.');
  });
}

function setearDescarga(){
  var id = $('#combo_prod_dev').val();
  $('#concepto_venta_detalle_id').val(id);
  $('#input_obs_devolver').val($("#combo_prod_dev option:selected").text());
  $('.prods_dev').hide();
  $('#det_'+id).show();
  $('#saldo_final_dev').val(0);
  $('.mostrar-devolucion').show();
}

function actualizarMontoDescarga(){
  var id = $('#combo_prod_dev').val();
  var costo_u = $('#costo_'+id).val();
  var subtotal = $('#cant_'+id).val() * costo_u;
  $('#monto_final_desc').val(subtotal);
  $('#subtotal_descarga_'+id).html(subtotal);
}
// CARGAS
function abrirModalCarga(id) {
  $.ajax({
      url: 'ajax_getConceptoCarga.php',
      type: 'POST',
      data: {venta_id:id
            },
      dataType: 'html'
  }).done(function(data){
    $('#form_concepto').html('');
    $('#form_concepto').html(data);
    $('#myModalCarga').modal('show');
  }).fail(function(xhr, textStatus, errorThrown) {
      console.log(xhr.responseText);
  }).always(function(){
      // console.log('The ajax call ended.');
  });
}

function setearCarga(){
  var id = $('#combo_prod_carga').val();
  $('#concepto_venta_detalle_id').val(id);
  $('#input_obs_carga').val($("#combo_prod_carga option:selected").text());
  $('.prods_dev').hide();
  $('#det_'+id).show();
  $('#saldo_final_dev').val(0);
  $('.mostrar-devolucion').show();
}

function actualizarMontoCarga(){
  var id = $('#combo_prod_carga').val();
  var costo_u = $('#costo_'+id).val();
  var subtotal = $('#cant_'+id).val() * costo_u;
  $('#monto_final_carga').val(subtotal);
  $('#subtotal_carga_'+id).html(subtotal);
}


// GASTOS
function abrirModalGastos(id){
  $.ajax({
      url: 'ajax_getConceptoGasto.php',
      type: 'POST',
      data: {id:id
            },
      dataType: 'html'
  }).done(function(data){
    $('#form_concepto').html('');
    $('#form_concepto').html(data);
    $('#myModalGasto').modal('show');
  }).fail(function(xhr, textStatus, errorThrown) {
      console.log(xhr.responseText);
  }).always(function(){
      // console.log('The ajax call ended.');
  });
} 

// var event = $(document).click(function(e) {
//     e.stopPropagation();
//     e.preventDefault();
//     e.stopImmediatePropagation();
//     // $(document).unbind('click');
//     return false;
// });
// cuando se abre el modal para cargar un nuevo cliente le paso el id de venta asociada
function modal_agregar_nuevo_cliente(id_venta){
  $('#add_cliente_venta_id').val(id_venta);
}
// Funcion para crear un nuevo cliente, validando que no exista
      function validarCliente(){
          var nombre = $('#add_input_nombre').val();

          $.ajax({
              url: 'ajax_validarCliente.php',
              type: 'POST',
              data: {nombre : nombre},
              dataType: 'json'
          }).done(function(data){
            if(data){ //encontro un cliente con el mismo nombre
              alert('Ya existe un cliente con el mismo nombre. Debe ingresar un nombre distinto');
            }else{
             if ($("#detalle_cliente_add")[0].checkValidity())
                    {
                $("#detalle_cliente_add").submit()
                    }else {
                      alert('Ingrese un nombre de cliente');
                    }
            }
          }).fail(function(xhr, textStatus, errorThrown) {
              console.log(xhr.responseText);
          }).always(function(){
              // console.log('The ajax call ended.');
          });

        }