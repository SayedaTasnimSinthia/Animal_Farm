function navigateTo(pageUrl) {
    window.location.href = pageUrl;
}

function logoutUser() {
    localStorage.removeItem("isLoggedIn");
    localStorage.removeItem("currentUserName");
    window.location.href = "index.html";
}

document.addEventListener("DOMContentLoaded", () => {
    const loginStatus = localStorage.getItem("isLoggedIn");
    if (loginStatus !== "true") {
        alert("Access Denied! Please log in to complete your purchase.");
        window.location.href = "customer-login.html";
        return;
    }

   
    const savedName = localStorage.getItem("currentUserName") || "George Miller";
    const savedEmail = localStorage.getItem("currentUserEmail") || "";
    const savedPhone = localStorage.getItem("currentUserPhone") || "";
    const savedAddress = localStorage.getItem("currentUserAddress") || "";
    const savedCity = localStorage.getItem("currentUserCity") || "";

   
    document.getElementById("dynamic-username").textContent = savedName;


    document.getElementById("chk-name").value = savedName;
    document.getElementById("chk-email").value = savedEmail;
    document.getElementById("chk-phone").value = savedPhone;
    document.getElementById("chk-address").value = savedAddress;
    document.getElementById("chk-city").value = savedCity;
    
   
    const countryField = document.getElementById("chk-country");
    if(countryField && !countryField.value) {
        countryField.value = "USA";
    }


    calculateCheckoutInvoice();
});

function calculateCheckoutInvoice() {
    let cart = JSON.parse(localStorage.getItem('farmCart')) || [];
    let subtotalSum = 0;

    cart.forEach(item => {
        subtotalSum += (Number(item.price) || 0) * (Number(item.quantity) || 0);
    });


    let discountDeduction = subtotalSum * 0.15;
    const shippingSurcharge = 30;
    const gstSurcharge = 20;
    let absoluteNetTotal = subtotalSum + shippingSurcharge + gstSurcharge - discountDeduction;
    
    if (subtotalSum === 0) absoluteNetTotal = 0;

    document.getElementById("invoice-subtotal").textContent = `$${subtotalSum.toFixed(2)}`;
    document.getElementById("invoice-discount").textContent = `-$${discountDeduction.toFixed(2)}`;
    document.getElementById("invoice-total").textContent = `$${absoluteNetTotal.toFixed(2)}`;
}

function executeFinalOrderPlacement() {
    const buyerName = document.getElementById("chk-name").value.trim();
    const buyerAddress = document.getElementById("chk-address").value.trim();
    const buyerCountry = document.getElementById("chk-country").value.trim();
    const finalInvoiceValue = document.getElementById("invoice-total").textContent;
    
    let cart = JSON.parse(localStorage.getItem('farmCart')) || [];

    if (cart.length === 0) {
        alert("Your cart is empty! Cannot place order.");
        return;
    }
    if (buyerName === "" || buyerAddress === "" || buyerCountry === "") {
        alert("Please make sure your name, shipping address, and country fields are filled out.");
        return;
    }

    const randomOrderId = "ID - " + Math.floor(100000000000 + Math.random() * 900000000000);

    const today = new Date();
    const formattedDate = `${String(today.getDate()).padStart(2, '0')}/${String(today.getMonth() + 1).padStart(2, '0')}/${today.getFullYear()}`;

    const newOrder = {
        id: randomOrderId,
        date: formattedDate,
        amount: finalInvoiceValue,
        status: "Processing"
    };

    let orderHistoryList = JSON.parse(localStorage.getItem("globalOrderHistory")) || [];
    orderHistoryList.unshift(newOrder);
    localStorage.setItem("globalOrderHistory", JSON.stringify(orderHistoryList));

    alert(`Order Placed Successfully!\n\nOrder ID: ${randomOrderId}\nShipping To: ${buyerCountry}\nTotal: ${finalInvoiceValue}\n\nYou will be contacted shortly to confirm.`);
    
    localStorage.removeItem('farmCart');
    window.location.href = "order-history.html";
}


document.body.addEventListener("click", (event) => {
    if (event.target && event.target.classList.contains("go-btn")) {
        event.preventDefault();

        const subscribeContainer = event.target.closest(".subscribe-box");
        const emailField = subscribeContainer ? subscribeContainer.querySelector("input") : null;
        const typedEmail = emailField ? emailField.value.trim() : "";

        const strictEmailRegex = /^[A-Za-z][A-Za-z0-9._%+-]*@(gmail|yahoo|hotmail|outlook)\.com$/;

        if (strictEmailRegex.test(typedEmail)) {
            let currentSubscribers = JSON.parse(localStorage.getItem("newsletterSubscribers")) || [];

            if (!currentSubscribers.includes(typedEmail)) {
                currentSubscribers.push(typedEmail);
                localStorage.setItem("newsletterSubscribers", JSON.stringify(currentSubscribers));
            }

            alert("Thank you for signing up to the Animal Farm 360 newsletter!");
            if (emailField) emailField.value = "";

            
            populateSubscribersSection();
        } else {
            alert("Invalid Email Format!\n\nYour email must:\n1. Start with a letter.\n2. Contain an '@' symbol.\n3. Use a supported provider (gmail, yahoo, hotmail, outlook).\n4. End with '.com'.");
        }
    }
});