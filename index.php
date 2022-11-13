<?php $niveau = '';

include($niveau . 'inc/scripts/config.inc.php');

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

            <section class="section_photo">
                <picture class="picture_right photo_intro ">
                    <source class='photo_intro-mobile' media="(max-width: 780px)"
                        srcset="<?php echo $niveau ?>image_accueil/mobile_header/korrias_1x_390.jpg, <?php echo $niveau ?>image_accueil/mobile_header/korrias_2x_780.jpg 2x">

                    <source class='photo_intro-table' media="(min-width: 781px)"
                        srcset="<?php echo $niveau ?>image_accueil/accueil_table/purity-ring-megan-james_1x_w570.jpg 1x, <?php echo $niveau ?>image_accueil/accueil_table/purity-ring-megan-james_2x_w1140.jpg 2x">

                    <img class="img_article_2" src="image_accueil/accueil_table/purity-ring-megan-james_2x_w1140.jpg 2x"
                        alt="Image d'en-tête">
                </picture>

            </section>
    </main>
    <?php include($niveau . 'inc/fragments/footer.inc.php'); ?>
</body>

<script src="js/menu.js"></script>

</html>