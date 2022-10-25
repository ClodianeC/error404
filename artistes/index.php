<?php
//Définition du niveau du fichier dans l'arborescence
$niveau="../";

//Inclusion du fichier de config
include($niveau."inc/scripts/config.inc.php");

//Vérification si on a un no de page
//Si on a un no de page
if(isset($_GET["id_page"])){
    $id_page = $_GET["id_page"];
}
//Si on a pas de no de page
else{
    $id_page = 0;
}

//Établissement du nb d'artistes par pages
$intMaxArtistes = 4;

//Calcul premier artiste affiché
$enregistrementDepart = $id_page * $intMaxArtistes;

//Établissement de la requete pour connaitre le nb total d'artistes
$strRequeteNbArtistes = "SELECT COUNT(*) AS nbEnregistrementsArtistes FROM t_artiste";

//Initialisation de PDOStatement avec la requete pour le total
$pdosResultat = $pdoConnexion->prepare($strRequeteNbArtistes);
$pdosResultat->execute();

//Extraction du nombre total d'artistes à partir de la BD
$ligne = $pdosResultat->fetch();

//Enregistrement de la donnée dans une variable
$intNbArtiste = $ligne['nbEnregistrementsArtistes'];

//Libération de la requête du nombre total d'artistes
$pdosResultat->closeCursor();

//Calcul nb de pages (nbArtistes/maxArtistesParPages)
$nbPages = ceil($intNbArtiste / $intMaxArtistes);

//Vérification si on un style de sélectionné comme filtre
if (isset($_GET["id_style"])==true) {
    $strId_style = $_GET["id_style"];
}
else{
    $strId_style = 0;
}

//Établissement de la requête pour les artistes
//Si on a pas de filtre
if($strId_style == 0){
    $strRequeteArtiste="SELECT id_artiste, nom_artiste FROM t_artiste ORDER BY nom_artiste LIMIT $enregistrementDepart,$intMaxArtistes ;";
}
//Si on a un filtre
else{
    $strRequeteArtiste="SELECT DISTINCT t_artiste.id_artiste, nom_artiste FROM ti_style_artiste INNER JOIN t_artiste ON ti_style_artiste.id_artiste=t_artiste.id_artiste WHERE ti_style_artiste.id_style=$strId_style ORDER BY nom_artiste LIMIT $enregistrementDepart,$intMaxArtistes ";
}

//Initialisation de PDOStatement avec la requête
$pdosResultat = $pdoConnexion->prepare($strRequeteArtiste);
$pdosResultat->execute();

$arr_artiste=array();
$ligne=$pdosResultat->fetch();

//Extraction des données de la BD (pour les artistes)
for($intCptEnr=0;$intCptEnr<$pdosResultat->rowCount();$intCptEnr++) {
    $arr_artiste[$intCptEnr]['id_artiste'] = $ligne['id_artiste'];
    $arr_artiste[$intCptEnr]['nom_artiste'] = $ligne['nom_artiste'];

    //Établissement de la deuxième requête (pour les styles liés à l'artiste)
    $strRequeteStyle="SELECT nom_style FROM t_style INNER JOIN ti_style_artiste ON ti_style_artiste.id_style=t_style.id_style WHERE id_artiste=".$ligne['id_artiste'];

    //Initialisation de PDOStatement avec la nouvelle requête
    $pdosSousResultat = $pdoConnexion->prepare($strRequeteStyle);
    $pdosSousResultat->execute();

    $ligneStyle = $pdosSousResultat->fetch();
    $strStyles="";

    //Extraction des données de la BD (pour les styles)
    for($intCptStyle=0;$intCptStyle<$pdosSousResultat->rowCount();$intCptStyle++){
        if($strStyles != ""){
            $strStyles = $strStyles . ", ";
        }
        $strStyles = $strStyles . $ligneStyle['nom_style'];
        $ligneStyle = $pdosSousResultat->fetch();
    }

    //Libération de la requête Styles
    $pdosSousResultat->closeCursor();
    //Création d'une section de tableau pour style
    $arr_artiste[$intCptEnr]['style_artiste'] = $strStyles;

//Nouvel artiste
    $ligne=$pdosResultat->fetch();
}

