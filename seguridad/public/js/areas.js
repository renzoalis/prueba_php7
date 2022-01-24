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

function modificar_area(id){
  var indice = array_indice[id];
  // console.log(array_json[indice]);
  
  $('#edit_area_id').val(array_json[indice]["area_id"]);
  $('#id_area_titulo').text("#"+array_json[indice]["area_id"]);
  $('#areaNombre').val(array_json[indice]["area_nombre"]);
  $('#areaEstado').val(array_json[indice]["area_baja"]);
  if(array_json[indice]["area_baja"]<1)
  {
      $('#areaEstado_check').html('<span style="color: green;"><i class="fa fa-check-circle-o"></i> Activo</span>');
  } else
  {
      $('#areaEstado_check').html('<span style="color: red;"><i class="fa fa-times-circle"></i> Inactivo</span>');
  }
}

function cambio_estado(){
  
  if($('#areaEstado').val()>0)
  {
      $('#areaEstado').val(0);
      $('#areaEstado_check').html('<span style="color: green;"><i class="fa fa-check-circle-o"></i> Activo</span>');
  } 
  else {
      $('#areaEstado').val(1);
      $('#areaEstado_check').html('<span style="color: red;"><i class="fa fa-times-circle"></i> Inactivo</span>');
  }
}

