/*
=====================================================
FICHIER : cart.js
ROLE :
- Gestion panier avec LocalStorage
- Gestion quantité
- Calcul total
=====================================================
*/

function getCart() {
    return JSON.parse(localStorage.getItem("cart")) || [];
}

function saveCart(cart) {
    localStorage.setItem("cart", JSON.stringify(cart));
}

function addToCart(id, name, price) {

    let cart = getCart();

    let existing = cart.find(item => item.id === id);

    if (existing) {
        existing.quantity++;
    } else {
        cart.push({
            id: id,
            name: name,
            price: price,
            quantity: 1
        });
    }

    saveCart(cart);
    alert("Produit ajouté au panier !");
}

function removeFromCart(id) {
    let cart = getCart();
    cart = cart.filter(item => item.id !== id);
    saveCart(cart);
    renderCart();
}

function updateQuantity(id, change) {
    let cart = getCart();
    let item = cart.find(item => item.id === id);

    if (!item) return;

    item.quantity += change;

    if (item.quantity <= 0) {
        removeFromCart(id);
    } else {
        saveCart(cart);
        renderCart();
    }
}

function renderCart() {

    const cartItems = document.getElementById("cart-items");
    const cartTotal = document.getElementById("cart-total");

    if (!cartItems) return;

    let cart = getCart();
    cartItems.innerHTML = "";

    let total = 0;

    cart.forEach(item => {

        total += item.price * item.quantity;

        cartItems.innerHTML += `
            <div class="cart-item">
                <h4>${item.name}</h4>
                <p>${item.price} FCFA</p>

                <div class="quantity-controls">
                    <button onclick="updateQuantity(${item.id}, -1)">-</button>
                    <span>${item.quantity}</span>
                    <button onclick="updateQuantity(${item.id}, 1)">+</button>
                </div>

                <button onclick="removeFromCart(${item.id})">
                    Supprimer
                </button>
            </div>
        `;
    });

    cartTotal.innerText = "Total : " + total + " FCFA";
}

document.addEventListener("DOMContentLoaded", renderCart);

/* Envoi panier vers serveur */
document.getElementById("checkout-btn")?.addEventListener("click", function() {

    let cart = getCart();

    if (cart.length === 0) {
        alert("Panier vide !");
        return;
    }

    fetch("../backend/process/save-cart.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify(cart)
    })
    .then(() => {
        window.location.href = "checkout.php";
    });
});
