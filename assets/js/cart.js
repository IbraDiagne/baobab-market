/*
=====================================================
FICHIER : cart.js
ROLE :
- Gère le panier du site Baobab Market
- Ajoute / supprime des produits
- Stocke les données dans LocalStorage
=====================================================
*/

/* ================= PANIER GLOBAL ================= */
let cart = JSON.parse(localStorage.getItem("cart")) || [];

/* Sauvegarde du panier */
function saveCart() {
    localStorage.setItem("cart", JSON.stringify(cart));
}

/* ================= AJOUT AU PANIER ================= */
function addToCart(name, price) {

    const product = {
        name: name,
        price: price
    };

    cart.push(product);
    saveCart();

    alert(name + " ajouté au panier 🛒");
}

/* ================= AFFICHAGE PANIER ================= */
function displayCart() {

    const cartContainer = document.getElementById("cart-items");
    const cartTotal = document.getElementById("cart-total");

    /* Si on n’est pas sur la page panier */
    if (!cartContainer || !cartTotal) return;

    cartContainer.innerHTML = "";
    let total = 0;

    if (cart.length === 0) {
        cartContainer.innerHTML = "<p>Votre panier est vide.</p>";
        cartTotal.textContent = "Total : 0 FCFA";
        return;
    }

    cart.forEach((product, index) => {

        total += product.price;

        const item = document.createElement("div");
        item.classList.add("product-card");

        item.innerHTML = `
            <h3>${product.name}</h3>
            <p>${product.price} FCFA</p>
            <button class="btn-primary" onclick="removeFromCart(${index})">
                Supprimer
            </button>
        `;

        cartContainer.appendChild(item);
    });

    cartTotal.textContent = "Total : " + total + " FCFA";
}

/* ================= SUPPRESSION ================= */
function removeFromCart(index) {
    cart.splice(index, 1);
    saveCart();
    displayCart();
}

/* Affichage automatique si page panier */
displayCart();
