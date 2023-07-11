/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


function reporte( ids , postcat , donde , ruta , eve , tadId , titulo , recargar = null  )
{    
    const fechaActual = new Date();
    if( recargar === null )
    {
        cargarLoad(donde);
    }
    idexistentesReCa( ids , postcat , `${donde}` ,`${ruta}` , eve , tadId );
     
    var id  =  window.setInterval(function(){
    
        if(document.getElementById(donde).innerHTML!=='')
        { 
            var data_type = 'data:text/csv;charset=utf-8';
            var tmpElemento = document.getElementById('botonE');
            var tabla_div = document.getElementById(donde).innerHTML;
            tmpElemento.href = data_type + ', ' + tabla_div;
            //Asignamos el nombre a nuestro EXCEL
            tmpElemento.download= `${titulo} ${fechaActual}.csv`;
            // Simulamos el click al elemento creado para descargarlo
            tmpElemento.click();
            clearInterval(id);
        }
     },100); 
    document.getElementById(donde).innerHTML='';
}