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
$arr_tri[0] = array();
$arr_tri[0]["value"] = "defaut";
$arr_tri[0]["nom"] = "Par défaut";
$arr_tri[1] = array();
$arr_tri[1]["value"] = "za";
$arr_tri[1]["nom"] = "Z-A";
$arr_tri[2] = array();
$arr_tri[2]["value"] = "az";
$arr_tri[2]["nom"] = "A-Z";


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

$strId_style = "";
$intCptS = 0;
$str_queryPageStyles = "";
$str_queryPageTri = "";
//Vérification si on un style de sélectionné comme filtre
if (isset($_GET["id_style"])==true) {
    foreach($_GET["id_style"] as $valueS){
        if($intCptS!=0){
            $strId_style = $strId_style.", ";
            $str_queryPageStyles = $str_queryPageStyles."&";

        }
        $strId_style = $strId_style.$valueS;
        $str_queryPageStyles = $str_queryPageStyles."id_style%5B%5D=".$valueS;

        $intCptS ++;
    }
}
else{
    $strId_style = 0;
}

if (isset($_GET["tri"])==true) {
    $str_tri = "ORDER BY ";
    switch ($_GET["tri"]){
        case "az":
            $str_tri = $str_tri."nom_artiste";
            break;
        case "za":
            $str_tri = $str_tri."nom_artiste DESC";
            break;
        default:
            $str_tri = "";
    }
    $str_queryPageTri = "tri=".$_GET["tri"]."&";
}
else{
    $str_tri = "";
}

