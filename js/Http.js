/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


window.addEventListener('load', () =>
{
    evaluar();
});
  
window.addEventListener("hashchange", (ev) =>
{
    for (var i = 0; i < document.getElementsByClassName('color').length; i++) 
    {
       document.getElementsByClassName('color')[i].className = 'menua'; 
    }
    evaluar(); 
});
    
function evaluar( id = '' ) 
{
    var donde = '';
    var nombre = '';
    var location = window.location.hash.toString().toUpperCase();
    for (var i = 0; i < json.length; i++)
    {
        if ( json[i]['URL'].includes( location ) )
        {
            donde = json[i]['DONDE'];
            nombre = json[i]['NOMBRE'];
        }
    }
    if (document.getElementById(nombre).className !== 'menua color')
    {
        document.getElementById(nombre).className += " color";
    }
    idexistentesReCa('',`pagina=0${id}`,'tableIntT',donde, null, null);
}