//Libération de la requête Artistes
$pdosResultat->closeCursor();

//Requete styles
$strRequeteListeStyle="SELECT id_style, nom_style FROM t_style";
$pdosResultat = $pdoConnexion->query($strRequeteListeStyle);

for($intCptStyle = 0; $rangee=$pdosResultat->fetch();$intCptStyle++){
    $arr_style[$intCptStyle]["id_style"]=$rangee["id_style"];
    $arr_style[$intCptStyle]["nom_style"]=$rangee["nom_style"];
}

$str_requeteArtisteComplet = "SELECT id_artiste, nom_artiste FROM t_artiste";
$pdosResultat = $pdoConnexion->query($str_requeteArtisteComplet);
$arr_artisteComplet = array();

for($intCptStyle = 0; $rangee=$pdosResultat->fetch();$intCptStyle++){
    $arr_artisteComplet[$intCptStyle]["id_artiste"]=$rangee["id_artiste"];
    $arr_artisteComplet[$intCptStyle]["nom_artiste"]=$rangee["nom_artiste"];
}

$arr_randomArtisteSugg = [-1,-1,-1];
for($intCptRand = 0; $intCptRand<3 && $intCptRand<count($arr_artisteComplet); $intCptRand++){
    $int_randSugg = rand(0,(count($arr_artisteComplet)-1));
    while($int_randSugg==$arr_randomArtisteSugg[0] || $int_randSugg==$arr_randomArtisteSugg[1]){
        $int_randSugg = rand(0,(count($arr_artisteComplet)-1));
    }
    $arr_randomArtisteSugg[$intCptRand] = $int_randSugg;
}
?>


<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Festival OFF - Liste des artistes</title>
</head>

<body>
<?php include($niveau . 'inc/fragments/header.inc.php'); ?>

<h1>Artistes
    <?php
    if(isset($_GET["id_style"])){
    echo " du style ".$arr_style[($_GET["id_style"]-1)]["nom_style"];
    }
    ?></h1>

<div class="tri-filtres">
    <h2 class="h2_tri">Trier :</h2>
    <form class="tri_formulaire">
        <select name="tri" id="tri" class="tri_liste-deroulante">
            <option class="tri_choix" value="Par défault">Par défaut</option>
            <option class="tri_choix" value="A-Z">A-Z</option>
            <option class="tri_choix" value="Z-A">Z-A</option>
            <option class="tri_choix" value="Par style">Par style</option>
        </select>
    </form>
    <h2 class="h2_tri">Filtrer par styles</h2>
    <form class="filtre_formulaire">
        <input class="checkbox-style" type="checkbox" id="burlesque" name="style-filtre" value="burlesque">
        <label class="label_checkbox-style" for="burlesque">Burlesque</label>
        <input class="checkbox-style" type="checkbox" id="electro" name="style-filtre" value="electro">
        <label class="label_checkbox-style" for="electro">Électro</label>
        <input class="checkbox-style" type="checkbox" id="trash" name="style-filtre" value="trash">
        <label class="label_checkbox-style" for="trash">Trash</label>
        <input class="checkbox-style" type="checkbox" id="punk" name="style-filtre" value="punk">
        <label class="label_checkbox-style" for="punk">Punk</label>
        <input class="checkbox-style" type="checkbox" id="exprerimental" name="style-filtre" value="exprerimental">
        <label class="label_checkbox-style" for="exprerimental">Exprérimental</label>
        <input class="checkbox-style" type="checkbox" id="humour" name="style-filtre" value="humour">
        <label class="label_checkbox-style" for="humour">Humour</label>
        <input class="checkbox-style" type="checkbox" id="raggae" name="style-filtre" value="raggae">
        <label class="label_checkbox-style" for="raggae">Raggae</label>
        <input class="checkbox-style" type="checkbox" id="hip-hop" name="style-filtre" value="hip-hop">
        <label class="label_checkbox-style" for="hip-hop">Hip-Hop</label>
        <input class="checkbox-style" type="checkbox" id="rap" name="style-filtre" value="rap">
        <label class="label_checkbox-style" for="rap">Rap</label>
        <input class="checkbox-style" type="checkbox" id="folk" name="style-filtre" value="folk">
        <label class="label_checkbox-style" for="folk">Folk</label>
        <input class="checkbox-style" type="checkbox" id="country" name="style-filtre" value="country">
        <label class="label_checkbox-style" for="country">Country</label>
        <input class="checkbox-style" type="checkbox" id="franco" name="style-filtre" value="franco">
        <label class="label_checkbox-style" for="franco">Franco</label>
        <input class="checkbox-style" type="checkbox" id="indie" name="style-filtre" value="indie">
        <label class="label_checkbox-style" for="indie">Indie</label>
        <input class="checkbox-style" type="checkbox" id="pop" name="style-filtre" value="pop">
        <label class="label_checkbox-style" for="pop">Pop</label>
    </form>
    <button class="reinitialiser">Réinitialiser  </button>
