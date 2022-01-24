
/* -- FUNCIONES NUEVA VENTA -- */

/* Para que deje scrollear el modal anterior!! */
$(document).on('hidden.bs.modal', '.modal', function () {
    $('.modal:visible').length && $(document.body).addClass('modal-open');
});


document.getElementById('detalle_venta').addEventListener('submit', function(evt){
    evt.preventDefault();
    if($('#detalle_venta')[0].checkValidity()){
      if($('#input_monto').val() < 0 ){
        alert('No se puede cargar una nota con saldo negativo ')
      }else{
        if(confirm('Por favor revise los datos del pago.  \nSi son correctos click en OK para guardar.')){
          $('#detalle_venta')[0].submit();
        }
      }
    }
});

$('#combo_tipo').on('input',function(){
  var i = $(this).val();
  if(i == 'NC' || i == 'ND'){
    $('#div-contado').css('display','block');
  } else{
    $('#div-contado').css('display','none');
  }
});

function getNota(id) {
  $.ajax({
      url: 'ajax_getNota.php',
      type: 'POST',
      data: {id : id},
      dataType: 'html'
  }).done(function(data){
    console.log(data);
      $('#modal-edit-nota').html('');
      $('#modal-edit-nota').html(data);
      $('#modal-edit-nota').modal('show');
  }).fail(function(xhr, textStatus, errorThrown) {
      console.log(xhr.responseText);
  }).always(function(){
      // console.log('The ajax call ended.');
  });
}

function getVentaDespachada(id) {
  $.ajax({
      url: 'ajax_getVentaDespachada.php',
      type: 'POST',
      data: {id : id},
      dataType: 'html'
  }).done(function(data){
    //console.log(data);
    $('#modal-ver-venta').html('');
      $('#modal-ver-venta').html(data);
      $('#modal-ver-venta').modal('show');
      
  }).fail(function(xhr, textStatus, errorThrown) {
      console.log(xhr.responseText);
  }).always(function(){
      // console.log('The ajax call ended.');
  });
}

function getListadoAgentes(id) {
  $.ajax({
      url: 'ajax_getListadoAgentes.php',
      type: 'POST',
      data: {id : id},
      dataType: 'html'
  }).done(function(data){
    //console.log(data);
    $('#div-seleccionar-agente').html(data);
      
  }).fail(function(xhr, textStatus, errorThrown) {
      console.log(xhr.responseText);
  }).always(function(){
      // console.log('The ajax call ended.');
  });
}