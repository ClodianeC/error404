<?php $niveau = '';

include($niveau . 'inc/scripts/config.inc.php');

$arrActualites = array();

$strRequete = 'SELECT titre, article, date_actualite,DAYOFWEEK(date_actualite) AS joursemaine, DAYOFMONTH(date_actualite) AS jour, MONTH(date_actualite) AS mois, YEAR(date_actualite) AS annee, auteurs 
                FROM t_actualite
                ORDER BY date_actualite DESC';

$strRequeteStyle = 'SELECT DISTINCT t_artiste.id_artiste, nom_artiste 
                    FROM t_artiste ';



//Extraction de l'enregistrements de la BD
$pdosResultatActualites = $pdoConnexion->query($strRequete);

for($cptEnr = 0; $ligneActualite=$pdosResultatActualites->fetch(); $cptEnr++){
    $arrActualites[$cptEnr]["titre"]=$ligneActualite["titre"];
    $arrActualites[$cptEnr]["jour"]=$ligneActualite["jour"];
    $arrActualites[$cptEnr]["joursemaine"]=$ligneActualite["joursemaine"];
    $arrActualites[$cptEnr]["mois"]=$ligneActualite["mois"];
    $arrActualites[$cptEnr]["annee"]=$ligneActualite["annee"];
    $arrActualites[$cptEnr]["auteurs"]=$ligneActualite["auteurs"];

        //Coupe le texte en tableau
        $arrArticle=explode(" ", $ligneActualite["article"]);

        //Si plus grand que 45 mots
        if(count($arrArticle) > 45){
            //Couper le reste du texte
            array_splice($arrArticle,45,count($arrArticle));
        }

        //Reprend le tableau et recompose le texte, stocke dans la propriété article du tableau
        $arrActualites[$cptEnr]["article"]=implode(" ",$arrArticle);
}
$pdosResultatActualites->closeCursor();


//Extraction de l'enregistrements de la BD
$pdosResultatStyle = $pdoConnexion->query($strRequeteStyle);



$nbRandomArtiste = rand(3,5);

$arrSugArtistes = array();

//Extraction des enregistrements à afficher de la BD
for($intCptEnr=0;$ligne = $pdosResultatStyle->fetch();$intCptEnr++){
    $arrSugArtistes[$intCptEnr]['id_artiste']=$ligne['id_artiste'];
    $arrSugArtistes[$intCptEnr]['nom_artiste']=$ligne['nom_artiste'];
}


$arrArtistesChoisis = array();
    for ($intCpt = 0; $intCpt <= 2; $intCpt++){
        $artisteChoisi = rand(0, count($arrSugArtistes)-1);
        array_push($arrArtistesChoisis,$arrSugArtistes[$artisteChoisi]);
        array_splice($arrSugArtistes,$artisteChoisi,1);
}

?>


<!doctype html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="scss/style-isaac.css">
</head>

