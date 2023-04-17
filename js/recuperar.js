/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


function recuperar_clave()
{
    var valor_id = prompt("INGRESE SU NUMERO DE IDENTIFICACION");
    var valor_correo = prompt("INGRESE CORREO ASOCIADO");
    if(valor_id !== '' && valor_correo !== '')
    {
        idexistentesReCa('' , `accion=RECUPERAR&usuario=${valor_id}&correo=${valor_correo}` , 'respuestaIndex' , 'Controller/Recuperar/RecuperarCrud.php' , null , null);
    }
    else
    {
        document.getElementById('respuestaIndex').innerHTML='LA IDENTIFICACION Y EL CORREO DEBEN SER VALIDOS';
    }
}

