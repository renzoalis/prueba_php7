/* CUSTOM JS DE INTRANET-GAM */
/* 20-05-2017 */

/* Alta de proveedor con Ajax */
function ajax_guardarProveedor() {

  var data = JSON.stringify( $('#detalle_cliente_add').serializeArray() ); 
  console.log(data);
  
    $.ajax({
      url: 'ajax_addProveedor.php',
      type: 'POST',
      data: {data : data},
      dataType: 'json'
  }).done(function(data){
      var newOption = new Option(data.nombre, data.id, false, false);
      $('#combo_prov').append(newOption).trigger('change');
      $('#combo_prov').val(data.id);
      $('#myModalProveedorAdd').modal('hide');
  }).fail(function(xhr, textStatus, errorThrown) {
      console.log(xhr.responseText);
  });
}
function ajax_guardarTransportista() {

  var data = JSON.stringify( $('#detalle_transportista_add').serializeArray() ); 
  console.log(data);
  
    $.ajax({
      url: 'ajax_addTransportista.php',
      type: 'POST',
      data: {data : data},
      dataType: 'json'
  }).done(function(data){
      var newOption = new Option(data.nombre, data.id, false, false);
      $('#combo_transp').append(newOption).trigger('change');
      $('#combo_transp').val(data.id);
      $('#myModalTransportistaAdd').modal('hide');
  }).fail(function(xhr, textStatus, errorThrown) {
      console.log(xhr.responseText);
  });
}
/* Alta de TRANSPORTISTA con Ajax */
function guardarTransportista() {
  var transp = $('#combo_transp_e').val();
  // console.log(transp);
  if(transp){
    $('#upd_transp').submit();
  }
}

/* Para que deje scrollear el modal anterior!! */
$(document).on('hidden.bs.modal', '.modal', function () {
    $('.modal:visible').length && $(document.body).addClass('modal-open');
});

function calcular_total() {
  var sum = 0;
  $('.precio_parc').each(function() {
    $val = $(this).val();
    if ($val != ''){ sum += parseFloat($val);}
  });
  $('#saldo_final_total').val(sum);
}

function modificar(i) {
  // Actualizo factura
    var peso_total = parseFloat($('#cantidad_'+i+'').val());
    var precio_kg = parseFloat($('#precio_kg_'+i+'').val());

    var precio_parcial = (peso_total * precio_kg);

    $('#precio_total_'+i+'').val(precio_parcial);

    calcular_total();
}

function cargar_producto_lista (prod_modelo, prod_id) {

  var i = parseInt($('#cant_prod').val());

  var newRowContent = '<tr>';
  newRowContent += '<td>'+prod_modelo+' <input type="hidden" id="prod_id_'+i+'" name="prod['+i+'][id]" value="'+prod_id+'"></td>';
  newRowContent += '<td><input type="text" step="1" min="1" producto_id="'+prod_id+'"  id="calibre_'+i+'" class="det_venta calibreclase" name="prod['+i+'][calibre]" onchange="validacionCalibreIguales('+prod_id+',$(this).val())"></td>';
  newRowContent += '<td><input type="number" step="1" min="1" id="cantidad_'+i+'" class="precio_parc" name="prod['+i+'][cantidad]" required oninput="modificar('+i+');"></td>';
  newRowContent += '<td><a href="#" class="borrar"><i class="fa fa-times"></i></a></td>';
  
  // newRowContent += '<td><input type="number" step="0.10" min="0" id="precio_kg_'+i+'" name="prod['+i+'][precio_kg]" oninput="modificar('+i+');"></td>';
  // newRowContent += '<td><input type="number" step="0.10" min="0" disabled="disabled" id="precio_total_'+i+'" class="precio_parc"> <a href="#" class="borrar"><i class="fa fa-times"></i></a></td>';
  newRowContent += '</tr>';

  $("#tabla_productos tbody").append(newRowContent);
  $("#combo_prod").val('');
  
  calcular_total();

  $('#cant_prod').val(i+1);
}

