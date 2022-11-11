

const hamburger = document.querySelector('.hamburger');
const navMenu = document.querySelector('.nav-menu');


hamburger.addEventListener('click', () => {
 hamburger.classList.toggle("active")
 navMenu.classList.toggle("active")
 navMenu.classList.toggle("stop-scrolling")
});

hamburger.querySelectorAll('nav--principale__link').forEach(n => n.addEventListener('click', () => {
 hamburger.classList.remove("active")
 navMenu.classList.remove("active")
}));

//*** crousselle ***//

let refDivCarousel = document.getElementById('image_carousel');


function changerDePhoto(intPhoto) {
 console.log(intPhoto);
 switch (intPhoto) {
  case 1:
   refDivCarousel.innerHTML = ' <picture class="picture__entete "> ' + 
   ' <source media="(max-width: 780px) ' +
     ' srcset="<?php echo $niveau ?>image_accueil/mobile_header/DiamondRings_1x_w390.jpg 1x, <?php echo $niveau ?>image_accueil/mobile_header/DiamondRings_2x_w780.jpg 2x">' +
' <source media="(min-width: 781px)" srcset="<?php echo $niveau ?>image_accueil/accueil_table/DiamondRings.jpg 1x, <?php echo $niveau ?>image_accueil/accueil_table/DiamondRings_DSC0731_2x_w3840.jpg 2x">' +

'<img class="imageCarousel" src="image_accueil/accueil_table/DiamondRings_DSC0731_2x_w3840.jpg" alt="Image en-tête"> ' +
 '</picture>'

   break;
  case 2:
    refDivCarousel.innerHTML =     ' <picture class="picture__entete "> ' + 
    ' <source media="(max-width: 780px) ' +
      ' srcset="<?php echo $niveau ?>image_accueil/mobile_header/3DKids_DSC0179_1x_w390.jpg 1x, <?php echo $niveau ?>image_accueil/mobile_header/3DKids_DSC0179_2x_w780.jpg 2x">' +
 
 ' <source media="(min-width: 781px)" srcset="<?php echo $niveau ?>image_accueil/accueil_table/3DKids_DSC0179.jpg 1x, <?php echo $niveau ?>image_accueil/accueil_table/accueil_table/3DKids_DSC0179.jpg_2x_w3840.jpg 2x">' +
 
 '<img class="imageCarousel" src="image_accueil/accueil_table/DiamondRings_DSC0731_2x_w3840.jpg" alt="Image en-tête"> ' +
  '</picture>'
   break;
  case 3:
    refDivCarousel.innerHTML =     ' <picture class="picture__entete "> ' + 
    ' <source media="(max-width: 780px) ' +
      ' srcset="<?php echo $niveau ?>image_accueil/mobile_header/DiamondRings_DSC0860_1x_w390.jpg 1x, <?php echo $niveau ?>image_accueil/mobile_header/DiamondRings_DSC0860_2x_w780.jpg 2x">' +
 
 ' <source media="(min-width: 781px)" srcset="<?php echo $niveau ?>image_accueil/accueil_table/DiamondRings_DSC0860.jpg 1x, <?php echo $niveau ?>image_accueil/accueil_table/accueil_table/DiamondRings_DSC0860.jpg_2x_w3840.jpg 2x">' +
 
 '<img class="imageCarousel" src="image_accueil/accueil_table/DiamondRings_DSC0860_2x_w3840.jpg" alt="Image en-tête"> ' +
  '</picture>'
   break;

  default:
    refDivCarousel.innerHTML =     ' <picture class="picture__entete "> ' + 
    ' <source media="(max-width: 780px) ' +
      ' srcset="<?php echo $niveau ?>image_accueil/mobile_header/DiamondRings_1x_w390.jpg 1x, <?php echo $niveau ?>image_accueil/mobile_header/DiamondRings_2x_w780.jpg 2x">' +
 
 ' <source media="(min-width: 781px)" srcset="<?php echo $niveau ?>image_accueil/accueil_table/DiamondRings.jpg 1x, <?php echo $niveau ?>image_accueil/accueil_table/DiamondRings_DSC0731_2x_w3840.jpg 2x">' +
 
 '<img class="imageCarousel" src="image_accueil/accueil_table/DiamondRings_DSC0731_2x_w3840.jpg" alt="Image en-tête"> ' +
  '</picture>'
 
 }

}


document.getElementById('btn1').addEventListener('click', function () {
 changerDePhoto(1);
});

document.getElementById('btn2').addEventListener('click', function () {
 changerDePhoto(2);
});

document.getElementById('btn3').addEventListener('click', function () {
 changerDePhoto(3);
});









