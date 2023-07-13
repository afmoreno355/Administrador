/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


function PlanoUsuarios(donde , hacer)
{
    document.getElementById('aviso2').innerHTML = '';
    for (var i = 0; i < document.getElementsByClassName('column_check').length; i++) 
    {
        switch (i)
        {
            case 0:
                nombre = 'IDENTIFICACION' ;
            break;
            case 1:
                nombre = 'NOMBRES' ;
            break;
            case 2:
                nombre = 'APELLIDOS' ;
            break;
            case 3:
                nombre = 'CORREO' ;
            break;
            case 4:
                nombre = 'CELULAR' ;
            break;
            case 5:
                nombre = 'TELEFONO' ;
            break;
            case 6:
                nombre = 'SEDE' ;
            break;
            case 7:
                nombre = 'ROL' ;
            break;
            case 8:
                nombre = 'DEPENDENCIA' ;
            break;
        }
        if( document.getElementsByClassName('column_check')[i].innerHTML.includes('<i class='))
        {
            document.getElementsByClassName('column_check')[i].innerHTML = `<div>${nombre.toUpperCase()}</div>`;
            document.getElementsByClassName('column_check')[i].style.background = 'white';
        }   
    }
    
    personaplano = false ;
    
    const form = document.querySelector('#modalesV');
    const formData = new FormData(form);
    form.addEventListener('submit', function(e){
        e.preventDefault(); 
    })
    
    if( document.getElementById('personaplano').value == '' )
    {
        document.getElementById('aviso').innerHTML = ' DOCUMENTO .DOC O .CSV OBLIGATORIO ';
        document.getElementById('personaplano').focus();
        return ;
    }
    else
    {
        personaplano = true ;
    }
    if(personaplano == true )
    {
        
        formFotoDoc(donde, formData, hacer);
    }
}

function validarColumn(event)
{
    var valueInput = '' ;
    var valueNoInput = '' ;
    var nombre = '' ;
    var contador = 0 ;
    if ( event.currentTarget.checked )
    {
        for (var i = 0; i < document.getElementsByClassName('column_check').length; i++) 
        {
            if( !document.getElementsByClassName('column_check')[i].innerHTML.includes('<i class='))
            {
                document.getElementsByClassName('column_check')[i].innerHTML = `<i class="fas fa-check-circle"></i>`+document.getElementsByClassName('column_check')[i].innerHTML;
                document.getElementsByClassName('column_check')[i].style.background = '#F4D03F';
                switch (i)
                {
                    case 0:
                         valueInput = 'IDENTIFICACION' ;
                    break;
                    case 1:
                         valueInput = 'NOMBRES' ;
                    break;
                    case 2:
                         valueInput = 'APELLIDOS' ;
                    break;
                    case 3:
                         valueInput = 'CORREO' ;
                    break;
                    case 4:
                         valueInput = 'CELULAR' ;
                    break;
                    case 5:
                         valueInput = 'TELEFONO' ;
                    break;
                    case 6:
                         valueInput = 'SEDE' ;
                    break;
                    case 7:
                         valueInput = 'ROLES' ;
                    break;   
                    case 8:
                         valueInput = 'DEPENDENCIA' ;
                    break;   
                }
                event.currentTarget.id = valueInput ;
                if( i === 8 )
                {
                    for (var k = 0; k < document.getElementsByClassName('column').length; k++)
                    {
                        if( !document.getElementsByClassName('column')[k].checked )
                        {
                            document.getElementsByClassName('column')[k].disabled='true';
                        }
                    }
                }
                return ;
            }
        }
    }
    else
    {
        switch (event.currentTarget.id)
        {
            case 'IDENTIFICACION':
                 valueNoInput = 'identificacion' ;
                 nombre = 'IDENTIFICACION' ;
            break;
            case 'NOMBRES':
                 valueNoInput = 'nombres' ;
                 nombre = 'NOMBRES' ;
            break;
            case 'APELLIDOS':
                 valueNoInput = 'apellido' ;
                 nombre = 'APELLIDOS' ;
            break;
            case 'CORREO':
                 valueNoInput = 'correo' ;
                 nombre = 'CORREO' ;
            break;
            case 'CELULAR':
                 valueNoInput = 'celular' ;
                 nombre = 'CELULAR' ;
            break;
            case 'TELEFONO':
                 valueNoInput = 'telefono' ;
                 nombre = 'TELEFONO' ;
            break;
            case 'SEDE':
                 valueNoInput = 'id_sede' ;
                 nombre = 'SEDE' ;
            break;
            case 'ROLES':
                 valueNoInput = 'id_tipo' ;
                 nombre = 'ROLES' ;
            break;
            case 'DEPENDENCIA':
                 valueNoInput = 'dependencia' ;
                 nombre = 'DEPENDENCIA' ;
            break;
        }
        document.getElementById(valueNoInput).innerHTML = `<div>${nombre.toUpperCase()}</div>`;
        document.getElementById(valueNoInput).style.background = 'white';
        for (var k = 0; k < document.getElementsByClassName('column').length; k++)
        {
            if( document.getElementsByClassName('column')[k].checked )
            {
                contador = contador + 1 ;
            }
        }
        if( contador >= 7 )
        {
            for (var k = 0; k < document.getElementsByClassName('column').length; k++)
            {
                if( !document.getElementsByClassName('column')[k].checked && document.getElementsByClassName('column')[k].id != event.currentTarget.id )
                {
                    document.getElementsByClassName('column')[k].disabled=!document.getElementsByClassName('column')[k].disabled; 
                }
            }
        }
        event.currentTarget.id = '' ;
    }    
}