$(document).on('click', '.borrar', function (event) {
  event.preventDefault();
  $(this).closest('tr').remove();
  calcular_total();
});

/* Para que deje scrollear el modal anterior!! */
$(document).on('hidden.bs.modal', '.modal', function () {
    $('.modal:visible').length && $(document.body).addClass('modal-open');
});

function getCompra(id) {
  $.ajax({
      url: 'ajax_getCompra.php',
      type: 'POST',
      data: {id : id},
      dataType: 'html'
  }).done(function(data){
    $('#modal-edit-compra').html('');
    $('#modal-edit-compra').html(data);
    $('#modal-edit-compra').modal('show');
  }).fail(function(xhr, textStatus, errorThrown) {
      console.log(xhr.responseText);
  }).always(function(){
      // console.log('The ajax call ended.');
  });
}
 $('#detalle_compra').on('submit', function (event) {
    event.preventDefault();
    var continuar = true;

    // valido que se ingrese la cantidad y el costo en todos los productos 
    // $('.precio_parc').each(function() { 
    //     var precio_parcial = $(this).val();
    //     if (precio_parcial == "" || precio_parcial == 0) {
    //        alert('Por favor ingrese precio y/o cantidad de todos sus productos');
    //       continuar = false;
    //       return false;
    //     };
    //  }); // fin each

    // VALIDO QUE INGRESE UN PRODUCTO A LA COMPRA
    if ( !$(".precio_parc")[0]){// si no hay ningun elemento con esa clase, es que no hay productos cargados
               alert('Ingrese al menos un producto a la compra');
                continuar = false;
                return false;
      } 

    // valido que no haya 2 productos iguales 
      $('.calibreclase').each(function() {
        if (continuar == false) { 
          return false;  } else {
        var hay = 0;
        var calibre = $(this).val();
        var id = $(this).attr('producto_id');
         $('.calibreclase').each(function() { 
             var calibreActual = $(this).val();
             var idActual = $(this).attr('producto_id');
             if (calibre == calibreActual && id == idActual) {
                hay = hay + 1;
               if (hay != 1) {
                alert('Hay dos productos iguales cargados, por favor revise los datos');
                continuar = false;
                return false;
               }
             };
          }); // fin primer each
       }; // fin if
       }); // fin segundo each
    if($('#detalle_compra')[0].checkValidity() && continuar){
        var respuesta = confirm("Por favor revise los datos de la compra.  \nSi son correctos click en OK para guardar.");
   
    if (respuesta == true) {
       $('#detalle_compra')[0].submit();
            } else {
              return false;
            }
      } else {
        return false;
      }
    
    
});

function editarCompra(){
  $(".form-select-prov").prop("disabled", false);
  $(".form-select-transp").prop("disabled", false);
  $(".editable").prop("disabled", false);
  $("#guardar-cambios").css('display','inline');
  $("#nuevo_prov").css('display','inline');
  $("#editar_modal").css('display','none');
}

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

});

