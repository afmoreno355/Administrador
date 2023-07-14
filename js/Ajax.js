/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function renderImage()
{
    const form = document.querySelector('#modalesV');
    form.click();
    const formData = new FormData(form);
    form.addEventListener('submit', function (e) {
        e.preventDefault();
    });
    if( formData.get('Foto') !== null)
    {
    const file = formData.get('Foto');
    const image = URL.createObjectURL(file);
    document.getElementById('imageNew').setAttribute('src', image);
    }
}

function formFotoDoc( donde , formData, hacer = 'aviso' ) {
    var xhr = new XMLHttpRequest();
    renderImage( formData )
    xhr.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200)
        {
            var respuesta = this.responseText;
            //console.log(respuesta);
            respuestas( hacer , respuesta ) ;
        }
    };
    xhr.open('POST', donde, true);
    xhr.send(formData);
}

 function idexistentesReCa(id, postcad, donde = 'aviso' , accion , evt , tadId) {
        var xhr=new XMLHttpRequest();
        xhr.onreadystatechange=function (){
            if(this.readyState==4 && this.status==200){
                   var respuesta=this.responseText;  
                   console.log(respuesta);
                   respuestas( donde , respuesta ) ;
            }        
        };
        xhr.open('POST',accion, true);
        xhr.setRequestHeader("Content-type","application/x-www-form-urlencoded");
        xhr.send(postcad); 
        action( evt , tadId ) ;
    }
     
    function action( evt = null , tadId = null )
    {
        if(evt !== null && tadId !== null)
        {
        document.getElementById('pag').innerHTML = 1;
        var i, tablinks;
        tablinks = document.getElementsByClassName(tadId);
        for (i = 0; i < tablinks.length; i++) {
            tablinks[i].className = tablinks[i].className.replace(" color", "");
        }

        evt.currentTarget.className += " color";
        }
    }
    function respuestas( donde = 'aviso' , respuesta )
    {
        if( ( datos = respuesta.split('<|>') ).length >= 2)
        {
                for (var i = 0; i < document.getElementsByClassName(donde).length; i++)
                {
                    document.getElementsByClassName(donde)[i].innerHTML = datos[i];
                }
        }
        else if ( respuesta.split(":").length >= 2 && respuesta.split("}").length >= 2 && respuesta.split("{").length >= 2)
        {
            console.log(respuesta);
            jsonRespuesta = JSON.parse(respuesta);
            //alert(jsonRespuesta["empresa"].toLowerCase());
            if( jsonRespuesta["empresa"].toLowerCase() === 'sena sg-dfp' && window.location.toString().includes('adminV2') )
            {
                const user = jsonRespuesta["user"];
                const empresa = jsonRespuesta["empresa"];
                const token1 = jsonRespuesta["token1"];
                const token2 = jsonRespuesta["token2"];
                if( token1 === 'e9a47b2d90858e078eddd1157959544e1' && token2 === '1d638cdf5c6d83588bf3216f3e2c3a881' )
                {
                    token(empresa,token1,token2,user);
                    window.location.replace('inicio#MI_USUARIO');
                    return ; 
                }
                else
                {
                    token(empresa,token1,token2,user);
                    document.getElementById(donde).innerHTML = 'NO TIENE LICENCIA ESTE SISTEMA ESTE FUE CREADO PARA LA ALCALDIA DE PITALITO' ;
                    return ;
                }
            } 
            else
            {
                //alert(window.location.toString());
                token(empresa,token1,token2,user)
                document.getElementById(donde).innerHTML = 'NO TIENE LICENCIA ESTE SISTEMA ESTE FUE CREADO PARA LA ALCALDIA DE PITALITO' ;
                return ;
            }
        }
        else
        {
            document.getElementById(donde).innerHTML = respuesta;
        }
    }
    
    function token( empresa , token1 , token2 , user )
    {
        idexistentesReCa('', `empresa=${empresa}&token1=${token1}&token2=${token2}&user=${user}` , `aviso` , `View/Validar/Empresa` , null , null)
    }