/*
=====================================================
FICHIER : checkout.js
ROLE :
- Gère la soumission du formulaire
- Prépare l’envoi vers le backend
=====================================================
*/

const form = document.getElementById("checkout-form");

form.addEventListener("submit", function(event) {

    event.preventDefault(); // Empêche rechargement

    alert("Commande enregistrée ! (backend à connecter)");

});
