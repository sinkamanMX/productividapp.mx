$( document ).ready(function(){
  
});

function verDetalles(color,desContenedor){
  console.log(color);
  
  location.href='/dashboard/main/detalles?colorES='+color+'|'+desContenedor;

/*$.ajax({
        url: "dashboard/main/detalles",
        type: "GET",
        data: { colorES : 1},
        success: function(data) {
		  location.href='/dashboard/main/detalles?colorES=33';
		}
    });
*/
}

function verStatus(valor){
	 location.href='/location/main/index?iStatus='+valor;
}