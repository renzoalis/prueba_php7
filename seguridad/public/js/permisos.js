/* CUSTOM JS DE INTRANET-GAM */
/* 29-05-2017 */

function cambiar_permiso(modulo, rol, permiso){

  $('#rol_id').val(rol);
  $('#modulo_id').val(modulo);
  $('#tipoacc_id').val(permiso);
  $('#detalle_permiso_edit').submit();

}


