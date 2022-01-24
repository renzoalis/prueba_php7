
// DTV

//focuseo el primer input cuando se abre el modal 
$('#myModalDTVAdd').on('shown.bs.modal', function () {
  $('#input_numero_dtv').focus();
});

// validaciones para el dtv desde nueva compra
function guardardtv(){
var esValido = false;

//valido que haya ingresado el numero de dtv 
  if($('#input_numero_dtv').val()){
   esValido = true;
  }else {
    alert('El número dtv es obligatorio');
     $('#input_numero_dtv').focus();
     return false;
  }

// valido que la fecha de vencimiento sea superior a la fecha actual
var fechaAlta =moment($('#dtv_fh').val());
var fechaVencimiento = moment($('#input_fecha_vencimiento_dtv').val());
var diferencia = fechaVencimiento.diff(fechaAlta, 'days');

if ( 0 <= diferencia && diferencia <= 30 ) { 
  esValido=true;
}else {
  alert('La fecha de vencimiento no debe ser anterior a la fecha de alta ni superar los 30 dias de diferencia');
  $('#input_fecha_vencimiento_dtv').focus();
  return false;
}

// valido que todos los campos de los productos hayan sido cargados
var cant_productos = $('#dtv_cant_prod').val();
for (i = 0; i < cant_productos; i++) {
  //producto
  var input_producto = $('#dtv_producto_'+i).val();
  if (input_producto == "") {
      alert('debe completar todos los campos del producto');
  $('#dtv_producto_'+i).focus();
  return false;
  }
  //variedad
  var input_variedad = $('#dtv_variedad_'+i).val();
  if (input_variedad == "") {
      alert('debe completar todos los campos del producto');
  $('#dtv_variedad_'+i).focus();
  return false;
  }
  //tipo de embalaje
  var input_embalaje = $('#dtv_tipoembalaje_'+i).val();
  if (input_embalaje == "") {
      alert('debe completar todos los campos del producto');
  $('#dtv_tipoembalaje_'+i).focus();
  return false;
  }
  //cantidad
  var input_cantidad = $('#dtv_cantidad_'+i).val();
  if (input_cantidad == "") {
      alert('debe completar todos los campos del producto');
  $('#dtv_cantidad_'+i).focus();
  return false;
  }
  //peso
  var input_peso = $('#dtv_peso_'+i).val();
  if (input_peso == "") {
      alert('debe completar todos los campos del producto');
  $('#dtv_peso_'+i).focus();
  return false;
  }
  // umedida
  var input_umedida = $('#dtv_umedida_'+i).val();
  if (input_umedida == "") {
      alert('debe completar todos los campos del producto');
  $('#dtv_umedida_'+i).focus();
  return false;
  }

}

// si todo es valido 
  if(esValido) {
     $('#noguardado').val(1);
     $('#myModalDTVAdd').modal('hide');
     $('#agregar_dtv').submit();
  }

}


// cuando se cierra el modal y no se guardo 

$('#myModalDTVAdd').on('hidden.bs.modal', function () {
  var noguardado = $("#noguardado").val();
  if (noguardado == 0){
    borrardatos();
  }
})

// borra todos los inputs del modal dtv
function borrardatos(fecha){
  $('#myModalDTVAdd').find(".inputdtv").val("");
  // vacio la tabla de productos
  $('.filaproductos').remove();
}

//trae la dtv 
function getDTV(id) {
  $.ajax({
      url: 'ajax_getDTV.php',
      type: 'POST',
      data: {id : id},
      dataType: 'html'
  }).done(function(data){
      console.log(data);
      $('#modal-edit-dtv').html('');
      $('#modal-edit-dtv').html(data);
      $('#modal-edit-dtv').modal('show');
  }).fail(function(xhr, textStatus, errorThrown) {
      console.log(xhr.responseText);
  }).always(function(){
      // console.log('The ajax call ended.');
  });
}

function nuevo_producto_dtv() {

  var i = parseInt($('#dtv_cant_prod').val());

  var newRowContent = '<tr class="filaproductos">';
  newRowContent += '<td><input class="det_venta" type="text" id="dtv_producto_'+i+'" name="dtv_prod['+i+'][producto]"></td>';
  newRowContent += '<td><input class="det_venta" type="text" id="dtv_variedad_'+i+'" name="dtv_prod['+i+'][variedad]"></td>';
  newRowContent += '<td><input class="det_venta" type="text" id="dtv_tipoembalaje_'+i+'" name="dtv_prod['+i+'][tipo_embalaje]"></td>';
  newRowContent += '<td><input class="det_venta" type="number" id="dtv_cantidad_'+i+'" name="dtv_prod['+i+'][cantidad]" oninput="modificardtv('+i+')"></td>';
  newRowContent += '<td><input class="det_venta" type="number" id="dtv_peso_'+i+'" name="dtv_prod['+i+'][peso]" oninput="modificardtv('+i+')"></td>';
  newRowContent += '<td><input class="det_venta" readonly type="number" id="dtv_total_'+i+'" name="dtv_prod['+i+'][total]"></td>';
  newRowContent += '<td><input style="width:80%" class="det_venta" type="text" id="dtv_umedida_'+i+'" name="dtv_prod['+i+'][tipo_umedida]"> </td>';
  newRowContent += '<td><a style="padding-left:5px;color:red" href="#" id="borrar_producto_dtv"><i class="fa fa-trash"></i></a></td>'
  newRowContent += '</tr>';

  $("#tabla_productos_dtv tbody").append(newRowContent);

  $('#dtv_cant_prod').val(i+1);
}

