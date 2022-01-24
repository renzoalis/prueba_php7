
/* -- FUNCIONES NUEVA VENTA -- */

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
      $('#myModalClienteAdd').modal('hide');
  }).fail(function(xhr, textStatus, errorThrown) {
      console.log(xhr.responseText);
  });
}

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

function getVenta(id) {
  $.ajax({
      url: 'ajax_getVenta.php',
      type: 'POST',
      data: {id : id},
      dataType: 'html'
  }).done(function(data){
    $('#modal-edit-venta').html('');
      $('#modal-edit-venta').html(data);
      $('#modal-edit-venta').modal('show');
  }).fail(function(xhr, textStatus, errorThrown) {
      console.log(xhr.responseText);
  }).always(function(){
      // console.log('The ajax call ended.');
  });
}

document.getElementById('detalle_venta').addEventListener('submit', function(evt){
    evt.preventDefault();
    if($('#detalle_venta')[0].checkValidity()){
      if(confirm('Por favor revise los datos de la venta.  \nSi son correctos click en OK para guardar.')){
        $('#detalle_venta')[0].submit();
      }
    }
});

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
  $('#form_editar_venta').find('input').attr('disabled',false);
  $(".form-select-datalist").prop("disabled", false);
  $(".borrar").css('display','inline');
  $("#guardar-cambios").css('display','inline');
  $("#editar_modal").css('display','none');
  $("#nuevo_cliente").css('display','block');
}

function mostrar(idMostrar,idBoton){
  $(".botone").css("background-color","white");
  $(idBoton).css("background-color","#b9eff16b");
  $(".oculto").hide();
  $(idMostrar).fadeIn(1000);
}