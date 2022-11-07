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

//Requete artistes complets
$str_requeteArtisteComplet = "SELECT id_artiste, nom_artiste FROM t_artiste";
$pdosResultat = $pdoConnexion->query($str_requeteArtisteComplet);
$arr_artisteComplet = array();

for($intCptArtistesC = 0; $rangee=$pdosResultat->fetch();$intCptArtistesC++){
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

<div>
<?php include($niveau . 'inc/fragments/header.inc.php'); ?>

<div class="tri-filtres">
    <div class="section-tri">
        <h2 class="h2_tri">Trier :</h2>
        <form class="tri_formulaire">
            <select name="tri" id="tri" class="tri_liste-deroulante">
                <option class="tri_choix" value="Par défault">Par défaut</option>
                <option class="tri_choix" value="A-Z">A-Z</option>
                <option class="tri_choix" value="Z-A">Z-A</option>
                <option class="tri_choix" value="Par style">Par style</option>
            </select>
        </form>
    </div>
    <div class="section-filtre">
        <h2 class="h2_tri">Filtrer par styles :</h2>
        <form class="filtre_formulaire">
            <?php
            for($intCptFiltre = 0; $intCptFiltre < count($arr_style); $intCptFiltre++){
                $str_style = $arr_style[$intCptFiltre]["nom_style"];
                echo "<li class='filtre_element'><input class='checkbox-style' type='checkbox' id='".$str_style."' name='style-filtre' value='".$str_style."'>";
                echo "<label class='label_checkbox-style'for='".$str_style."'>$str_style</label></li>";
            }
            ?>
        </form>
    </div>

    <button class="bouton appliquer"><p>Appliquer</p></button>
    <button class="bouton reinitialiser"><p>Réinitialiser</p></button>
</div>
<div class="titre">
    <div class="h1_deco">
        <h1 class="h1">Artistes
            <?php
            if(isset($_GET["id_style"])){
                echo " du style ".$arr_style[($_GET["id_style"]-1)]["nom_style"];
            }
            ?>
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
        if(strlen($arr_artiste[$intCpt]["nom_artiste"])>15){
            $str_classLongTitre = "longTitre";
        }
        $str_queryArtiste = "./fiche/index.php?id_artiste=".$arr_artiste[$intCpt]['id_artiste'];
    ?>
    <li class="artistes <?php echo $str_classArtistePair?>">
        <div class="artistes_deco">
            <a class="artistes_lien" href="<?php echo $str_queryArtiste ?>">
                <picture class="artiste_img">
                    <?php echo "<img src='../images/liste-artistes/artistes/".$arr_artiste[$intCpt]['id_artiste']."_w520.jpg' srcset='../images/liste-artistes/artistes/".$arr_artiste[$intCpt]['id_artiste']."_w260.jpg 1x, ../images/liste-artistes/artistes/".$arr_artiste[$intCpt]['id_artiste']."_w520.jpg 2x'>"; ?>
                </picture>
            </a>
        </div>
        <div class="artiste_info">
            <h3 class="artiste_info_nom <?php echo $str_classLongTitre ?>"><?php echo $arr_artiste[$intCpt]["nom_artiste"]?></h3>
            <p class="artiste_info_style"><?php echo $arr_artiste[$intCpt]["style_artiste"]?></p>
        </div>

    </li>
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
            if(strlen($arr_artisteComplet[$int_randFinal]["nom_artiste"])>12){
                $str_classLongTitre = "longTitre";
            }
            ?>
            <li class="artistes-vedettes <?php echo $str_classArtistePair?>">
                <div class="artistes-vedettes_deco">
                    <a class="artistes-vedettes_lien" href="<?php echo $str_queryVedette ?>">
                        <picture class="artiste-vedettes_img">
                            <?php echo "<img src='../images/liste-artistes/artistes/".$arr_artisteComplet[$int_randFinal]['id_artiste']."_w520.jpg' srcset='../images/liste-artistes/artistes/".$arr_artisteComplet[$int_randFinal]['id_artiste']."_w260.jpg 1x, ../images/liste-artistes/artistes/".$arr_artisteComplet[$int_randFinal]['id_artiste']."_w520.jpg 2x'>"; ?>
                        </picture>
                    </a>
                </div>
                <div class="artiste-vedettes_info">
                    <h3 class="artiste-vedettes_info_nom <?php echo $str_classLongTitre ?>"><?php echo $arr_artisteComplet[$int_randFinal]["nom_artiste"]?></h3>
                    <p class="artiste-vedettes_info_style"><?php echo $arr_artisteComplet[$int_randFinal]["style_artiste"]?></p>
                </div>

            </li>
            <?php
        }
    }
echo "</ul>";

echo "<div class='controles_div_total'><div class='controles_deco'><div class='controle-page'>";
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
    for($intCptPagination=0; $intCptPagination<$nbPages; $intCptPagination++){
        $str_classActif = "";
        $href = "";
        if($intCptPagination == $id_page){
            $str_classActif = "inactif";
        }
        else{
            $str_classActif = "actif";
            $href = "href='./index.php?id_page=$intCptPagination'";
        }
        $int_pageLien = $intCptPagination+1;
        echo "<a class='page $str_classActif' $href>".$int_pageLien."</a>";
        echo " ";
    }
    ?>

    <a class="<?php echo $str_classPageSuivante ?>" <?php echo $hrefSuivante ?>">Suivant</a>
</div>
</div>
</div>
<?php include($niveau . 'inc/fragments/footer.inc.php');; ?>
</body>
</html>