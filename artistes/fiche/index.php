<?php

$niveau= '../../';

include($niveau . 'inc/scripts/config.inc.php');

if(isset($_GET['id_artiste']) == true) {
    $strIdArtiste = $_GET['id_artiste'];
}else{
    //Dans le cas ou il n'y a pas de Querystring
    $strIdArtiste = 5;
}
//Détection de idSport dans la querystring?
if(isset($_GET['idStyle']) == true) {
    $strIdStyle = $_GET['idStyle'];
}else{
    //Dans le cas ou il n'y a pas de Querystring
    $strIdStyle = 0;
}

$arrMoisFr=array('janvier', 'février', 'mars', 'avril', 'mai', 'juin', 'juillet', 'août', 'septembre', 'octobre', 'novembre', 'décembre');

//Établissement de la chaine de requête
$strRequete = 'SELECT ti_evenement.id_artiste, HOUR(date_et_heure) AS heure, MINUTE(date_et_heure) AS minutes, DAY(date_et_heure) AS jour, MONTH(date_et_heure) AS mois, YEAR(date_et_heure) AS annee, t_lieu.nom_lieu, t_lieu.id_lieu
FROM ti_evenement
INNER JOIN t_lieu ON ti_evenement.id_lieu = t_lieu.id_lieu
WHERE ti_evenement.id_artiste = ' . $strIdArtiste . '
ORDER BY date_et_heure ASC';

//Exécution de la requête
$pdosResultat = $pdoConnexion->query($strRequete);

$arrArtistes = array();
//$ligne = $pdosResultat->fetch();
//Extraction des informations sur les styles
for($cptEnr=0;$ligne=$pdosResultat->fetch();$cptEnr++){
    $arrArtistes[$cptEnr]['minutes'] = $ligne['minutes'];
    $arrArtistes[$cptEnr]['heure'] = $ligne['heure'];
    $arrArtistes[$cptEnr]['jour'] = $ligne['jour'];
    $arrArtistes[$cptEnr]['mois'] = $ligne['mois'];
    $arrArtistes[$cptEnr]['annee'] = $ligne['annee'];
    $arrArtistes[$cptEnr]['id_lieu'] = $ligne['id_lieu'];
    $arrArtistes[$cptEnr]['nom_lieu'] = $ligne['nom_lieu'];
}

//var_dump($arrArtistes);

//Établissement de la chaine de la 2e requête pour tous les sports
$strRequeteInfos = 'SELECT id_artiste, nom_artiste, provenance, description, site_web_artiste
                    FROM t_artiste
                    WHERE id_artiste='. $strIdArtiste;

//Exécution de la 2e requête
$pdosResultat = $pdoConnexion->query($strRequeteInfos);

$arrInfos = array();
//Extraction des informations sur les infos
for($cptEnr=0;$ligne=$pdosResultat->fetch();$cptEnr++) {
    $arrInfos[$cptEnr]['id_artiste'] = $ligne['id_artiste'];
    $arrInfos[$cptEnr]['nom_artiste'] = $ligne['nom_artiste'];
    $arrInfos[$cptEnr]['provenance'] = $ligne['provenance'];
    $arrInfos[$cptEnr]['description'] = $ligne['description'];
    $arrInfos[$cptEnr]['site_web_artiste'] = $ligne['site_web_artiste'];

    // Sous-requête (afficher les styles de l'artiste)
    $strRequete = 'SELECT t_style.nom_style, ti_style_artiste.id_artiste, ti_style_artiste.id_artiste 
               FROM t_style
               INNER JOIN ti_style_artiste ON ti_style_artiste.id_style = t_style.id_style
               WHERE ti_style_artiste.id_artiste=' . $ligne['id_artiste'];
    $pdosSousResultat = $pdoConnexion->prepare($strRequete);
    $pdosSousResultat->execute();

    $ligneStyle = $pdosSousResultat->fetch();
    $strStyles="";

    for($intCptStyle=0;$intCptStyle<$pdosSousResultat->rowCount();$intCptStyle++){
        if($strStyles != ""){
            $strStyles = $strStyles . ", ";
        }
        $strStyles = $strStyles . $ligneStyle['nom_style'];
        $ligneStyle = $pdosSousResultat->fetch();
    }

    $pdosSousResultat->closeCursor();

    $arrArtistes[$cptEnr]['nom_style'] = $strStyles;

    $ligne = $pdosResultat->fetch();
}
//Liberation de la requête
$pdosResultat->closeCursor();

//Établissement de la chaine de requête
$strRequete = 'SELECT ti_evenement.id_artiste, HOUR(date_et_heure) AS heure, MINUTE(date_et_heure) AS minutes, DAY(date_et_heure) AS jour, MONTH(date_et_heure) AS mois, YEAR(date_et_heure) AS annee, t_lieu.nom_lieu, t_lieu.id_lieu
FROM ti_evenement
INNER JOIN t_lieu ON ti_evenement.id_lieu = t_lieu.id_lieu
WHERE ti_evenement.id_artiste = ' . $strIdArtiste . '
ORDER BY date_et_heure ASC';

