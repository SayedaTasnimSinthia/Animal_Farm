
let globallyLoadedActiveProduct = null;

document.addEventListener("DOMContentLoaded", () => {

    syncUserSessionHeader();


    const urlParams = new URLSearchParams(window.location.search);
    const productIndexId = urlParams.get('item');

    if (productIndexId !== null) {
        loadDynamicProductDetails(parseInt(productIndexId));
    } else {
        
        loadDynamicProductDetails(0);
    }
});

function syncUserSessionHeader() {
    const savedCustomerName = localStorage.getItem("currentUserName");
    const userBadgeBtn = document.getElementById("header-user-badge");

    if (savedCustomerName && userBadgeBtn) {
        userBadgeBtn.textContent = savedCustomerName;
      
        userBadgeBtn.onclick = null;
        userBadgeBtn.style.cursor = "default";
    }
}

function loadDynamicProductDetails(indexId) {

    let inventoryList = JSON.parse(localStorage.getItem("farmInventory")) || [];

    if (inventoryList.length === 0 || !inventoryList[indexId]) {
        console.error("Requested entry index parameters do not exist inside inventory databases.");
        return;
    }

    globallyLoadedActiveProduct = inventoryList[indexId];


    document.getElementById("det-name").textContent = globallyLoadedActiveProduct.name;
    document.getElementById("det-price").textContent = `$${Number(globallyLoadedActiveProduct.price).toFixed(2)}`;
    document.getElementById("det-description").textContent = globallyLoadedActiveProduct.description;

    document.getElementById("det-image").style.backgroundImage = `url('${globallyLoadedActiveProduct.image || 'images/logo.png'}')`;


    document.getElementById("spec-category").textContent = globallyLoadedActiveProduct.category;
    document.getElementById("spec-subtag").textContent = globallyLoadedActiveProduct.subTag;


    const genderRow = document.getElementById("gender-spec-row");
    if (globallyLoadedActiveProduct.category === "Produce") {
        genderRow.style.display = "none";
    } else {
        genderRow.style.display = "flex";
        document.getElementById("spec-gender").textContent = globallyLoadedActiveProduct.sex || "Mixed / N/A";
    }
}

function triggerAddToCartFromDetails() {
    if (!globallyLoadedActiveProduct) return;

    let cart = JSON.parse(localStorage.getItem('farmCart')) || [];
    let existingItem = cart.find(item => item.name === globallyLoadedActiveProduct.name);

    if (existingItem) {
        existingItem.quantity += 1;
    } else {
        cart.push({
            name: globallyLoadedActiveProduct.name,
            price: parseFloat(globallyLoadedActiveProduct.price),
            image: globallyLoadedActiveProduct.image,
            category: globallyLoadedActiveProduct.category,
            quantity: 1
        });
    }

    localStorage.setItem('farmCart', JSON.stringify(cart));
    alert(`"${globallyLoadedActiveProduct.name}" has been added to your shopping cart from details view! 🛍️`);
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