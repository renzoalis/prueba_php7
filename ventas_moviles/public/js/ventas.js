
function cancelarModal(){

$("#modal-categorias").modal("hide");

}

function modalTipo(){
  $.ajax({
      url: 'ajax_getCantTipos.php',
      type: 'POST',
      dataType: 'JSON'
  }).done(function(data){
      if(data.unSoloTipo){
        modalCategorias(data.tipo_id, data.tipo_nombre, data.unSoloTipo);
      }else{
        getTipos();

        $('#modal-tipos').html('');
        $('#modal-tipos').html(data);
        $('#modal-tipos').modal('show');
      }
  }).fail(function(xhr, textStatus, errorThrown) {
      console.log(xhr.responseText);
  }).always(function(){
      // console.log('The ajax call ended.');
  });
}


function getTipos(){
  $.ajax({
      url: 'ajax_getTipos.php',
      type: 'POST',
      dataType: 'HTML'
  }).done(function(data){
    $('#modal-tipos').html('');
    $('#modal-tipos').html(data);
    $('#modal-tipos').modal('show');
  }).fail(function(xhr, textStatus, errorThrown) {
      console.log(xhr.responseText);
  }).always(function(){
      // console.log('The ajax call ended.');
  });
}

function volverTipo(){

  $('#modal-categorias').modal('hide');
  $('#modal-tipos').modal('show');

}

function modalCategorias(tipo_id,tipo_nombre,unico){
  $('#modal-tipos').modal('hide');
  $('#aux_tipo').val(tipo_nombre);
  $.ajax({
      url: 'ajax_getCategorias.php',
      type: 'POST',
      data: {tipo_id : tipo_id,
            tipo_nombre: tipo_nombre,
            unSoloTipo: unico},
      dataType: 'html'
  }).done(function(data){
      $('#modal-categorias').html('');
      $('#modal-categorias').html(data);
      $('#modal-categorias').modal('show'); 
  }).fail(function(xhr, textStatus, errorThrown) {
      console.log(xhr.responseText);
  }).always(function(){
      // console.log('The ajax call ended.');
  });
}

function volverCategoria(){

  $('#modal-productos').modal('hide');
  $('#modal-categorias').modal('show');

}

function modalProductos(cat_id,cat_nombre){
var tipo_cat_nombre = $('#breadcrumb-cat').html()+' > '+cat_nombre;
$('#aux_categoria').val(cat_nombre);

$('#modal-categorias').modal('hide');

  $.ajax({
      url: 'ajax_getProductos.php',
      type: 'POST',
      data: {cat_id : cat_id,
            tipo_cat_nombre : tipo_cat_nombre},
      dataType: 'html'
  }).done(function(data){
      $('#modal-productos').html('');
      $('#modal-productos').html(data);
      $('#modal-productos').modal('show');
  }).fail(function(xhr, textStatus, errorThrown) {
      console.log(xhr.responseText);
  }).always(function(){
      // console.log('The ajax call ended.');
  });
}

function volverProducto(){

  $('#modal-calibres').modal('hide');
  $('#modal-productos').modal('show');

}

function modalCalibres(prod_id,prod_nombre){
var calibre_nombre = $('#breadcrumb-prod').html()+' > '+prod_nombre;
$('#aux_producto_id').val(prod_id);
$('#aux_producto_nombre').val(prod_nombre);
$('#modal-productos').modal('hide');

  $.ajax({
      url: 'ajax_getCalibres.php',
      type: 'POST',
      data: {prod_id : prod_id,
            calibre_nombre : calibre_nombre},
      dataType: 'html'
  }).done(function(data){
    if(data.length < 10){
      $('#aux_sincalibre').val(1);
      $('#calibre_desc').val(calibre_nombre);
      modalLote("S/C",data,prod_id);
    }else{
      $('#modal-productos').modal('hide');
      $('#modal-calibres').html('');
      $('#modal-calibres').html(data);
      $('#modal-calibres').modal('show');
      $('#aux_sincalibre').val(0);
    }
  }).fail(function(xhr, textStatus, errorThrown) {
      console.log(xhr.responseText);
  }).always(function(){
      // console.log('The ajax call ended.');
  });
}

function volverCalibre(){

  $('#modal-lotes').modal('hide'); 
  if($('#aux_sincalibre').val() == 1){
    $('#modal-productos').modal('show');
  } else {
    $('#modal-calibres').modal('show');
  }
  
}

