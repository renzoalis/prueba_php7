/* CUSTOM JS DE INTRANET-GAM */
/* 20-05-2017 */

// Inicialización
var array_json = [];
var array_indice = [];
var a = 0;

function cargar_json(objeto,id) {
  array_json.push(objeto);
  array_indice[id] = a;
  a++;
  // console.log(array_json);
}

function modificar_rol(id){
  var indice = array_indice[id];
  // console.log(array_json[indice]);
  
  $('#edit_rol_id').val(array_json[indice]["rol_id"]);
  $('#id_rol_titulo').text("#"+array_json[indice]["rol_id"]);
  $('#rolNombre').val(array_json[indice]["rol_nombre"]);
  $('#rolEstado').val(array_json[indice]["rol_baja"]);
  if(array_json[indice]["rol_baja"]<1)
  {
      $('#rolEstado_check').html('<span style="color: green;"><i class="fa fa-check-circle-o"></i> Activo</span>');
  } else
  {
      $('#rolEstado_check').html('<span style="color: red;"><i class="fa fa-times-circle"></i> Inactivo</span>');
  }
}

function cambio_estado(){
  
  if($('#rolEstado').val()>0)
  {
      $('#rolEstado').val(0);
      $('#rolEstado_check').html('<span style="color: green;"><i class="fa fa-check-circle-o"></i> Activo</span>');
  } 
  else {
      $('#rolEstado').val(1);
      $('#rolEstado_check').html('<span style="color: red;"><i class="fa fa-times-circle"></i> Inactivo</span>');
  }
}