<body>

    <?php include($niveau . 'inc/fragments/header.inc.php'); ?>

    <main class="main">
        <div class="div_main">
            <h1 class="h1">Festival OFF</h1>
            <h2 class="h2">Du 8 au 11 juillet</h2>

            <section class="section_photo">
                <picture class="picture_left photo_intro ">
                    <source class='photo_intro-mobile' media="(max-width: 780px)"
                        srcset="<?php echo $niveau ?>image_accueil/mobile_header/korrias_1x_390.jpg, <?php echo $niveau ?>image_accueil/mobile_header/korrias_2x_780.jpg 2x">

                    <source class='photo_intro-table' media="(min-width: 781px)"
                        srcset="<?php echo $niveau ?>image_accueil/accueil_table/Koriass05022016-224725-34-Koriass_1x_w570.jpg 1x, <?php echo $niveau ?>image_accueil/accueil_table/Koriass05022016-224725-34-Koriass_2x_w1140.jpg 2x">

                    <img class="img_article"
                        src="image_accueil/accueil_table/Koriass05022016-224725-34-Koriass_2x_w1140.jpg 2x"
                        alt="Image d'en-tête">
                </picture>

            </section>
            <div class="tout_est_possible">
            <h2 class="h2">Tout est possible</h2>
            <section class="section_photo">
                <picture class="picture_right photo_intro ">
                    <source class='photo_intro-mobile' media="(max-width: 780px)"
                        srcset="<?php echo $niveau ?>image_accueil/mobile_header/purityKing_1x_390.jpg, <?php echo $niveau ?>image_accueil/mobile_header/purityKing_2x_w780.jpg 2x">

                    <source class='photo_intro-table' media="(min-width: 781px)"
                        srcset="<?php echo $niveau ?>image_accueil/accueil_table/purity-ring-megan-james_1x_w570.jpg 1x, <?php echo $niveau ?>image_accueil/accueil_table/purity-ring-megan-james_2x_w1140.jpg 2x">

                    <img class="img_article_2" src="image_accueil/accueil_table/purity-ring-megan-james_2x_w1140.jpg 2x"
                        alt="Image d'en-tête">
                </picture>

            </section>
            </div>
            <h2 class="h2">Actualités</h2>

            <section class="actualite">
            <?php
    $nbArticleRandom = rand(3,5);
    for($cpt = 0; $cpt < $nbArticleRandom; $cpt++){
        $nbArticleRandom2 = rand(0,count($arrActualites)-1);
        ?>
        <article class="actualite_elem">
                <h3 class="h3"><?php echo $arrActualites[$nbArticleRandom2]["titre"];?></h3>

                <div class="centenu-actu<?php echo $cpt + 1?>">

            <picture class="actu-picture img_actualite<?php echo $cpt + 1?>">
            <source class='photo_intro-mobile' media="(max-width: 780px)"
                        srcset="<?php echo $niveau ?>image_accueil/mobile_header/<?php echo $cpt + 1?>_<?php echo $cpt + 1?>_1x_w390.jpg, <?php echo $niveau ?>image_accueil/mobile_header/<?php echo $cpt + 1 ?>_<?php echo $cpt + 1 ?>_2x_w780.jpg 2x">

                    <source class='photo_intro-table' media="(min-width: 781px)"
                        srcset="<?php echo $niveau ?>image_accueil/accueil_table/<?php echo $cpt + 1 ?>_<?php echo $cpt + 1 ?>_1x_w570.jpg 1x, <?php echo $niveau ?>image_accueil/accueil_table/<?php echo $cpt + 1 ?>_<?php echo $cpt + 1 ?>_2x_w1140.jpg 2x">

                    <img class="actu imgActualite<?php echo $cpt + 1?>"
                        src="image_accueil/accueil_table/<?php echo $cpt + 1 ?>_<?php echo $cpt + 1?>_2x_w1140.jpg 2x"
                        alt="Image d'en-tête">
        </picture>
        <p class="text-actu">  <?php
            $arrJour = array('Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche');
            $arrMois = array('Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre');
            echo $arrActualites[$nbArticleRandom2]["article"];
                if(count(explode(" ",$arrActualites[$nbArticleRandom2]["article"])) >= 45) { ?>
                    <a href="#">...</a>
            <?php } ?>
            </p>   
            </div>

                <h4>Par <?php echo $arrActualites[$nbArticleRandom2]["auteurs"];?>, le
                    <?php echo $arrJour[$arrActualites[$nbArticleRandom2]["joursemaine"]-1];?>
                    <?php echo $arrActualites[$nbArticleRandom2]["jour"]. " " .$arrMois[$arrActualites[$nbArticleRandom2]["mois"]]. " " .$arrActualites[$nbArticleRandom2]["annee"];?>
                </h4>

        </article>
<?php } ?>
            </section>
