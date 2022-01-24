
/* -- FUNCIONES NUEVA VENTA -- */

/* Para que deje scrollear el modal anterior!! */
$(document).on('hidden.bs.modal', '.modal', function () {
    $('.modal:visible').length && $(document.body).addClass('modal-open');
});

function getProveedor(id) {
  $.ajax({
      url: 'ajax_getProveedor.php',
      type: 'POST',
      data: {id : id},
      dataType: 'html'
  }).done(function(data){
    $('#modal-edit-proveedor').html('');
      $('#modal-edit-proveedor').html(data);
      $('#modal-edit-proveedor').modal('show');
  }).fail(function(xhr, textStatus, errorThrown) {
      console.log(xhr.responseText);
  }).always(function(){
      // console.log('The ajax call ended.');
  });
}

function getTransferencia(id) {
  $.ajax({
      url: 'ajax_getTransferencia.php',
      type: 'POST',
      data: {id : id},
      dataType: 'html'
  }).done(function(data){
    $('#modal-edit-transferencia').html('');
    $('#modal-edit-transferencia').html(data);
    $('#modal-edit-transferencia').modal('show');
  }).fail(function(xhr, textStatus, errorThrown) {
      console.log(xhr.responseText);
  }).always(function(){
      // console.log('The ajax call ended.');
  });
}

function getTransferenciasService() {

  $.ajax({
      url: 'service_getTransferencias.php',
      type: 'POST',
      dataType: 'json'
  }).done(function(data){
      notificacion('Transferencias recibidas','Las transferencias recibidas se encuentran actualizadas.','<icon class=\"fa fa-check\">','success',5000);
  }).fail(function(xhr, textStatus, errorThrown) {
      notificacion('Transferencias recibidas','El puesto no pudo conectarse con el sistema matriz. <br> Intente de nuevo más tarde','<icon class=\"fa fa-warning\">','warning',5000);
  }).always(function(){
    getTablas();
      // console.log('The ajax call ended.');
  });
}

function getCambiosDeEstado() {

  $.ajax({
      url: 'service_getNuevoEstado.php',
      type: 'POST',
      dataType: 'html'
  }).done(function(data){
      notificacion('Transferencias enviadas','Las transferencias enviadas se encuentran actualizadas.','<icon class=\"fa fa-check\">','success',5000);
  }).fail(function(xhr, textStatus, errorThrown) {
      notificacion('Transferencias enviadas','El puesto no pudo conectarse con el sistema matriz. <br> Intente de nuevo más tarde','<icon class=\"fa fa-warning\">','warning',5000);
  }).always(function(){
      // console.log('The ajax call ended.');
  });
}