//Établissement de la requête pour les artistes
//Si on a pas de filtre
if($strId_style == 0){
    $strRequeteArtiste="SELECT id_artiste, nom_artiste FROM t_artiste $str_tri LIMIT $enregistrementDepart,$intMaxArtistes ;";
}
//Si on a un filtre
else{
    $strRequeteArtiste="SELECT DISTINCT t_artiste.id_artiste, nom_artiste FROM ti_style_artiste INNER JOIN t_artiste ON ti_style_artiste.id_artiste=t_artiste.id_artiste WHERE ti_style_artiste.id_style IN($strId_style) $str_tri LIMIT $enregistrementDepart,$intMaxArtistes ";
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

//Requete artistes complets
$str_requeteArtisteComplet = "SELECT id_artiste, nom_artiste FROM t_artiste";
$pdosResultatC = $pdoConnexion->query($str_requeteArtisteComplet);
$arr_artisteComplet = array();

for($intCptArtistesC = 0; $rangee=$pdosResultatC->fetch();$intCptArtistesC++){
    $arr_artisteComplet[$intCptArtistesC]["id_artiste"]=$rangee["id_artiste"];
    $arr_artisteComplet[$intCptArtistesC]["nom_artiste"]=$rangee["nom_artiste"];

    //Établissement de la deuxième requête (pour les styles liés à l'artiste)
    $strRequeteStyleV="SELECT nom_style FROM t_style INNER JOIN ti_style_artiste ON ti_style_artiste.id_style=t_style.id_style WHERE id_artiste=".$rangee['id_artiste'];

    //Initialisation de PDOStatement avec la nouvelle requête
    $pdosSousResultatV = $pdoConnexion->prepare($strRequeteStyleV);
    $pdosSousResultatV->execute();

    $ligneStyleV = $pdosSousResultatV->fetch();
    $strStylesV="";

    //Extraction des données de la BD (pour les styles)
    for($intCptStyleV=0;$intCptStyleV<$pdosSousResultatV->rowCount();$intCptStyleV++){
        if($strStylesV != ""){
            $strStylesV = $strStylesV . ", ";
        }
        $strStylesV = $strStylesV . $ligneStyleV['nom_style'];
        $ligneStyleV = $pdosSousResultatV->fetch();
    }

    //Libération de la requête Styles
    $pdosSousResultatV->closeCursor();
    //Création d'une section de tableau pour style
    $arr_artisteComplet[$intCptArtistesC]['style_artiste'] = $strStylesV;

//Nouvel artiste
    $ligne=$pdosResultat->fetch();
}
//Libération de la requête Artistes complets
$pdosResultat->closeCursor();

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
    <?php include($niveau . 'inc/fragments/head-links.html'); ?>
    <link rel="stylesheet" href="<?php echo $niveau ?>css/style-clodiane.css">
</head>
<body>
<div>
<?php include($niveau . 'inc/fragments/header.inc.php'); ?>

<form class="tri-filtres" action="index.php" method="get">
    <div class="section-tri">
        <h2 class="h2_tri">Trier:</h2>
        <div class="tri_formulaire">
            <select name="tri" id="tri" class="tri_liste-deroulante">
            <?php
                for($intCptTri = 0; $intCptTri<count($arr_tri);$intCptTri++){
                    $strSelected = "";
                    $strTriActu = $arr_tri[$intCptTri]["value"];
                    if(isset($_GET["tri"])){
                        if($strTriActu == $_GET["tri"]){
                            $strSelected = "selected";
                        }
                    }
                    if(isset($_GET["btn_reinitialiser"])){
                        if($intCptTri==0){
                            $strSelected="selected";
                        }
                        else{
                            $strSelected="";
                        }
                    }
                    echo "<option class='tri_choix' value='".$arr_tri[$intCptTri]["value"]."' $strSelected>".$arr_tri[$intCptTri]["nom"]."</option>";
                }
            ?>
            </select>
        </div>
    </div>
    <div class="section-filtre">
        <h2 class="h2_tri">Filtrer par styles:</h2>
        <div class="filtre_formulaire">
            <?php
            for($intCptFiltre = 0; $intCptFiltre < count($arr_style); $intCptFiltre++){
                $str_style = $arr_style[$intCptFiltre]["nom_style"];
                $id_style = $arr_style[$intCptFiltre]["id_style"];
                $strChecked = "";
                if(isset($_GET["id_style"])){
                    for($intCptFiltreStyle=0; $intCptFiltreStyle<count($_GET["id_style"]); $intCptFiltreStyle++){
                        if($id_style == $_GET["id_style"][$intCptFiltreStyle]){
                            $strChecked = "checked";
                        }
                    }
                }
                if(isset($_GET["btn_reinitialiser"])){
                    $strChecked="";
                }
                echo "<li class='filtre_element'><input class='checkbox-style' type='checkbox' id='".$str_style."' name='id_style[]' value='".$id_style."' $strChecked>";
                echo "<label class='label_checkbox-style'for='".$str_style."'>$str_style</label></li>";
            }

            ?>
        </div>
    </div>

    <button type="submit" class="bouton appliquer" name="btn_appliquer" id="btn_appliquer" value="appliquer"><p>Appliquer</p></button>
    <button type="submit" class="bouton reinitialiser" name="btn_reinitialiser" id="btn_reinitialiser" value="reinitialisation"><p>Réinitialiser</p></button>
</form>
<div class="titre">
    <div class="h1_deco">
        <h1 class="h1">Artistes
<!--            --><?php
//            if(isset($_GET["id_style"])){
//                echo " du style ".$arr_style[($_GET["id_style"]-1)]["nom_style"];
//            }
//            ?>
        </h1>
    </div>
</div>
    <h2 class="h2 titrePage">Page <?php echo $id_page+1?></h2>
<ul class="liste-artistes">
    <?php
    for($intCpt=0;$intCpt<count($arr_artiste);$intCpt++){
        $str_classArtistePair = "pair";
        $str_classLongTitre = "";
        if($intCpt%2==0){
            $str_classArtistePair = "impair";
        }
        if(strlen($arr_artiste[$intCpt]["nom_artiste"])>=15){
            $str_classLongTitre = "longTitre";
        }
        $str_queryArtiste = "./fiche/index.php?id_artiste=".$arr_artiste[$intCpt]['id_artiste'];
    ?>
    <a class="artistes_lien <?php echo $str_classArtistePair?>" href="<?php echo $str_queryArtiste ?>">
        <li class="artistes <?php echo $str_classArtistePair?>">
            <div class="artistes_deco">
                <picture class="artiste_img">
                    <?php
                    echo "<source srcset='../images/liste-artistes/artistes/".$arr_artiste[$intCpt]['id_artiste']."_w260.jpg 1x, ../images/liste-artistes/artistes/".$arr_artiste[$intCpt]['id_artiste']."_w520.jpg 2x'>";
                    echo "<img src='../images/liste-artistes/artistes/".$arr_artiste[$intCpt]['id_artiste']."_w520.jpg' alt='Image représentant ".$arr_artiste[$intCpt]["nom_artiste"]."'>";
                    ?>
                </picture>
            </div>
            <div class="artiste_info">
                <h3 class="artiste_info_nom <?php echo $str_classLongTitre ?>"><?php echo $arr_artiste[$intCpt]["nom_artiste"]?></h3>
                <p class="artiste_info_style"><?php echo $arr_artiste[$intCpt]["style_artiste"]?></p>
            </div>
        </li>
    </a>
    <?php
    }
    ?>
</ul>
    <h2 class="h2 titreVedette">En vedette</h2>

<?php
echo "<ul class='liste_artistes-vedettes'>";
    for($intCptVedette = 0; $intCptVedette<3; $intCptVedette++){
        if($arr_randomArtisteSugg[$intCptVedette] != -1){
            $int_randFinal = $arr_randomArtisteSugg[$intCptVedette];
            $str_queryVedette = "./fiche/index.php?id_artiste=".$arr_artisteComplet[$int_randFinal]["id_artiste"];
            $str_classArtistePair = "pair";
            $str_classLongTitre = "";
            if($intCptVedette%2==0){
                $str_classArtistePair = "impair";
            }
            if(strlen($arr_artisteComplet[$int_randFinal]["nom_artiste"])>=11){
                $str_classLongTitre = "longTitre";
                if(strlen($arr_artisteComplet[$int_randFinal]["nom_artiste"])>=15){
                    $str_classLongTitre = "tresLongTitre";
                }

            }
            ?>
            <a class="artistes-vedettes_lien" href="<?php echo $str_queryVedette ?>">
                <li class="artistes-vedettes <?php echo $str_classArtistePair?>">
                    <div class="artistes-vedettes_deco">
                        <picture class="artiste-vedettes_img">
                            <?php echo "<img src='../images/liste-artistes/artistes/".$arr_artisteComplet[$int_randFinal]['id_artiste']."_w520.jpg' srcset='../images/liste-artistes/artistes/".$arr_artisteComplet[$int_randFinal]['id_artiste']."_w260.jpg 1x, ../images/liste-artistes/artistes/".$arr_artisteComplet[$int_randFinal]['id_artiste']."_w520.jpg 2x'>"; ?>
                        </picture>
                    </div>
                    <div class="artiste-vedettes_info">
                        <h3 class="artiste-vedettes_info_nom <?php echo $str_classLongTitre ?>"><?php echo $arr_artisteComplet[$int_randFinal]["nom_artiste"]?></h3>
                        <p class="artiste-vedettes_info_style"><?php echo $arr_artisteComplet[$int_randFinal]["style_artiste"]?></p>
                    </div>
                </li>
            </a>

<?php
        }
    }
echo "</ul>";

echo "<div class='controles_div_total'><div class='controles_deco'><div class='controle-page'>";
$nbPagesFiltre = $nbPages;
if(isset($_GET["id_style"])){
    //Déterminer max page si filtre
    $strRequeteNbArtistesFiltres = "SELECT COUNT(*) AS nbEnregistrementsArtistesFiltres FROM ti_style_artiste WHERE id_style IN(".$strId_style.")";
    $pdosResultat = $pdoConnexion->prepare($strRequeteNbArtistesFiltres);
    $pdosResultat->execute();
    $ligne = $pdosResultat->fetch();
    $intNbArtisteFiltre = $ligne['nbEnregistrementsArtistesFiltres'];
    $pdosResultat->closeCursor();
    $nbPagesFiltre = ceil($intNbArtisteFiltre / $intMaxArtistes);
    if($id_page<($nbPagesFiltre-1)){
        $hrefSuivante = "./index.php?".$str_queryPageTri.$str_queryPageStyles."&id_page=".($id_page+1);
        $str_classPageSuivante="suivante actif";
    }
    else{
        $hrefSuivante = "";
        $str_classPageSuivante="suivante inactif";
    }
    if($id_page>0){
        $hrefPrecedente = "./index.php?".$str_queryPageTri.$str_queryPageStyles."&id_page=".($id_page-1);
        $str_classPagePrecedente="precedente actif";
    }
    else{
        $hrefPrecedente = "";
        $str_classPagePrecedente="precedente inactif";
    }
    echo "<p class='indicateur-page'>Page ".($id_page+1)?> de <?php echo $nbPagesFiltre."</p>";
}

//Si il n'y en a pas
else{
    if($id_page<($nbPages-1)){
        $hrefSuivante="href='./index.php?id_page=".($id_page+1)."'";
        $str_classPageSuivante="suivante actif";
    }
    else{
        $hrefSuivante = "";
        $str_classPageSuivante="suivante inactif";
    }
    if($id_page>0) {
        $hrefPrecedente = "href='./index.php?id_page=".($id_page-1)."'";
        $str_classPagePrecedente="precedente actif";
    }
    else{
        $hrefPrecedente = "";
        $str_classPagePrecedente="precedente inactif";
    }
    echo "<p class='indicateur-page'>Page ".($id_page+1)?> de <?php echo $nbPages."</p>";
}
?>

    <a class="<?php echo $str_classPagePrecedente ?>" <?php echo $hrefPrecedente ?>">Précédent</a>
    <?php
    for($intCptPagination=0; $intCptPagination<$nbPages && $intCptPagination<$nbPagesFiltre; $intCptPagination++){
        $str_classActif = "";
        $href = "";
        if($intCptPagination == $id_page){
            $str_classActif = "inactif";
        }
        else{
            $str_classActif = "actif";
            if(isset($_GET["id_style"])){
                $href = "href='./index.php?".$str_queryPageTri.$str_queryPageStyles."&id_page=$intCptPagination'";
            }
            else{
                $href = "href='./index.php?".$str_queryPageTri."&id_page=$intCptPagination'";
            }

        }
        $int_pageLien = $intCptPagination+1;
        echo "<a class='page $str_classActif' $href>".$int_pageLien."</a>";
        echo " ";
    }
    ?>

    <a class="<?php echo $str_classPageSuivante ?>" <?php echo $hrefSuivante ?>">Suivant</a>
</div>
<?php include($niveau . 'inc/fragments/footer.inc.php');; ?>
</body>
</html>