$(document).on('click', '#borrar_producto_dtv', function (event) {
  event.preventDefault();
  $(this).closest('tr').remove();
});

function modificardtv(i) {
  // Actualizo factura
    var cantidad = parseFloat($('#dtv_cantidad_'+i+'').val());
    var peso = parseFloat($('#dtv_peso_'+i+'').val());
    var precio_parcial = (cantidad * peso);
    $('#dtv_total_'+i+'').val(precio_parcial);
}

// DTV DESDE EDIT COMPRA

function guardardtvedit(){
var esValido = false;

//valido que haya ingresado el numero de dtv 
  if($('#input_numero_dtv_edit').val()){
   esValido = true;
  }else {
    alert('El número dtv es obligatorio');
     $('#input_numero_dtv_edit').focus();
     return false;
  }

// valido que la fecha de vencimiento sea superior a la fecha actual
var hoy = $('#dtv_fh_edit').val()
var fechaDentro30 = $('#dtv_fh_30_edit').val()
var fechaVencimiento = $('#input_fecha_vencimiento_dtv_edit').val();

if (hoy <= fechaVencimiento && fechaVencimiento <= fechaDentro30) { 
  esValido=true
}else {
  alert('La fecha de vencimiento no debe ser anterior a la fecha actual ni superior a los 30 dias ');
  $('#input_fecha_vencimiento_dtv_edit').focus();
  return false;
}

// si todo es valido 
  if(esValido) {
     $('#agregar_dtv_edit').submit();
  }

}




// despues de abrir el modal para agregar dtv, le paso el idcompra
function abrirmodalnuevadtv(id_compra){
$('#dtv_edit_compra_id').val(id_compra);
}

function edit_nuevo_producto_dtv() {

  var i = parseInt($('#dtv_cant_prod_edit').val());

  var newRowContent = '<tr class="filaproductos">';
  newRowContent += '<td><input class="det_venta" type="text" id="dtv_producto_edit_'+i+'" name="dtv_prod['+i+'][producto]"></td>';
  newRowContent += '<td><input class="det_venta" type="text" id="dtv_variedad_edit_'+i+'" name="dtv_prod['+i+'][variedad]"></td>';
  newRowContent += '<td><input class="det_venta" type="text" id="dtv_tipoembalaje_edit_'+i+'" name="dtv_prod['+i+'][tipo_embalaje]"></td>';
  newRowContent += '<td><input class="det_venta" type="number" id="dtv_cantidad_edit_'+i+'" name="dtv_prod['+i+'][cantidad]" oninput="modificardtvedit('+i+')"></td>';
  newRowContent += '<td><input class="det_venta" type="number" id="dtv_peso_edit_'+i+'" name="dtv_prod['+i+'][peso]" oninput="modificardtvedit('+i+')"></td>';
  newRowContent += '<td><input class="det_venta" readonly type="number" id="dtv_total_edit_'+i+'" name="dtv_prod['+i+'][total]"></td>';
  newRowContent += '<td><input style="width:80%" class="det_venta" type="text" id="dtv_umedida_edit_'+i+'" name="dtv_prod['+i+'][tipo_umedida]"> </td>';
  newRowContent += '<td><a style="padding-left:5px;color:red" href="#" id="borrar_producto_edit_dtv"><i class="fa fa-trash"></i></a></td>'
  newRowContent += '</tr>';

  $("#tabla_productos_dtv_edit tbody").append(newRowContent);

  $('#dtv_cant_prod_edit').val(i+1);
}

$(document).on('click', '#borrar_producto_edit_dtv', function (event) {
  event.preventDefault();
  $(this).closest('tr').remove();
});
function modificardtvedit(i) {
  // Actualizo factura
    var cantidad = parseFloat($('#dtv_cantidad_edit_'+i+'').val());
    var peso = parseFloat($('#dtv_peso_edit_'+i+'').val());
    var precio_parcial = (cantidad * peso);
    $('#dtv_total_edit_'+i+'').val(precio_parcial);
}

$('#myModalDTVeditAdd').on('hidden.bs.modal', function () {
    borrardatosedit();
})

// borra todos los inputs del modal dtv
function borrardatosedit(fecha){
  $('#myModalDTVeditAdd').find(".inputdtv").val("");
  // vacio la tabla de productos
  $('.filaproductos').remove();
}


// funcion para cerar la dtv


function cerrar_dtv(id) {

  var id = id;
  console.log(id);
  
    $.ajax({
      url: 'ajax_cerrarDTV.php',
      type: 'POST',
      data: {id : id},
  }).done(function(data){
     $('#modal-edit-dtv').modal('hide');
     notificacion("DTV cerrada","la DTV se cerro exitosamente","<icon class=\"fa fa-check\">","success");
     $("#fila_dtv_"+id).css("background","antiquewhite");
     $("#estado_dtv_"+id).text("CERRADA");
  }).fail(function(xhr, textStatus, errorThrown) {
      console.log(xhr.responseText);
  });
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