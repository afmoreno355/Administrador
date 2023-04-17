/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function NuevaVentana( I , actions = '' , evt = null , tadId = null , rutas = null ){
    var ruta = '' ;
    if( rutas !== null )
    {
        var rutaNueva = prompt('INGRESE LA RUTA DE SUS DESCARGAS EJ: C:\\Users\\FELIPE\\Downloads PARA EMPAREJAR IMAGEN ') ;
        ruta = " <input type='hidden' value='"+rutaNueva+"' id='ruta' name='ruta'/> " ;
    }
    document.getElementById('formDetalle').innerHTML="<form target='_blank' method='post' action='"+actions+"'>"
             +"<input type='hidden' value='"+I+"' id='I' name='I'/> "
             +ruta
             +"<input type='submit' value='accion' id='accionForm' name='accionForm'/> "
         +"</form>";
    document.getElementById('accionForm').click();
    if( evt != null && tadId != null )
    {
        action( evt , tadId );
    }
}

function NuevaVentanaPop( I , actions = '' , evt = null , tadId = null ){
    document.getElementById('formDetalle').innerHTML="<form target='print_popup' method='post' action='"+actions+"'    onsubmit='"+window.open('about:blank','print_popup','width=1000,height=700,left=200,top=150,scrollbars=no,status=no,toolbar=no,directories=no,location=no,menubar=no')+"'   >"
             +"<input type='hidden' value='"+I+"' id='I' name='I'/> "
             +"<input type='submit' value='accion' id='accionForm' name='accionForm'/> "
         +"</form>";
    document.getElementById('accionForm').click();
    if( evt != null && tadId != null )
    {
        action( evt , tadId );
    }
}

function numeroElementos( numero , donde , primary , tabla  )
{
    envioNuevo( 'null' , `id=3&accion=ADICIONAR&${primary}&count=${numero}` , `modalVentanaSecundaria` , `View/${donde}/${donde}Modales.php` , "id_"+tabla , false ) ;
}
