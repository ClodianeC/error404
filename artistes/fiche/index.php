<?php

$niveau= '../../';

include($niveau . 'inc/scripts/config.inc.php');

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
