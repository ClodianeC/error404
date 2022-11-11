//*** Menu hamburger ***//

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

let refImage = document.getElementById('imageCarousel');


function changerDePhoto(intPhoto) {
 console.log(intPhoto);
 switch (intPhoto) {
  case 1:
   refImage.src = '../../accueil_table/DiamondRings_DSC0731.jpg';
   refImage
   break;
  case 2:
   refImage.src = '../../accueil_table/3DKids_DSC0179.jpg';
   break;
  case 3:
   refImage.src = '../../accueil_table/DiamondRings_DSC0860.jpg';
   break;

  default:
   refImage.src = '../../accueil_table/DiamondRings_DSC0731.jpg';
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









