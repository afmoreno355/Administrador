<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
session_start();
    
$lista='';
$bucarPalabraClave='';
$filtro='';

require_once dirname(__FILE__)."/../../classes/ConectorBD.php";
require_once dirname(__FILE__)."/../../classes/Persona.php";

foreach ($_POST as $key => $value) ${$key}=$value;

if ($bucarPalabraClave!='') {
    $filtro=" and identificacion like '%".strtoupper($bucarPalabraClave)."%' OR nombres like '%".strtoupper($bucarPalabraClave)."%' OR apellidos like '%".strtoupper($bucarPalabraClave)."%' OR correoinstitucional like '%".strtoupper($bucarPalabraClave)."%' OR idsede like '%$bucarPalabraClave%' ";
}

$datos=  Persona::datosobjetos($filtro, $pagina, 5);

$numeroPaginas=ceil(Persona::count($filtro)[0][0]/5);

for ($i = 0; $i < count($datos); $i++) {
    $objeto=$datos[$i];
        $lista.="<tr>";
        $lista.="<td>{$objeto->getId()}</td>";
        $lista.="<td class='noDisplay'>{$objeto->getNombre()}</td>";
        $lista.="<td class='noDisplay'>{$objeto->getApellido()}</td>";
        $lista.="<td class='noDisplay'>{$objeto->getTel()}</td>";
        $lista.="<td>
                      <pre> <input type='button' id='button' name='1' title='Modificar' value='MODIFICAR' onclick='validarDatosMod({$objeto->getId()})'></pre> 
                      <pre><input type='button' id='button' name='3' onclick='Eliminar({$objeto->getId()})' title='Eliminar' value='ELIMINAR'></pre>
                 </td>
                 <td>   
                       <pre> <input type='button' id='button' name='3' onclick='MostrarDatos({$objeto->getId()})' title='Mas Informacion Usuario' value='INFO'></pre> 
                </td>
                </tr>";
}
?>
    <tr>        
    <th>Identificacion</th>
    <th class='noDisplay'>Nombres</th>
    <th class='noDisplay'>Apellidos</th>
    <th class='noDisplay'>Telefono</th>
    <th colspan="2">
        <pre><input type='hidden' id='sede' value="<?=$sede?>"></pre>
        <pre><input type='button' id='button' name="2" title="Adicionar" value="ADICIONAR" onclick="validarDatos('','check=admin&user= &id=2&accion=ADICIONAR&donde=inicio.php?CONTENIDO=View/Usuario/Usuarios.php&sede='+document.getElementById('sede').value,'modalVentana','View/Usuario/UsuarioFormulario.php')"/></pre> 
        <pre><input type='button' id='button' name='2' title='Subir Archivo Plano' value='A. PLANOS' onclick="validarDatos('','id=8&accion=A. PLANO','modalVentana','View/Usuario/UsuarioFormulario.php')"/></pre> 
        <pre><input type='hidden' id='numeroPaginas' value="<?=$numeroPaginas?>"></pre> 
        <pre><input type='hidden' id='bucarPalabraClave' value="<?=$bucarPalabraClave?>"></pre> 
    </th>
    </tr>
    <?=$lista?>


