/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


function cargarLoad( aviso = 'aviso2')
{
    document.getElementById('cargar_load').innerHTML="<div class='circular-progress' id='carga'> </div>";
    document.getElementById('cargar_load').style.zIndex = "1000000";
    document.getElementById('cargar_load').style.display = "block";
    if( document.getElementById('accion') !== null )
    {
        document.getElementById('accion').style.display = "none";
    }
    vuelta=1;
    var id  =  window.setInterval(function(){
        document.getElementById('carga').style.transform="rotate("+45*vuelta+"deg)";

        if((15*vuelta)==360){
            vuelta=0;
        }  
        vuelta=vuelta+1;
        if(document.getElementById(aviso).innerHTML!=='')
        {
            clearInterval(id);
            document.getElementById('cargar_load').style.zIndex = "0";
            document.getElementById('cargar_load').style.display = "none";
            if( document.getElementById('accion') !== null )
            {
               document.getElementById('accion').style.display = "block";
            }
            if( document.getElementById('tablaRespuesta') != null )
            {
            document.getElementById('tablaRespuesta').innerHTML = "";
            }
        }
     },100);      
}

function reload()
{
    document.getElementById('pag').innerHTML='1';
    sedeGestion='';
    if( document.getElementById('sedeGestion') !== null )
    {
        sedeGestion=`&sedeGestion=${document.getElementById('sedeGestion').value}`;
    }
    if (document.getElementById('id_espe') !== null)
    {
        sedeGestion += `&id_espe=${document.getElementById('id_espe').value}`;
    }
    window.setTimeout(() =>
    {
       idexistentesReCa('',`pagina=0${sedeGestion}`,'tableIntT',`View/${document.getElementById('donde').value}/${document.getElementById('donde').value}Tabla.php`, null, null)
    }, 2000
            );
}

function espacioObligatorio(ID)
{
    document.getElementById('aviso1').innerHTML="***ESPACIO SIN MARCAR "+ID+"***";
}

  

window.addEventListener('keydown', function(ev) {            
    if (ev.keyCode==27) {
               document.getElementById("modales").style.transform='translateX(-100%)';      
	       document.getElementById("modales").style.transition="1s"; 
	       document.getElementById("formularioDiv").style.width="";    
    }
});

function cerrarventana(){
               document.getElementById("modales").style.transform='translateX(-100%)';      
	       document.getElementById("modales").style.transition="0.5s"; 
}

menu = 0 ;

function espacioObligatorio(ID){
    document.getElementById('aviso').innerHTML="***ESPACIO SIN MARCAR "+ID+"***";
}

function menudes(){
    if(menu===0){
        document.getElementById('divmenu1').style.height=`${document.getElementById('nav').clientHeight}`;
        document.getElementById('divmenu1').style.transition='1s';
        document.getElementById('nav').style.transform='translateY(0%)';
        document.getElementById('nav').style.transition='1s'; 
        menu=1;
    }else if (menu===1) {
	document.getElementById('divmenu1').style.height='40px';
        document.getElementById('divmenu1').style.transition='1s';
        document.getElementById('nav').style.transform='translateY(-110%)';
        document.getElementById('nav').style.transition='1s'; 
        menu=0;
    }    
}
window.addEventListener('resize', function (ev){
    if( parseInt(window.innerWidth) <= 1050){
        document.getElementById('nav').style.transform='translateY(-110%)';
        document.getElementById('nav').style.transition='0s';
        document.getElementById('divmenu').style.height='40px';
        document.getElementById('divmenu').style.minHeight='40px';
        menu=0;
    }
    else
    {
        document.getElementById('nav').style.transform='translateY(-0%)';
        document.getElementById('divmenu').style.minHeight='45px';
        document.getElementById('divmenu').style.height='auto';
        menu=0;
    }
});

function cerrar(donde){
        document.getElementById('modales').style.display='none';
        var dondeVamos=document.querySelector('#modalesV');
        dondeVamos.setAttribute('action', donde);
        document.getElementById('accionU').type='submit';
}