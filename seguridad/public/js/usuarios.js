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
  //console.log(array_json);
}

function modificar_usuario(id){
  var indice = array_indice[id];
  // console.log(array_json[indice]);
  
  $('#edit_usuario_id').val(array_json[indice]["usua_id"]);
  $('#id_user_titulo').text("#"+array_json[indice]["usua_id"]);
  $('#usuarioNombre').val(array_json[indice]["usua_nombre"]);
  $('#usuarioID').val(array_json[indice]["usua_usrid"]);
  $('#usuarioMail').val(array_json[indice]["usua_email"]);
  $('#usuariPass').val('');
  $('#usuarioEstado').val(array_json[indice]["usua_baja"]);
  if(array_json[indice]["usua_baja"]<1)
  {
      $('#usuarioEstado_check').html('<span style="color: green;"><i class="fa fa-check-circle-o"></i> Activo</span>');
  } else
  {
      $('#usuarioEstado_check').html('<span style="color: red;"><i class="fa fa-times-circle"></i> Inactivo</span>');
  }
  $('#usuarioArea').val(array_json[indice]["area_id"]);
  $('#usuarioRol').val(array_json[indice]["rol_id"]);
}

function cambio_estado(){
  
  if($('#usuarioEstado').val()>0)
  {
      $('#usuarioEstado').val(0);
      $('#usuarioEstado_check').html('<span style="color: green;"><i class="fa fa-check-circle-o"></i> Activo</span>');
  } 
  else {
      $('#usuarioEstado').val(1);
      $('#usuarioEstado_check').html('<span style="color: red;"><i class="fa fa-times-circle"></i> Inactivo</span>');
  }
}

