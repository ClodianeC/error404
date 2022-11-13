<footer class="footer">
    <div class="block">
    <nav class="nav--principale footer-nav" aria-label="Menu principal">
        <ul class="nav--principale__list" id="navPrincipaleList">
            <li class="nav--principale__list-elem ">
                <a href="<?php echo $niveau ?>index.php" class="nav--principale__link nav--principale__link--active"
                   arria-current="page">Programmation</a>
            </li>
            <li class="nav--principale__list-elem">
                <a href="<?php echo $niveau ?>artistes/index.php" class="nav--principale__link">Artistes</a>
            </li>
            <li class="nav--principale__list-elem">
                <a href="<?php echo $niveau ?>#" class="nav--principale__link">Contactez-nous</a>
            </li>
        </ul>
    </nav>
    </div>
    <div class="purpleBG">
    <div class="footer-socials">
        <div class="socials-icons ">
            <ul class="socials-icons__list footer-icons">
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
    <h4 class="h4">
        Festival OFF de Québec
    </h4>
    <div class="footer__socials--info">
        <div class="footer__socials--info-adresse">
            <p class="courant-text">110 boulevard René-Lévesque Ouest C.P 48036</p><br>
            <p class="courant-text">Québec, Québec</p><br>
            <p class="courant-text">G1R 5R5</p><br>
        </div>
        <div class="footer__socials--info-email">
            <a class='email' href="mailto: info@quebecoff.org">info@quebecoff.org</a>
            <a class='email' href="mailto: media@quebecoff.org">media@quebecoff.org</a>
        </div>
        </div>
        </div>
        <small class="copyrigth">
            <p>Une réalisation de Isaac Dubé, Clodiane Charette et Rosalie Roy © Tous droits réservés. Travail scolaire
                réalisé en mars 2022.
                Programme Techniques d’intégration multimédia.</p>
        </small>
    </div>
</footer>
<link rel="stylesheet" href="<?php echo $niveau ?>scss/style-footer.css">