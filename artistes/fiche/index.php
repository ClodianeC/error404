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

$nbrImages = rand(3,5);
var_dump($nbrImages);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta charset="UTF-8">
    <title>Fiche d'artiste - Nom de l'artiste</title>
</head>
<body>
<!-- Main : Le contenu de la fiche de l'artiste
            1. Son nom
            2. Sa provenance
            3. Son style
            4. Sa description
            5. Des images
-->
<main class="contenuArtiste">
    <h1 class="nomArtiste">Nom de l'artiste</h1>
    <div class="provenanceStyleArtiste">
        <p class="provenanceArtiste">Provenance de l'artiste</p>
        <p class="styleArtiste">Style de l'artiste</p>
    </div>
    <p class="descriptionArtiste">Description de l'artiste</p>

    <img class="imageHeroArtiste">
    <img class="imageArtiste">
    <img class="imageArtiste">
</main>
<!-- Section : Des artistes proposés
            1. Des images
            2. Leurs noms
-->
<section class="artistesSug">
    <h2 class="suggestions">Vous aimerez aussi...</h2>

    <img class="imagesArtistesSug">
    <p class="nomArtisteSug"></p>

    <img class="imagesArtistesSug">
    <p class="nomArtisteSug"></p>

    <img class="imagesArtistesSug">
    <p class="nomArtisteSug"></p>
</section>
</body>
</html>