//Exécution de la requête
$pdosResultat = $pdoConnexion->query($strRequete);

//var_dump($pdoConnexion->errorInfo());

$arrArtistes = array();
//$ligne = $pdosResultat->fetch();
//Extraction des informations sur les styles
for($cptEnr=0;$ligne=$pdosResultat->fetch();$cptEnr++){
    $arrArtistes[$cptEnr]['minutes'] = $ligne['minutes'];
    $arrArtistes[$cptEnr]['heure'] = $ligne['heure'];
    $arrArtistes[$cptEnr]['jour'] = $ligne['jour'];
    $arrArtistes[$cptEnr]['mois'] = $ligne['mois'];
    $arrArtistes[$cptEnr]['annee'] = $ligne['annee'];
    $arrArtistes[$cptEnr]['id_lieu'] = $ligne['id_lieu'];
    $arrArtistes[$cptEnr]['nom_lieu'] = $ligne['nom_lieu'];
}

//var_dump($arrArtistes);

$strArtistesSimilaires= 'SELECT DISTINCT t_artiste.id_artiste, nom_artiste FROM t_artiste INNER JOIN
ti_style_artiste ON t_artiste.id_artiste=ti_style_artiste.id_artiste WHERE id_style
IN(SELECT id_style FROM ti_style_artiste WHERE id_artiste= '.$strIdArtiste.') AND
ti_style_artiste.id_artiste<>'.$strIdArtiste;

$pdosResultat = $pdoConnexion->query($strArtistesSimilaires);

$arrParticipantsSug = array();
$ligneAleatoire = $pdosResultat->fetch(); //On recoit une ligne à la fois!
//Extraction des enregistrements à afficher de la BD
for($intCptEnr=0;$intCptEnr<$pdosResultat->rowCount();$intCptEnr++){
    $arrParticipantsSug[$intCptEnr]['id_artiste']=$ligneAleatoire['id_artiste'];
    $arrParticipantsSug[$intCptEnr]['nom_artiste']=$ligneAleatoire['nom_artiste'];
    $ligneAleatoire=$pdosResultat->fetch();  //pour le prochain passage dans la boucle!
}
//var_dump($arrParticipantsSug);

//Détermine le nombre de participants suggérés
$nbParticipantsSug = rand(3,5);
//$nbrImages = rand(3,5);
//Établie une liste de choix
$arrParticipantsChoisi = []; //ou $arrParticipantsChoisi = array();
//Tant que le nombre de suggestions n'est pas atteint
for($intCptPart=0;$intCptPart<$nbParticipantsSug;$intCptPart++){
    //Trouve un index au hazard selon le nombre de sugestions
    $intMax=count($arrParticipantsSug)-1;
    $intIndexHazard=rand(0,$intMax);

//    var_dump($arrParticipantsSug[$intIndexHazard]);
    //Prendre la suggestion et la mettre dans les participants choisis
    array_push($arrParticipantsChoisi,$arrParticipantsSug[$intIndexHazard]);
    //Enlever la suggestion des suggestions disponibles (évite les suggestions en doublons)
    array_splice($arrParticipantsSug,$intIndexHazard,1);
}
//var_dump($arrParticipantsChoisi);
//On libère la requête
$pdosResultat->closeCursor();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta charset="UTF-8">
    <title>Festival OFF - <?php echo $arrInfos[0]['nom_artiste']?></title>
    <?php include($niveau . 'inc/fragments/head-links.html'); ?>
    <link rel="stylesheet" href="../../css/style-rosalie.css">
</head>
<body>
<header>
    <?php include($niveau . 'inc/fragments/header.inc.php'); ?>
</header>
<!--<picture class="artiste_img">    -->
<!--    --><?php //echo "<img src='../../img/fiche-artiste/".$arrInfos[$cptEnr]['id_artiste']."_0__carre_w366.jpg' srcset='../../img/fiche-artiste/".$arrInfos[$cptEnr]['id_artiste']."_w366.jpg 1x, ../../img/fiche-artiste/".$arrInfos[$cptEnr]['id_artiste']."_w732.jpg 2x'>"; ?>
<!--</picture>-->

<!-- Main : Le contenu de la fiche de l'artiste
            1. Son nom
            2. Sa provenance
            3. Son style
            4. Sa description
            5. Des images
-->
<main class="contenuArtiste">
    <div class="partieGauche">
        <div class="fondNeonsHero mauve">
        <picture class="imageHeroArtiste" style="position:relative;z-index:1;">
            <?php echo "<img src='../../img/fiche-artiste/".$arrInfos[0]['id_artiste']."_0__carre_w366.jpg' srcset='../../img/fiche-artiste/".$arrInfos[0]['id_artiste']."_0__carre_w366.jpg 1x, ../../img/fiche-artiste/".$arrInfos[0]['id_artiste']."_0__carre_w732.jpg 2x' class='imgHeroArtiste' style='position:relative;z-index:1;'>"; ?>
        </picture>
        </div>
        <div class="imagesArtiste">
            <picture class="imageArtiste imageArtisteGauche" style="margin-right: 10px">
