/* CUSTOM JS DE INTRANET-GAM */
/* 20-05-2017 */

function cambio_estado(){
  
  if($('#moduloEstado').val()>0)
  {
      $('#moduloEstado').val(0);
      $('#moduloEstado_check').html('<span style="color: green;"><i class="fa fa-check-circle-o"></i> Activo</span>');
  } 
  else {
      $('#moduloEstado').val(1);
      $('#moduloEstado_check').html('<span style="color: red;"><i class="fa fa-times-circle"></i> Inactivo</span>');
  }
}

function cargar_inputs(data){
  // console.log(data.paginas);
  $('#edit_modulo_id').val(data.modulo.mod_id);
  $('#id_modulo_titulo').text("#"+data.modulo.mod_id);
  $('#moduloNombre').val(data.modulo.mod_nombre);
  $('#moduloEstado').val(data.modulo.mod_baja);
  if(data.modulo.mod_baja<1)
  {
      $('#moduloEstado_check').html('<span style="color: green;"><i class="fa fa-check-circle-o"></i> Activo</span>');
  } else
  {
      $('#moduloEstado_check').html('<span style="color: red;"><i class="fa fa-times-circle"></i> Inactivo</span>');
  }

  $('#tabla_paginas').html('');

  for (prop in data.paginas) {
    // console.log(data.paginas[prop].id);
    var codigo = '<tr id="'+data.paginas[prop].id+'" style="cursor: context-menu;">';

    codigo += '<td> '+data.paginas[prop].id+' </td>';
    codigo += '<td> '+data.paginas[prop].script+' </td>';
    codigo += '<td> <form name="eliminar_pagina" id="eliminar_pagina" method="post" action="">';
    codigo += '<input type="hidden" id="id_pagina_delete" name="id_pagina_delete" value="'+data.paginas[prop].id+'">';
    codigo += '<a style="color: #494d55;" href="#" onclick="$(this).closest(\'form\').submit();"><i class="fa fa-trash" title="Eliminar página"></a>';
    codigo += '</form></td>';
    codigo += '</tr>';
    $('#tabla_paginas').append(codigo);
  }

}

function getModulo(id){
  // console.log("Hola");
  $.ajax({
      url: 'ajax_getModulo.php',
      type: 'POST',
      data: {id : id},
      dataType: 'json'
  }).done(function(data){
      // console.log(data);
      cargar_inputs(data);
  }).fail(function(xhr, textStatus, errorThrown) {
      console.log(xhr.responseText);
  }).always(function(){
      //console.log('The ajax call ended.');
  });
}