function enviarTransferenciasPendientes() {

  $.ajax({
      url: 'service_enviarPendientes.php',
      type: 'POST',
      dataType: 'json'
  }).done(function(data){
      notificacion('Transferencias pendientes','Las transferencias pendientes se encuentran actualizadas.','<icon class=\"fa fa-check\">','success',5000);
  }).fail(function(xhr, textStatus, errorThrown) {
      notificacion('Transferencias pendientes','El puesto no pudo conectarse con el sistema matriz. <br> Intente de nuevo más tarde','<icon class=\"fa fa-warning\">','warning',5000);
  }).always(function(){
      // console.log('The ajax call ended.');
  });
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

function getTablas() {
  $('#contenedor-esperando').css('display','block');
  $('#tablas').html('');

  $.ajax({
      url: 'ajax_getTablas.php',
      type: 'POST',
      data: {},
      dataType: 'html'
  }).done(function(data){
    //console.log(data);
    $('#tablas').css('display','none');
    $('#contenedor-esperando').delay( 1500 ).fadeOut( 400 );
    $('#tablas').html(data);
    $('#tablas').delay( 2000 ).fadeIn( 900 );
    
  }).fail(function(xhr, textStatus, errorThrown) {
      console.log(xhr.responseText);
  }).always(function(){
      // console.log('The ajax call ended.');
  });
}

function eliminarProveedor(){
  if(confirm('Desea eliminar el proveedor de forma permanente?')){
    $('#eliminar_proveedor').submit();
  }
}

function cambiarCosto(){
  if($('#input_tipo').val() == 1){
    $('#add_input_cantidad').removeAttr('max');
    $('#id_carga').hide();
    $('#id_descarga').show();
  }else{
    $('#id_carga').show();
    $('#id_descarga').hide();
  }
}

function traerStock(id,i,calibre,lote) {
  $.ajax({
      url: 'ajax_getStock.php',
      type: 'POST',
      data: {id : id,
            calibre : calibre,
            lote : lote},
      dataType: 'json'
  }).done(function(data){
    //console.log(data);
  var identific ="#cantidad_"+i;
      $("#cantidad_"+i).attr('max',data);
      $("#stock_"+i).text(data);
  }).fail(function(xhr, textStatus, errorThrown) {
      console.log(xhr.responseText);
  }).always(function(){
      // console.log('The ajax call ended.');
  });
}
var productosCargados = [];
function cargar_producto_lista (prod_modelo, prod_id,calibre,costou,lote) {
 
    // evitar Repetidos
    var codigounico=(lote);
    if (productosCargados.includes(codigounico)){
       $("#input_productos").val('');
            alert('Ese producto ya ha sido cargado');

        return false;
    }
     productosCargados.push(codigounico);
    // Fin  evitar Repetidos

  var i = parseInt($('#cant_prod').val());

  var newRowContent = '<tr id="'+codigounico+'">';
  
  newRowContent += '<td>'+prod_modelo+' <input type="hidden" id="prod_id_'+i+'" name="prod['+i+'][id]" value="'+prod_id+'"></td>';
  newRowContent += '<input type="hidden" id="prod_calibre_'+i+'" name="prod['+i+'][calibre]" value="'+calibre+'">';
  // newRowContent += '<input type="hidden" id="prod_costou_'+i+'" name="prod['+i+'][costou]" value="'+costou+'">';
  newRowContent += '<input type="hidden" id="prod_lote_'+i+'" name="prod['+i+'][lote]" value="'+lote+'">';
  newRowContent += '<input type="hidden" id="prod_detalle_'+i+'" name="prod['+i+'][detalle_prod]" value="'+prod_modelo+'">';
  newRowContent += '<td><span id="stock_'+i+'"> <span></td>';
  // newRowContent += '<td>$<span>'+costou+'</span></td>';  
  newRowContent += '<td><input type="number" required style="width: 100%;" step="1" min="1" id="cantidad_'+i+'" name="prod['+i+'][cantidad]" oninput="modificar('+i+');"></td>';
  newRowContent += '<td><input type="number" required step="0.10" min="0" id="precio_carga_unitaria_'+i+'" name="prod['+i+'][precio_carga_unitaria]" oninput="modificar('+i+');"></td>';
  // newRowContent += '<td><input type="number" required step="0.10" min="0" id="precio_flete_unitario_'+i+'" name="prod['+i+'][precio_flete_unitario]" oninput="modificar('+i+');"></td>';
  //newRowContent += '<td><input type="text" style="width: 90%;" id="observacion_carga_unitaria_'+i+'"  name="prod['+i+'][observacion_carga_unitaria]" class="observ_parc"><a style="margin-left:10px" href="#" class="borrar"><i class="fa fa-times"></i></a></td>';
  newRowContent += '<td><input type="number" step="0.10" min="0" readonly id="precio_total_'+i+'" class="precio_parc"><a href="#" class="borrar"><i class="fa fa-times"></i></a></td>';
  newRowContent += '</tr>';
  traerStock(prod_id, i,calibre,lote);
  $("#tabla_productos tbody").append(newRowContent);
  $("#input_productos").val('');

  calcular_total();

  $('#cant_prod').val(i+1);
  

}

//Borra la fila al hacer click en X
$(document).on('click', '.borrar', function (event) {
  event.preventDefault();

   var codigoAsacar = $(this).closest('tr').prop('id');
   var index = productosCargados.indexOf(codigoAsacar); // Busco la posicion en el arreglo
    if (index > -1) {
     productosCargados.splice(index, 1); //elimino del arreglo
    }

  $(this).closest('tr').remove();
  calcular_total();
});

// Actualiza total de totales
function calcular_total() {
  var sum = 0;
  $('.precio_parc').each(function() {
    $val = $(this).val();
    if ($val != ''){ sum += parseFloat($val);}
  });
  $('#costo_final_total').val(sum);
}

// Actualiza subtotales y totales
function modificar(i) {
  
    var cantidad = parseFloat($('#cantidad_'+i+'').val());
    var carga_unitaria = parseFloat($('#precio_carga_unitaria_'+i+'').val());
    // var flete_unitario = parseFloat($('#precio_flete_unitario_'+i+'').val());

    var precio_parcial_carga = (cantidad * carga_unitaria);
    // var precio_parcial_flete = (cantidad * flete_unitario);
    var precio_parcial = precio_parcial_carga;

    $('#precio_total_'+i+'').val(precio_parcial);

    calcular_total();
}
// para borar la fila cuando clickea X
$(document).on('click', '.borrar', function (event) {
  event.preventDefault();
  $(this).closest('tr').remove();
  calcular_total();
});

function abrirModalDiferencia(transf_id){
  $.ajax({
      url: 'ajax_getDiferencia.php',
      type: 'POST',
      data: {transf_id:transf_id},
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


function setearDevolucion(){
  var id = $('#combo_prod_dev').val();
  $('#concepto_transf_detalle_id').val(id);
  $('#input_obs_devolver').val($("#combo_prod_dev option:selected").text());
  $('.prods_dev').hide();
  $('#det_'+id).show();
  $('#input_cantidad_dev').val(0);
  $('#saldo_final_dev').val(0);
  $('#input_cantidad_dev').attr('max',$('#cant_'+id).val());
  $('.mostrar-devolucion').show();
}