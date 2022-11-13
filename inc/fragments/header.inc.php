
<!-- <a href="#contenu" class="visuallyhidden focusable">Aller au contenu</a> -->
<header class="header">
    <div class="header_nav">
        <div class="image_carousel">
       <picture class="picture__entete ">
              <source media="(max-width: 780px)"
                 srcset="<?php echo $niveau ?>image_accueil/mobile_header/DiamondRings_1x_w390.jpg 1x, <?php echo $niveau ?>image_accueil/mobile_header/DiamondRings_2x_w780.jpg 2x">

           <source media="(min-width: 781px)" srcset="<?php echo $niveau ?>image_accueil/accueil_table/DiamondRings.jpg 1x, <?php echo $niveau ?>image_accueil/accueil_table/DiamondRings_DSC0731_2x_w3840.jpg 2x">

           <img class="imageCarousel" src="image_accueil/accueil_table/DiamondRings_DSC0731_2x_w3840.jpg" alt="Image d'en-tête">
            </picture>
        </div>

        <a href="<?php echo $niveau ?>index.php" class="header__nav--logo"><img class="img__nav--logo"
         src="<?php echo $niveau ?>image_accueil/accueil_table/reseau_sociaux/logo.png" alt="Accueil">
    </div>
    <div class="nav-menu">
        <nav class="nav--principale" aria-label="Menu principal">
            <ul class="nav--principale__list header-nav" id="navPrincipaleList">
                <li class="nav--principale__list-elem">
                    <a href="<?php echo $niveau ?>programation/index.php" class="nav--principale__link nav--principale__link--active"
                       arria-current="page">Programmation</a>
                </li>
                <li class="nav--principale__list-elem">
                    <a href="<?php echo $niveau ?>artistes/index.php" class="nav--principale__link">Artistes</a>
                </li>
                <li class="nav--principale__list-elem">
                    <a href="#" class="nav--principale__link">Contactez-nous</a>
                </li>
            </ul>
        </nav>
    </div>
    <div class="hamburger">
        <div class="bubble">
            <span class="bar"></span>
            <span class="bar"></span>
            <span class="bar"></span>
        </div>
    </div>
    </div>
    <div class="header-socials">
        <div class="socials-icons ">
            <ul class="socials-icons__list header-icons">
                <li class="socials-icons__list-elem"><a class="socials-icons-link" href="#"><img
                                class="socials-icons-img" src="<?php echo $niveau ?>image_accueil/accueil_table/reseau_sociaux/facebook.png" alt=""></a>
                </li>
                <li class="socials-icons__list-elem"><a class="socials-icons-link" href="#"><img
                                class="socials-icons-img" src="<?php echo $niveau ?>image_accueil/accueil_table/reseau_sociaux/youtube.png" alt=""></a>
                </li>
                <li class="socials-icons__list-elem"><a class="socials-icons-link" href="#"><img
                                class="socials-icons-img" src="<?php echo $niveau ?>image_accueil/accueil_table/reseau_sociaux/twitter.png" alt=""></a>
                </li>
            </ul>
        </div>
    </div>
    <div class="bouton_carousel">
        <button class="btn_carousel bouton_carousel-1" id="btn1" value="btn1"></button>
        <button class="btn_carousel bouton_carousel-2" id="btn2" value="btn2"></button>
        <button class="btn_carousel bouton_carousel-3" id="btn3" value="btn3"></button>
    </div>
</header>
<link rel="stylesheet" href="<?php echo $niveau ?>scss/style-header.css">
<script src="<?php echo $niveau ?>js/menu.js"></script>
