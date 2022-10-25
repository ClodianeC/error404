<?php $niveau= '../';

include($niveau . 'inc/scripts/config.inc.php');

?>


<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Liste des artistes</title>
</head>

<body>
<?php include($niveau . 'inc/fragments/header.inc.php');; ?>

<h1>Artiste</h1>

<div class="tri-filtres">
    <h2 class="h2_tri">Trier :</h2>
    <form>
        <select name="tri" id="tri">
            <option value="Par défault">Par défaut</option>
            <option value="A-Z">A-Z</option>
            <option value="Z-A">Z-A</option>
            <option value="Par style">Par style</option>
        </select>
    </form>
    <h2 class="h2_tri">Filtrer par styles</h2>
    <form>
        <input type="checkbox" id="burlesque" name="style-filtre" value="burlesque">
        <label for="burlesque">Burlesque</label>
        <input type="checkbox" id="electro" name="style-filtre" value="electro">
        <label for="electro">Électro</label>
        <input type="checkbox" id="trash" name="style-filtre" value="trash">
        <label for="trash">Trash</label>
        <input type="checkbox" id="punk" name="style-filtre" value="punk">
        <label for="punk">Punk</label>
        <input type="checkbox" id="exprerimental" name="style-filtre" value="exprerimental">
        <label for="exprerimental">Exprérimental</label>
        <input type="checkbox" id="humour" name="style-filtre" value="humour">
        <label for="humour">Humour</label>
        <input type="checkbox" id="raggae" name="style-filtre" value="raggae">
        <label for="raggae">Raggae</label>
        <input type="checkbox" id="hip-hop" name="style-filtre" value="hip-hop">
        <label for="hip-hop">Hip-Hop</label>
        <input type="checkbox" id="rap" name="style-filtre" value="rap">
        <label for="rap">Rap</label>
        <input type="checkbox" id="folk" name="style-filtre" value="folk">
        <label for="folk">Folk</label>
        <input type="checkbox" id="country" name="style-filtre" value="country">
        <label for="country">Country</label>
        <input type="checkbox" id="franco" name="style-filtre" value="franco">
        <label for="franco">Franco</label>
        <input type="checkbox" id="indie" name="style-filtre" value="indie">
        <label for="indie">Indie</label>
        <input type="checkbox" id="pop" name="style-filtre" value="pop">
        <label for="pop">Pop</label>
    </form>
</div>
<ul class="liste-artistes">
    <li class="artistes">
        <picture class="artiste_img">

        </picture>
        <div class="artiste_info">
            <h2 class="artiste_info_nom"></h2>
            <p class="artiste_info_style"></p>
        </div>
    </li>
</ul>
<div class="controle-page">
    <a class="precedent inactif" href="">Précédent</a>
    <a class="page1 inactif" href="">1</a>
    <a class="page2 actif" href="">2</a>
    <a class="page3 actif" href="">3</a>
    <a class="page4 actif" href="">4</a>
    <a class="page5 actif" href="">5</a>
    <a class="page6 actif" href="">6</a>
    <a class="suivant actif" href="">Suivant</a>
</div>
</body>
</html>