function modalLote(calibre_desc,calibre_stock,prod_id){     //recibo por parametro el ps_calibre y el stock para la venta
if(calibre_desc == "S/C"){
  var lote_nombre = $('#calibre_desc').val()+' > '+calibre_desc;
}else{
  var lote_nombre = $('#breadcrumb-calibre').html()+' > '+calibre_desc;
}
$('#aux_calibre').val(calibre_desc);

$('#modal-calibres').modal('hide');

  $.ajax({
      url: 'ajax_getLotes.php',
      type: 'POST',
      data: {ps_calibre : calibre_desc,
             prod_id : prod_id,
             lote_nombre : lote_nombre},
      dataType: 'html'
  }).done(function(data){
      $('#modal-lotes').html('');
      $('#modal-lotes').html(data);
      $('#modal-lotes').modal('show');
  }).fail(function(xhr, textStatus, errorThrown) {
      console.log(xhr.responseText);
  }).always(function(){
      // console.log('The ajax call ended.');
  });
}

function volverLote(){

  $('#modal-lotes').modal('show'); 
  $('#cantidad').val();
  $('#modal-cantidad').modal('hide');
  
}

$('#modal-cant').on('shown.bs.modal', function () {
  $('#cantidad').focus();
})  

function modalCantidad(lote_desc,calibre_stock,nombreproducto,calibre,lote,prod_id){
  var $id_producto = prod_id;
  var $yaEstaCargado = false;
  $('#aux_lote').val(lote_desc);


    $('.listaProductos').each(function() {
      $id = $(this).val();
      $lote = $(this).text();
      console.log($lote);
      if ($id == $id_producto && lote == $lote ){ $yaEstaCargado = true;}
    });
    if ($yaEstaCargado) {alert('El producto ya esta cargado')}else {
        $('#leyenda_stock').html(" * Quedan "+calibre_stock+" unidades disponibles");
        $('#aux_lote').val(lote);
        $('#aux_calibre').val(calibre);
        $('#aux_producto_nombre').val(nombreproducto);
        $('#aux_producto_id').val(prod_id);
        $('#cantidad').val('');
        $('#cantidad').attr('max',calibre_stock);
        $('#stock_max').val(calibre_stock);
        $('#modal-productos').modal('hide');
        $('#modal-cant').modal('show');
        var descrip = $('#breadcrumb-prod').html()+' > '+lote_desc;
        $('#breadcrumb-cantidad').html(descrip);
    }
    

}

function volverCantidad(){

  $('#modal-cant').modal('show'); 
  $('#cantidad').val();
  $('#modal-valor').modal('hide');
  
}

function modalValor(){
   if(parseInt($('#cantidad').val()) > parseInt($('#cantidad').attr('max'))){
    alert('No hay stock');
    return false;
  }
  if($('#cantidad').val() == 0){
    alert('Debe ingresar un valor');
    return false;
  }else{
    $('#aux_prod_cant').val($('#cantidad').val());
    $('#modal-cant').modal('hide');
    $('#modal-valor').modal('show');
    $('#valor').val('');
    
    setTimeout(function(){
    $('#valor').focus();
    },500);
  }
}

function modificar_valor(i){
  var cant = Math.trunc($('#producto_'+i+'_cant').val());
  $('#producto_'+i+'_cant').val(cant);
  var valor = $('#producto_'+i+'_val').val();

  var mult = cant * valor; 
  var max = parseInt($('#producto_'+i+'_cant').attr('max'));

  if(mult > 0 && cant <= max){
    var total = cant * valor;
    $('#producto_'+i+'_tot').val(total);
  } else {
    alert("Ingrese un valor correcto"); 
    if(cant > max) {
      $('#producto_'+i+'_cant').val(max);
    }
  }


}

