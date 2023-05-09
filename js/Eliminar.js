/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
function eliminar(donde , pag = document.getElementById('donde').value )
{
    cargarLoad('aviso');
    reload();
    const form = document.querySelector('#modalesV');
    const formData = new FormData(form);
    form.addEventListener('submit', function(e){
        e.preventDefault(); 
    });
    formFotoDoc(`View/${pag}/${pag}Crud.php`,formData ,donde);
    window.setTimeout( () =>{
            //cerrarventana(); 
        },2000);
}

function eliminarItem( postcad , donde , accion ) 
{
    var des = atob(postcad) ;
    idexistentesReCa('' , des , donde , `View/${accion}/${accion}Crud.php` , null , null ) ;
    reloadInfo( donde ) ;
}


