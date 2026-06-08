document.addEventListener("DOMContentLoaded", () => {
   
    const loginStatus = localStorage.getItem("isLoggedIn");
    if (loginStatus !== "true") {
        alert("Access Denied! Please log in to view your shopping cart.");
        window.location.href = "customer-login.html";
        return;
    }


    const savedName = localStorage.getItem("currentUserName");
    if (savedName) {
        document.getElementById("dynamic-username").textContent = savedName;
    }


    renderLiveCart();
});

function renderLiveCart() {
    const itemsListContainer = document.querySelector(".cart-items-list");
    if (!itemsListContainer) return;

    let cart = JSON.parse(localStorage.getItem('farmCart')) || [];

    if (cart.length === 0) {
        itemsListContainer.innerHTML = `<p style="text-align:center; padding: 40px; font-size: 20px; width: 100%;">Your cart is empty!</p>`;
        document.getElementById("invoice-subtotal").textContent = "$0.00";
        document.getElementById("invoice-discount").textContent = "-$0.00";
        document.getElementById("invoice-total").textContent = "$0.00";
        return;
    }

    itemsListContainer.innerHTML = "";

    cart.forEach((item, index) => {
        const itemCardHTML = `
            <div class="cart-item-card">
                <div class="item-thumbnail" style="background-image: url('${item.image}'); width: 50px; height: 45px; border-radius: 4px;"></div>
                <div class="item-details">
                    <h3>${item.name}</h3>
                    <a href="product.html" class="add-more-link">Add more items</a>
                </div>
                <div class="item-price-quantity">
                    <span class="item-unit-display">$${item.price}</span>
                    <div class="quantity-counter">
                        <button onclick="updateCartQuantity(${index}, -1)">-</button>
                        <span class="quantity-val">${item.quantity}</span>
                        <button onclick="updateCartQuantity(${index}, 1)">+</button>
                    </div>
                </div>
            </div>
        `;
        itemsListContainer.innerHTML += itemCardHTML;
    });

    calculateCartInvoice(cart);
}

function updateCartQuantity(itemIndex, adjustmentAmount) {
    let cart = JSON.parse(localStorage.getItem('farmCart')) || [];
    if(!cart[itemIndex]) return;

    cart[itemIndex].quantity += adjustmentAmount;

    if (cart[itemIndex].quantity <= 0) {
        cart.splice(itemIndex, 1);
    }

    localStorage.setItem('farmCart', JSON.stringify(cart));
    renderLiveCart();
}

function calculateCartInvoice(cart) {
    let subtotalSum = 0;
    cart.forEach(item => {
        const validPrice = Number(item.price) || 0;
        const validQuantity = Number(item.quantity) || 0;
        subtotalSum += (validPrice * validQuantity);
    });


    let discountDeduction = subtotalSum * 0.15; 

    const shippingSurcharge = 30;
    const gstSurcharge = 20;


    let absoluteNetTotal = subtotalSum + shippingSurcharge + gstSurcharge - discountDeduction;
    
    if (subtotalSum === 0) {
        discountDeduction = 0;
        absoluteNetTotal = 0;
    }

   
    document.getElementById("invoice-subtotal").textContent = `$${subtotalSum.toFixed(2)}`;
    document.getElementById("invoice-discount").textContent = `-$${discountDeduction.toFixed(2)}`;
    document.getElementById("invoice-total").textContent = `$${absoluteNetTotal.toFixed(2)}`;
}

function navigateTo(pageUrl) { window.location.href = pageUrl; }

function logoutUser() {
    localStorage.removeItem("isLoggedIn");
    localStorage.removeItem("currentUserName");
    window.location.href = "index.html";
}

function proceedToCheckout() {

    const currentFinalTotal = document.getElementById("invoice-total").textContent;
    

    if (currentFinalTotal === "$0.00" || currentFinalTotal === "$0") {
        alert("Your shopping cart is currently empty! Please add items from the products page before checking out.");
        return;
    }


    window.location.href = "checkout.html";
}


document.addEventListener("DOMContentLoaded", () => {
    
    // 1. Check login status immediately
    const loginStatus = localStorage.getItem("isLoggedIn");
    if (loginStatus !== "true") {
        alert("Access Denied! Please log in to view your shopping cart.");
        window.location.replace("customer-login.html"); // Using replace prevents backward looping
        return; // Stops any further execution
    }

    // 2. Only if logged in, populate the true username
    const savedName = localStorage.getItem("currentUserName");
    if (savedName) {
        document.getElementById("dynamic-username").textContent = savedName;
    } else {
        document.getElementById("dynamic-username").textContent = "Customer";
    }

    renderLiveCart();
});