function finalizarVenta(){
  if($('#valor').val() == 0){
    alert('Debe ingresar un valor');
    return false;
  }else{
    $('#aux_prod_val').val($('#valor').val());


  var i = parseInt($("#cant_items").val()) + 1;
  var newRowContent = '<tr class="linea-item" id="linea_'+i+'">';
  newRowContent += '<input type="hidden" class="listaProductos" name="producto['+i+'][id]" id="producto_'+i+'_id">';
  newRowContent += '<input type="hidden" name="producto['+i+'][calibre]" id="producto_'+i+'_calibre">';
  newRowContent += '<td class="item-venta"><input onchange="modificar_valor('+i+');" class="form-control fila soloNumeros" min="1" step="1" type="number" name="producto['+i+'][cant]" id="producto_'+i+'_cant" ></td>';
  newRowContent += '<td class="item-venta"><input class="form-control fila" type="text" name="producto['+i+'][nombre] "  id="producto_'+i+'_nombre" ></td>';
  newRowContent += '<td class="item-venta"><input class="form-control fila" readonly type="text" name="producto['+i+'][lote]" id="producto_'+i+'_lote"></td>';
  newRowContent += '<td class="item-venta"><input onchange="modificar_valor('+i+');" required class="form-control fila" type="number" name="producto['+i+'][val]" min="1" id="producto_'+i+'_val"  ></td>';
  newRowContent += '<td class="item-venta"><span style="display:inline;">$ </span><input style="display:inline; width:80%;" required class="form-control fila" type="number" step="0.1" readonly name="producto['+i+'][tot]" id="producto_'+i+'_tot"  ></td>';
  newRowContent += '<td class="item-venta"><a class="btn btn-danger" onclick="eliminar('+i+')"><i class="fa fa-times"></i></a></td>';
  newRowContent += '</tr>';


  $("#tabla_items tbody").append(newRowContent);
  $("#cant_items").val(i);

    $('#producto_'+$("#cant_items").val()+'_tipo').val($('#aux_tipo').val());
    $('#producto_'+$("#cant_items").val()+'_categoria').val($('#aux_categoria').val());
    $('#producto_'+$("#cant_items").val()+'_id').val($('#aux_producto_id').val());
    $('#producto_'+$("#cant_items").val()+'_id').text($('#aux_lote').val());
    $('#producto_'+$("#cant_items").val()+'_nombre').val($('#aux_producto_nombre').val());
    $('#producto_'+$("#cant_items").val()+'_calibre').val($('#aux_calibre').val());
    $('#producto_'+$("#cant_items").val()+'_lote').val($('#aux_lote').val());
    $('#producto_'+$("#cant_items").val()+'_cant').val($('#aux_prod_cant').val());
    $('#producto_'+$("#cant_items").val()+'_val').val($('#aux_prod_val').val());
    $('#producto_'+$("#cant_items").val()+'_cant').attr('max',$('#stock_max').val());
  
  $('#modal-valor').modal('hide');
  actualizarTotal(i);
  modalTipo();
  }
}


function eliminar(i) {
    $('#linea_'+i).remove();
    $('#cant_items').val() = $('#cant_items').val() -1;
    return false;
}

function actualizarTotal(i,max,input) { 
  // si tiene max e input viene de onchage de cant o precio
  if (max && input){
  var cantidadactual = input.value;
  var cantidadAnterior = input.getAttribute("guardacant");
  if (cantidadactual > max){
     alert('La cantidad ingresada no debe superar al stock disponible ('+max+')');
     $('#producto_'+i+'_cant').val(cantidadAnterior);
  }else {
    input.setAttribute("guardacant", cantidadactual);
    $('#producto_'+i+'_tot').val(($('#producto_'+i+'_cant').val() * $('#producto_'+i+'_val').val()).toFixed(2));
  }
  // sino viene de cuando se carga un nuevo producto
} else {
$('#producto_'+i+'_tot').val(($('#producto_'+i+'_cant').val() * $('#producto_'+i+'_val').val()).toFixed(2));
}

}

function validarCliente(){
    if ($("#clientevalor").val() == "") {
      alert('Por favor ingrese un cliente');
      return false;
    }
    $("#cliente").val($("#clientevalor").val());
    $("#productoshidden").val($("#cant_items").val());
    $("#totalhidden").val();
    $("#detalle_venta").submit()
}

$('#cantidad').keypress(function() {
  if(event.keyCode == 13){
    modalValor();
  }
});

$('#valor').keypress(function() {
  if(event.keyCode == 13){
    finalizarVenta();
  }
});

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

$('#combo_cli').on('change',function(){
  id = $('#combo_cli').val();
  getEstadoCC(id);
})

/* Alta de cliente con Ajax */
function ajax_guardarCliente() {

  var data = JSON.stringify( $('#detalle_cliente_add').serializeArray() ); 
  
    $.ajax({
      url: 'ajax_addCliente.php',
      type: 'POST',
      data: {data : data},
      dataType: 'json'
  }).done(function(data){
      var newOption = new Option(data.nombre, data.id, false, false);
      $('#combo_cli').append(newOption).trigger('change');
      $('#combo_cli').val(data.id);
      getEstadoCC(data.id);
      $('#myModalClienteAdd').modal('hide');
  }).fail(function(xhr, textStatus, errorThrown) {
      console.log(xhr.responseText);
  });
}
/* FINALIZAR VENTA */
function cerrarVenta() {
  var cliente = $("#combo_cli").val();
  if ($("#combo_cli").val() == "") {
    alert("Debe seleccionar un cliente");
    return false;
  }

  if ($("#cant_items").val() == 0) {
    alert("La venta no tiene productos.");
    return false;
  }

  $("#cliente").val($("#combo_cli").val());
  $("#productoshidden").val($("#cant_items").val());
  $("#totalhidden").val();
  $("#detalle_venta").submit();
}

function modalConciliarStock(id) {
  $('#div-cerrar-caja').html('');

  $.ajax({
      url: '../caja/ajax_getDatosConciliacion.php',
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


                