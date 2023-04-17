/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
const anterior = () => {
    AtrasC('',
            'bucarPalabraClave=' + document.getElementById('bucarPalabraClave').value,
            'tableIntT',
            `View/${document.getElementById('donde').value}/${document.getElementById('donde').value}Tabla.php`);
};

const siguiente = () => {
    AdelanteC('',
            document.getElementById('numeroPaginas').value,
            'bucarPalabraClave=' + document.getElementById('bucarPalabraClave').value + '&pagina=0' ,
            'tableIntT',
            `View/${document.getElementById('donde').value}/${document.getElementById('donde').value}Tabla.php`);
};

function  AdelanteC(donde, numeroPaginas, filtro, dondeT, accion){
    pagNumero=parseInt(document.getElementById('pag').innerHTML);
    pagNumero=pagNumero+1;
    if (pagNumero<=numeroPaginas) {
      document.getElementById('pag').innerHTML=pagNumero;
      var pagina=(pagNumero*20)-20;
        idexistentesReCa('',filtro+"&pagina="+pagina, dondeT, accion, null, null);  
    }   
}

function  AtrasC(donde, filtro, dondeT, accion){
    pagNumero=parseInt(document.getElementById('pag').innerHTML);
    if(pagNumero<1 ){
        document.getElementById('pag').innerHTML=1;
    }else if (pagNumero>1) {
	pagNumero=pagNumero-1;
        document.getElementById('pag').innerHTML=pagNumero;
        var pagina=(pagNumero*20)-20;
        idexistentesReCa('',filtro+"&pagina="+pagina, dondeT, accion, null, null); 
    }
}