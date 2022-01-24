function notificacion(titulo,mensaje,icono,tipo,delay){
  notify({
    type: tipo, //alert | success | error | warning | info
    title: titulo,
    position: {
        x: "right", //right | left | center
        y: "top" //top | bottom | center
    },
    autoHide: true, //true | false
    delay: delay, //number ms
    icon:icono,
    message: mensaje
  });
}