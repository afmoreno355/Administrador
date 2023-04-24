/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


function valido(form)
{
   if( form.value !== '' || form.type === 'hidden' || form.required ===  false)
   {
        if( form.value !== undefined )
        {
            switch (form.type)
            {
                case 'number' : 
                    if( Number.isInteger(parseInt(form.value) ) )
                    {
                        return true;   
                    }
                    else
                    {
                        return false;
                    }
                break ;
                case 'text' :
                    return true;  
                break ;
                case 'checkbox' :
                    return true;  
                break ;
                case 'hidden' :
                    return true;  
                break ;
                case 'submit' :
                    return true;  
                break ;
                case 'fieldset' :
                    return true;  
                break ;
                case 'password' :
                    return true;  
                break ;
                case 'file' : 
                    return true;  
                break ;
                case 'date' : 
                    return true;  
                break ;
                case 'fieldset' : 
                    return true;  
                break ;
                case 'radio' : 
                    return true;  
                break ;
                case 'button' : 
                    return true;  
                break ;
                case 'email' : 
                    var valor = form.value.split('@');
                    if( valor.length >= 2 )
                    {
                        return true; 
                    }
                    else
                    {
                       return false;  
                    }
                break ;
                case 'color' : 
                    return true;  
                break ;
                case 'select-one' : 
                    if( form.value !== '' )
                    {
                        return true;   
                    }
                    else
                    {
                        return false;
                    }  
                break ;
                case 'textarea' : 
                    if( form.value !== '' )
                    {
                        return true;   
                    }
                    else
                    {
                        return false;
                    }  
                break ;
                default :
                    return false;
                break;   
            }
        }
        else 
        {
            return true ; 
        }
   }
   return false;
} 