<div class="neon">
            <section class="artiste_sugg">
                <h2 class="h2">Artistes à découvrir</h2>
                <ul class="list_artistes">
                <?php for($cptArtistes = 0; $cptArtistes < count($arrArtistesChoisis); $cptArtistes++){?>
        <li class="list_artistes-elem"><a class="lien_artiste" href='fiche/index.php?id_artiste=<?php echo $arrArtistesChoisis[$cptArtistes]['id_artiste']?>'>
                <?php echo $arrArtistesChoisis[$cptArtistes]['nom_artiste'];?>
                <picture class="artiste_sugg-picture">
                    <source class='photo-table'
                        srcset="<?php echo $niveau ?>image_accueil/accueil_table/artistes_a_decouvrir/<?php echo $arrArtistesChoisis[$cptArtistes]['id_artiste']?>_1x_w1000.jpg 1x, <?php echo $niveau ?>image_accueil/accueil_table/artistes_a_decouvrir/<?php echo $arrArtistesChoisis[$cptArtistes]['id_artiste']?>_2x_w2000.jpg 2x">
                    <img class="sugg suggestion<?php echo $cpt + 1?>"
                        src="<?php echo $niveau ?>image_accueil/accueil_table/artistes_a_decouvrir/<?php echo $arrArtistesChoisis[$cptArtistes]['id_artiste']?>_2x_w2000.jpg 2x"
                        alt="<?php echo $arrArtistesChoisis[$cptArtistes]['nom_artiste'];?>">
        </picture></a></li>

                <?php } ?>
                
                </ul>
            </section>
            </div>

            <section class="Lieux">
                <h2 class="h2">Lieu de spectacles</h2>
                <ul class="liste_lieux">
                    <li class="liste_lieux-elem"><h4>Méduse</h4><p>591, rue de Saint-Vallier Est Québec</p></li>
                    <li class="liste_lieux-elem"><h4>Le Sacrilège</h4><p>447, rue Saint-JeanQuébec</p></li>
                    <li class="liste_lieux-elem"><h4>Scène Desjardins ( Le parvis St-Jean-Baptiste )</h4><p>Église St-Jean-Baptiste 
480 rue Saint-Jean
Québec
</p></li>
                    <li class="liste_lieux-elem"><h4>Le Fou-Bar</h4><p>525, Rue St-Jean 
Québec
</p></li>
                    <li class="liste_lieux-elem"><h4>La Ninkasi du Faubourg</h4><p>811, Rue Saint-Jean 
Québec
</p></li>
                </ul>
                
            </section>

            <section class="tarifs">
            <h2 class="h2">Tarifs</h2>
            <p class="tarif1">Passeport: 10$ pour toute la durée du festival<br>
                5$ à la porte / soir (spectacles à Méduse)<br>
                Spectacles extérieurs gratuits<br>
                Spectacles gratuits au Parvis de l’église Saint-Jean-Baptiste, au bar le Sacrilège et au Fou-Bar</p>

            
            <p class="tarif2">Procurez-vous un passeport en ligne à lepointdevente.com et profitez d’offres spéciales!<br>
            Les passeports sont aussi disponibles en prévente chez nos partenaires:<br>
                
                
            <p class="tarif3">La Ninkasi Honoré-Mercier : 840 Avenue Honoré-Mercier, Québec<br>
            Érico: 634 Rue Saint-Jean, Québec<br>
            Le Sacrilège: 447 Rue Saint-Jean, Québec<br>
            Le Bonnet d’âne: 298 Rue Saint-Jean, Québec<br>
            Disquaire CD Mélomane : 248 rue Saint-Jean, Québec
<br>
Le Knock-Out: 832 St-Joseph Est, Québec
<br>
            Le Sacrilège: 447 Rue Saint-Jean, Québec<br>
            Le Bonnet d’âne: 298 Rue Saint-Jean, Québec</p>
            </section>
    </main>
    <?php include($niveau . 'inc/fragments/footer.inc.php'); ?>
</body>

<script src="js/menu.js"></script>

</html>