</div>
<ul class="liste-artistes">
    <?php
    for($intCpt=0;$intCpt<count($arr_artiste);$intCpt++){
    ?>
    <li class="artistes">
        <picture class="artiste_img">
    <img src="../images/image.jpg">
        </picture>
        <div class="artiste_info">
            <h2 class="artiste_info_nom"><?php echo $arr_artiste[$intCpt]["nom_artiste"]?></h2>
            <p class="artiste_info_style"><?php echo $arr_artiste[$intCpt]["style_artiste"]?></p>
        </div>
    </li>
    <?php
    }
    ?>
</ul>
<?php
if(isset($_GET["id_style"])){
    //Déterminer max page si filtre
    $strRequeteNbArtistesFiltres = "SELECT COUNT(*) AS nbEnregistrementsArtistesFiltres FROM ti_style_artiste WHERE id_style=".$_GET["id_style"];
    $pdosResultat = $pdoConnexion->prepare($strRequeteNbArtistesFiltres);
    $pdosResultat->execute();
    $ligne = $pdosResultat->fetch();
    $intNbArtisteFiltre = $ligne['nbEnregistrementsArtistesFiltres'];
    $pdosResultat->closeCursor();
    $nbPagesFiltre = ceil($intNbArtisteFiltre / $intMaxArtistes);
    if($id_page<($nbPagesFiltre-1)){
        $str_queryPageSuivante="./index.php?id_style=".$_GET["id_style"]."&id_page=".($id_page+1);
    }
    if($id_page>0){
        $str_queryPagePrecedente="./index.php?id_style=".$_GET["id_style"]."&id_page=".($id_page-1);
    }
    echo "Page ".($id_page+1)?> de <?php echo $nbPagesFiltre;
}

//Si il n'y en a pas
else{
    if($id_page<($nbPages-1)){
        $str_queryPageSuivante="./index.php?id_page=".($id_page+1);
    }
    if($id_page>0) {
        $str_queryPagePrecedente = "./index.php?id_page=".($id_page-1);
    }
    echo "Page ".($id_page+1)?> de <?php echo $nbPages;
}
?>
<div class="controle-page">
    <a class="precedent inactif" href="<?php echo $str_queryPagePrecedente ?>">Précédent</a>

    <?php
    for($intCptPagination=0; $intCptPagination<$nbPages; $intCptPagination++){
        echo "<a class='page1 actif' href='./index.php?id_page=$intCptPagination'>".($intCptPagination+1)."</a>";
        echo " ";
    }
    ?>

    <a class="suivant actif" href="<?php echo $str_queryPageSuivante ?>">Suivant</a>
</div>
<?php include($niveau . 'inc/fragments/footer.inc.php');; ?>
</body>
</html>