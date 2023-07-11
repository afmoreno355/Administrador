
<link rel="stylesheet" href="css/menu_left.css">

<div class="estado" id="divmenu2">
    <label class="fas fa-bars la1" for="chequear">
    <input type="checkbox" name="chek" id='chequear' onclick='menuDesplegableIzq();' style="display: none">
    <!--<img src="img/logo/logob2.jpg">-->
</div>
<header id="cont_menu" class="cont_menu">
    <img id="mi_foto" src="<?= $_SESSION['foto'] ?>"  />
    <nav id="nav_content">
        <?= $_SESSION['miMenu2'] ?>
    </nav>
</header>