function validarYsubmitear() {

  var prov = $('#combo_prov_pago').val();
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
      }

      if(!monto) {
        alert('Debe ingresar un monto válido');
        return false;
      } else {
        sigue = sigue * 1;
      }
    }

  // Valido cliente
    if(!prov) {
      alert('Debe ingresar un proveedor existente.');
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
function actualizarMonto() {
  var costou =  $('#input_costo_unitario').val();
  var cantidad =  $('#input_cantidad').val();
  total = costou*cantidad;

  $('#input_monto').val(total);
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


$('#myModalPago').on('shown.bs.modal', function () {
  id = $('#combo_prov_pago').val();
  tipo=1;
  getEstadoCC(id,tipo);
})  

// Limpio las validaciones cuando cierro el modal
$("#myModalPago").on("hidden.bs.modal", function () {
    $('.input-pagos').attr('disabled',false);
});


// VER
function validarConcepto() {
  var tipo = $('#combo_tipo').val();

  var transp = $('#concepto_transp_id').val();
  if (transp == "0" && (tipo == 2 || tipo == 3)) {
    alert('No existe transportista asociado a la compra.');
    return false;
  } else {
    $('#form_concepto').submit();
  }

  var cant = $('#input_cantidad_dev').val();
  if (cant < 1 && tipo == 8) {
    alert('Ingrese una cantidad mayor a cero.');
    return false;
  } else {
    $('#form_concepto').submit();
  }
}

function validarFlete() {
  var total = $('#total_flete').val();

  $('#form_concepto').submit();
  
}
function validarDescarga() {
  var total = $('#saldo_final_dev').val();

  if (total == 0) {
    if(confirm('Está seguro de ingresar costo cero?.')){
      $('#form_concepto').submit();
    }else{
      return false;
    }
  } else {
    $('#form_concepto').submit();
  }
}

$('.soloNumeros').keypress(function (tecla) {
  if (tecla.charCode < 48 || tecla.charCode > 57) return false;
});

function abrirModalDescarga(id) {
  $.ajax({
      url: 'ajax_getConceptoDescarga.php',
      type: 'POST',
      data: {compra_id:id
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

function abrirModalCostoMercaderia(id) {
  $.ajax({
      url: 'ajax_getCostoMercaderia.php',
      type: 'POST',
      data: {compra_id:id
            },
      dataType: 'html'
  }).done(function(data){
    $('#form_concepto').html('');
    $('#form_concepto').html(data);
    $('#myModalCostoMercaderia').modal('show');
  }).fail(function(xhr, textStatus, errorThrown) {
      console.log(xhr.responseText);
  }).always(function(){
      // console.log('The ajax call ended.');
  });
}

function setearDescarga(){
  var id = $('#combo_prod_dev').val();
  $('#concepto_compra_detalle_id').val(id);
  $('#input_obs_devolver').val($("#combo_prod_dev option:selected").text());
  $('.prods_dev').hide();
  $('#det_'+id).show();
  $('#saldo_final_dev').val(0);
  $('.mostrar-devolucion').show();
}

function actualizarMontoDescarga(id){
  var costo_u = $('#costo_'+id).val();
  var subtotal = $('#cant_'+id).val() * costo_u;
  $('#guardasubtotal_'+id).val(subtotal);
  $('#subtotal_descarga_'+id).html(subtotal);
  calcular_total_descarga();
}


function actualizarMontoCostoMercaderia(id){
  var costo_u = $('#costo_'+id).val();
  var subtotal = $('#cant_'+id).val() * costo_u;
  $('#guardasubtotal_'+id).val(subtotal);
  $('#subtotal_mercaderia_'+id).html(subtotal);
  calcular_total_mercaderia();
}



function calcular_total_descarga() {
  var sum = 0;
  $('.subtotaldescarga').each(function() {
    $val = $(this).val();
    if ($val != ''){ sum += parseFloat($val);}
  });
  $('#saldo_final_dev').val(sum);
}

function calcular_total_mercaderia() {
  var sum = 0;
  $('.subtotalcostomercaderia').each(function() {
    $val = $(this).val();
    if ($val != ''){ sum += parseFloat($val);}
  });
  $('#saldo_final_dev').val(sum);
}


function inputsTipoPago() {
  $id = $('#combo_prov_pago').val();
 switch ($('#combo_tipo_de_pago').val()) {
     case '1':
     getEstadoCC($id);
     $('#combo_prov_pago').show();
     $('#cargaspan').hide();
     $('#descargaspan').hide();
      break;
      case '2':
      $('#deuda_prov').html('');
    $('#combo_prov_pago').hide();
    $('#cargaspan').show();
    $('#descargaspan').hide();
       break;
     case '3':
     $('#deuda_prov').html('');
    $('#combo_prov_pago').hide();
    $('#cargaspan').hide();
    $('#descargaspan').show();
      break;
     default:
  }

}

function setearDevolucion(){
  var id = $('#combo_prod_dev').val();
  $('#concepto_compra_detalle_id').val(id);
  $('#input_obs_devolver').val($("#combo_prod_dev option:selected").text());
  $('.prods_dev').hide();
  $('#det_'+id).show();
  $('#input_cantidad_dev').val(0);
  $('#saldo_final_dev').val(0);
  $('#input_cantidad_dev').attr('max',$('#cant_'+id).val());
  $('.mostrar-devolucion').show();
}

function abrirModalDevolucion(compra_id){
  $.ajax({
      url: 'ajax_getDevolucionMercaderia.php',
      type: 'POST',
      data: {compra_id:compra_id
            },
      dataType: 'html'
  }).done(function(data){
    $('#form_concepto').html('');
    $('#form_concepto').html(data);
    $('#myModalDev').modal('show');
    $('#div-devolucion').show();
  }).fail(function(xhr, textStatus, errorThrown) {
      console.log(xhr.responseText);
  }).always(function(){
      // console.log('The ajax call ended.');
  });
}

function actualizarMontoDevolucion() {
  // Actualizo factura 
    var det_id = parseFloat($('#concepto_compra_detalle_id').val());
    var costo = parseFloat($('#val_'+det_id).val());
    var cantidad = parseFloat($('#input_cantidad_dev').val());
    var precio_parcial = (parseFloat(costo) * parseFloat(cantidad));
    if(cantidad > $('#input_cantidad_dev').attr('max')){
      $('#input_cantidad_dev').val($('#input_cantidad_dev').attr('max'));
      precio_parcial = parseFloat($('#input_cantidad_dev').val()) * parseFloat(costo);
    }
    $('#saldo_final_dev').val(precio_parcial);
}

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

function abrirModalFlete(id) {
  $.ajax({
      url: 'ajax_getConceptoFlete.php',
      type: 'POST',
      data: {compra_id:id
            },
      dataType: 'html'
  }).done(function(data){
    $('#form_concepto').html('');
    $('#form_concepto').html(data);
    $('#myModalFlete').modal('show');
  }).fail(function(xhr, textStatus, errorThrown) {
      console.log(xhr.responseText);
  }).always(function(){
      // console.log('The ajax call ended.');
  });
}

function setearFlete(){
  var id = $('#combo_prod_dev').val();
  $('#concepto_compra_detalle_id').val(id);
  $('#input_obs_flete').val($("#combo_prod_dev option:selected").text());
  $('.prods_dev').hide();
  $('#det_'+id).show();
  $('#saldo_final_dev').val(0);
  $('.mostrar-devolucion').show();
}

function actualizarMontoFlete(id){
  //var id = $('#combo_prod_dev').val();
  var costo_u = $('#costo_'+id).val();
  var subtotal = $('#cant_'+id).val() * costo_u;
  $('#subtotal_flete_'+id).html(subtotal);
  $('#guardasubtotales_'+id).val(subtotal);
  calcular_total_flete();
}

function calcular_total_flete() {
  var sum = 0;
  $('.subtotalflete').each(function() {
    $val = $(this).val();
    if ($val != ''){ sum += parseFloat($val);}
  });
  $('#total_flete').val(sum);
}

function habilitarTransp(txt, id) {
  if(id){
    $('#boton-transp').show();
  } else {
    $('#boton-transp').hide();
  }
}

function guardarObs() {
  $('#modificar_obs').submit();
}

function validacionCalibreIguales(id_producto, calibreCambio) {
hay = 0;
// recorro los calibres ya cargados
$('.calibreclase').each(function() {
    calibre = $(this).val();
    id = $(this).attr('producto_id');
// solo debe encontrar uno que concida con id y calibre
    if (calibre == calibreCambio & id == id_producto){
      hay = hay + 1;
    }

  });
// si hay dos emite la alerta
if (hay != 1){
  alert('Ese producto con ese calibre ya fue cargado');
}

}

//notificaciones 
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