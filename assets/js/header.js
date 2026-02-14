/*
=====================================================
FICHIER : header.js
ROLE :
- Gère les interactions du header
- Accès admin via double clic sur le logo
=====================================================
*/

document.addEventListener("DOMContentLoaded", function() {

    const logo = document.getElementById("logo-admin");

    if (!logo) return;

    let clickCount = 0;

    logo.addEventListener("click", function() {

        clickCount++;

        if (clickCount === 2) {
            window.location.href = "/baobab-market/admin/";
        }

        setTimeout(() => clickCount = 0, 500);

    });

});
