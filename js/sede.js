/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function sedeGestion(sede)
{
    document.getElementById('formDetalle').innerHTML = "<form method='post' action=''>"
            + "<input type='hidden' value='" + sede + "' id='sedeGestion' name='sedeGestion' required/>"
            + "<input type='submit' value='accion' id='accionForm' name='accionForm'/> "
            + "</form>";
    document.getElementById('accionForm').click();
}

function sedeReporte( ids , eve , tadId )
{
    reporte( '' , `Reporte_total` , `View/Sede/SedeModales.php` , ids , 'tablareporte' , eve , tadId );
}