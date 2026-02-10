/*
=====================================================
FICHIER : animation.js
ROLE :
- Anime le bandeau "Paiement à la livraison"
- Attire l’attention de l’utilisateur
- Ajoute un effet professionnel et moderne
=====================================================
*/

const banner = document.querySelector('.delivery-banner');

/* Vérifie que le bandeau existe */
if (banner) {

    let visible = true;

    /* Toutes les 2 secondes */
    setInterval(() => {

        if (visible) {
            banner.style.opacity = "0.6";
        } else {
            banner.style.opacity = "1";
        }

        visible = !visible;

    }, 2000);
}
