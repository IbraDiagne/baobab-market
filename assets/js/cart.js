/*
=====================================================
FICHIER : cart.js
ROLE :
- Gère le panier du site Baobab Market
- Utilise LocalStorage pour stocker les produits
- Affiche les produits et le total
=====================================================
*/

/* Récupération du panier depuis le navigateur */
let cart = JSON.parse(localStorage.getItem("cart")) || [];

/* Conteneur HTML du panier */
const cartContainer = document.getElementById("cart-items");
const cartTotal = document.getElementById("cart-total");

/* ================= AFFICHER LE PANIER ================= */
function displayCart() {
    cartContainer.innerHTML = "";
    let total = 0;

    /* Si le panier est vide */
    if (cart.length === 0) {
        cartContainer.innerHTML = "<p>Votre panier est vide.</p>";
        cartTotal.textContent = "Total : 0 FCFA";
        return;
    }

    /* Parcours des produits */
    cart.forEach((product, index) => {

        total += product.price;

        const item = document.createElement("div");
        item.classList.add("product-card");

        item.innerHTML = `
            <h3>${product.name}</h3>
            <p>${product.price} FCFA</p>
            <button onclick="removeFromCart(${index})" class="btn-primary">
                Supprimer
            </button>
        `;

        cartContainer.appendChild(item);
    });

    cartTotal.textContent = "Total : " + total + " FCFA";
}

/* ================= SUPPRIMER UN PRODUIT ================= */
function removeFromCart(index) {
    cart.splice(index, 1);
    localStorage.setItem("cart", JSON.stringify(cart));
    displayCart();
}

/* Affichage au chargement */
displayCart();