<?php echo "<source srcset='../../img/fiche-artiste/".$arrInfos[0]['id_artiste']."_1__carre_w150.jpg 1x, ../../img/fiche-artiste/".$arrInfos[0]['id_artiste']."_1__carre_w334.jpg 2x'>"; ?>
                <?php echo "<img src='../../img/fiche-artiste/".$arrInfos[0]['id_artiste']."_1__carre_w334.jpg'>"; ?>
            </picture>
            <picture class="imageArtiste imageArtisteDroite" style="margin-left: 10px">
                <?php echo "<source srcset='../../img/fiche-artiste/".$arrInfos[0]['id_artiste']."_2__carre_w150.jpg 1x, ../../img/fiche-artiste/".$arrInfos[0]['id_artiste']."_2__carre_w334.jpg 2x'>"; ?>
                <?php echo "<img src='../../img/fiche-artiste/".$arrInfos[0]['id_artiste']."_2__carre_w334.jpg'>"; ?>
            </picture>
        </div>
    </div>
    <div class="partieDroite">
        <div class="fondNeons" id="bleu">
            <div class="fondFonce">
            <h1 class="nomArtiste"><?php echo $arrInfos[0]['nom_artiste']?></h1>
            </div>
        </div>

        <div class="provenanceStyleArtiste">
            <p class="provenanceStyleArtisteP"><?php echo $arrInfos[0]['provenance'] . " - " . $strStyles?></p>
            <!--            <p class="styleArtiste">--><?php //echo $strStyles?><!--</p>-->
        </div>
        <div class="fondNeons neonsDescription mauve">
            <div class="fondFonce">
                <h3>Description </h3><p class="descriptionArtiste"><?php echo $arrInfos[0]['description']?></p>
                <?php
                for($cptEnr=0;$cptEnr<1;$cptEnr++){
                    ?> <h3>Représentations </h3><p class="representationsArtiste">À <?php echo $arrArtistes[$cptEnr]['nom_lieu'] . " " ?>à <?php echo $arrArtistes[$cptEnr]['heure'] . "h"?><?php echo $arrArtistes[$cptEnr]['minutes'] . " "?> le <?php echo $arrArtistes[$cptEnr]['jour'] . " " .  $arrMoisFr[$arrArtistes[$cptEnr]['mois']] . " " .  $arrArtistes[$cptEnr]['annee']; ?></p>
                    <?php
                }
                ?>
                <h3>Site web </h3><p class="siteWebArtiste"><a href="<?php echo $arrInfos[0]['site_web_artiste']?>"><?php echo $arrInfos[0]['site_web_artiste']?></a></p>
            </div>
        </div>
    </div>
<!--    <img class="lumiereBleue" src="../../img/ligneBleue.svg">-->
</main>
<!-- Section : Des artistes proposés
            1. Des images
            2. Leurs noms
-->
<div class="artistesSugDiv">
    <section class="artistesSug">
        <h2 class="suggestions">Vous aimerez aussi...</h2>
        <ul class="listeSuggestions">
<!--            --><?php
//            //        var_dump($arrParticipantsChoisi);
//            for($cpt=0;$cpt<count($arrParticipantsSug)-1;$cpt++){ ?>
<!---->
<!--            --><?php //} ?>
            <?php for ($cptPhoto=0; $cptPhoto<$nbParticipantsSug; $cptPhoto++){
//                echo '<img class="imagesArtistesSug" src="https://fakeimg.pl/300/">';
                ?> <li class="artisteSug">
                    <p class="nomArtisteSug"><a href='index.php?id_artiste=<?php echo $arrParticipantsChoisi[$cptPhoto]["id_artiste"];?>' class="lienArtisteSug">
                            <?php echo $arrParticipantsChoisi[$cptPhoto]["nom_artiste"];?></a></p>

                <a href='index.php?id_artiste=<?php echo $arrParticipantsChoisi[$cptPhoto]["id_artiste"];?>'><picture class="imageHeroArtisteSug">
                <?php echo "<img src='../../img/fiche-artiste/".$arrParticipantsChoisi[$cptPhoto]['id_artiste']."_0__carre_w292.jpg' srcset='../../img/fiche-artiste/".$arrParticipantsChoisi[$cptPhoto]['id_artiste']."_0__carre_w292.jpg 1x, ../../img/fiche-artiste/".$arrParticipantsChoisi[$cptPhoto]['id_artiste']."_0__carre_w584.jpg 2x' class='imgHeroArtisteSug'>"; ?>
                    </picture></a></li>
            <?php
            }?>
    </section>

    <footer>
        <?php include($niveau . 'inc/fragments/footer.inc.php'); ?>
    </footer>
</div>
</body>
</html>
