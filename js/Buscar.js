/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function BuscarElementos(bucarPalabraClave = document.getElementById('bucarPalabraClave').value, eve = null, tab = null) {
    const form = document.querySelector('#formBuscar');
    const formData = new FormData(form);
    form.addEventListener('submit', function (e) {
        e.preventDefault();
    });
    addcadena = '';
    if (document.getElementById('sedeGestion') !== null)
    {
        addcadena += `&sedeGestion=${document.getElementById('sedeGestion').value}`;
    }
    if (document.getElementById('id_espe') !== null)
    {
        addcadena += `&id_espe=${document.getElementById('id_espe').value}`;
    }
    if (parseInt(document.getElementById('pag').innerHTML) != 1) {
        document.getElementById('pag').innerHTML = 1;
    }
    idexistentesReCa('', 'bucarPalabraClave=' + bucarPalabraClave.trim() + '&pagina=0&limit=5' + addcadena, 'tableIntT', `View/${document.getElementById('donde').value}/${document.getElementById('donde').value}Tabla`, eve, tab);
}
