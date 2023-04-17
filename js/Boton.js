/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
function llenarTabla( numName , classe , k = '' ) 
{
    // LLENAR JSON PARA ENVIAR A PHP
    var elementos = '{}' ;
    var obj = [] ;
    for (var i = 0; i < numName; i++) 
    {
        elementos = '{' ;
        for (var j = 0; j < document.getElementsByClassName(`tabla_llenar${k}${i}`).length ; j++) 
        {
            // CAMBIO CARACTERES ESPECIALES 
            var cadena = document.getElementsByClassName(`tabla_llenar${k}${i}`)[j].value.replace(/Ñ/gim , 'N') ;
            cadena = cadena.replace(/á/gim , 'a') ;
            cadena = cadena.replace(/é/gim , 'e') ;
            cadena = cadena.replace(/í/gim , 'i') ;
            cadena = cadena.replace(/ó/gim , 'o') ;
            cadena = cadena.replace(/ú/gim , 'u') ;
            cadena = cadena.replace(/\n/gim , ' ') ;
            // HACEMOS UN JSON COMO STRING
            elementos += `"item${j}" : "${cadena.toUpperCase()}" ` ;
            if( j < document.getElementsByClassName(`tabla_llenar${k}${i}`).length - 1 )
            {
                elementos += `,` ;
            }
        }  
        elementos += '}' ;
        // ADD LINEA EN OBJ Y TRANSFORMAMOS EN JSON
        obj.push(JSON.parse(elementos));
    }
    return obj;
}

function add( value = '' , id = null , donde = 'agregar_listas1' , postcad , accion , classe , k = '' )
{
    // FUNCION GENERICA PARA CREAR CAMPOS DE FORMULARIOS Y ELIMINAR
    var des = atob(postcad) ;
    // CONTEO DE LOS DATOS DE LA CLASE QUE LLEGA
    var numName = document.getElementsByClassName(classe) !== null 
        ?
                parseInt( document.getElementsByClassName(classe).length )
        :
            0
        ;
    var arreglo = llenarTabla( numName , classe , k ); 
    //EL JSON LO ENVIAMOS COMO CADENA A PHP
    postcad = `I=`+btoa(`${des}&numName=${numName}&array_List=${JSON.stringify(arreglo)}`) ;
    idexistentesReCa('' , postcad , donde , accion , null , null )  
    var ids = window.setInterval(() => {
      clearInterval(ids);
    });
}

function addvarios( value = '' , id = null , donde = 'agregar_listas1' , postcad , accion , classe )
{
    var des = atob(postcad) ; 
    postcad = btoa(`adicionar=${value}&${des}`) ;
    add( '' , id , donde , postcad , accion , classe , value );
}