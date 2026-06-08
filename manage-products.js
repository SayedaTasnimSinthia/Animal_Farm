function logoutAdmin() {
    localStorage.removeItem("isAdminLoggedIn");
    window.location.href = "index.html";
}


const seedDefaultProducts = [
    { name: "Duck Egg - 12 piece", price: 25, image: "images/egg.jpg", category: "Produce", subTag: "Egg", sex: "Female", description: "Farm fresh gathered daily." },
    { name: "Cow Milk - 1 liter", price: 17, image: "images/images.jpg", category: "Produce", subTag: "Milk", sex: "N/A", description: "Rich preservative free milk." },
    { name: "Dorper Sheep", price: 250, image: "images/dorpersheep.png", category: "Livestock", subTag: "Sheep", sex: "Male", description: "Exceptional health meat sheep breed." },
    { name: "Mozzarella Cheese - 1 kg", price: 21, image: "images/mozarella.png", category: "Produce", subTag: "Cheese", sex: "N/A", description: "Artisanal cultured cheese recipe." }
];


let currentActiveViewCategory = "Livestock";

document.addEventListener("DOMContentLoaded", () => {
    if (!localStorage.getItem("farmInventory")) {
        localStorage.setItem("farmInventory", JSON.stringify(seedDefaultProducts));
    }

    const cachedUser = localStorage.getItem("adminTokenName") || "Sayeda Tasnim Sinthia";
    const profileTag = document.getElementById("admin-username-btn");
    if (profileTag) profileTag.textContent = cachedUser;

    
    renderInventoryTable();
});

function renderInventoryTable() {
    const tableWrapper = document.getElementById("live-inventory-table");
    let inventory = JSON.parse(localStorage.getItem("farmInventory")) || [];

    let filteredList = inventory.filter(item => item.category === currentActiveViewCategory);


    document.getElementById("inventory-view-title").textContent = `Manage ${currentActiveViewCategory} Inventory`;

    if (filteredList.length === 0) {
        tableWrapper.innerHTML = `<p style="padding: 20px; font-size:18px; text-align:center;">No records inside the ${currentActiveViewCategory} storefront group logs yet.</p>`;
        return;
    }

    let tableHTML = `
        <table class="invoice-data-table">
            <thead>
                <tr>
                    <th>PRODUCT NAME</th>
                    <th>SUB-CATEGORY TAG</th>
                    <th>UNIT PRICE</th>
                    <th>ACTIONS LINK</th>
                </tr>
            </thead>
            <tbody>
    `;


    inventory.forEach((item, originalIndexId) => {
        if (item.category !== currentActiveViewCategory) return;

        tableHTML += `
            <tr>
                <td><strong>${item.name}</strong></td>
                <td>${item.subTag}</td>
                <td style="color:#13705A; font-weight:bold;">$${Number(item.price).toFixed(2)}</td>
                <td><span class="edit-action-link" onclick="triggerBottomActionForm(${originalIndexId})">Click to Edit/Modify</span></td>
            </tr>
        `;
    });

    tableHTML += `</tbody></table>`;
    tableWrapper.innerHTML = tableHTML;
}

function switchCategoryView(categoryName) {
    currentActiveViewCategory = categoryName;
    

    document.getElementById("product-manipulation-form").classList.add("hidden-form");
    
    renderInventoryTable();
}


function triggerBottomActionForm(indexId) {
    const formBox = document.getElementById("product-manipulation-form");
    formBox.classList.remove("hidden-form"); 

    let inventory = JSON.parse(localStorage.getItem("farmInventory")) || [];
    const targetItem = inventory[indexId];

    document.getElementById("prod-index-id").value = indexId;
    document.getElementById("prod-name").value = targetItem.name;
    document.getElementById("prod-category").value = targetItem.category;
    document.getElementById("prod-sub-tag").value = targetItem.subTag;
    document.getElementById("prod-sex").value = targetItem.sex;
    document.getElementById("prod-price").value = targetItem.price;
    document.getElementById("prod-image").value = targetItem.image;
    document.getElementById("prod-description").value = targetItem.description;

    document.getElementById("form-action-title").textContent = `Modify Entry ID: ${indexId + 1}`;


    document.getElementById("form-buttons-container").innerHTML = `
        <button class="save-profile-btn" onclick="saveProductToDatabase()">SAVE CHANGE</button>
        <button class="save-profile-btn delete-btn" onclick="deleteProductFromDatabase(${indexId})">DELETE ITEM</button>
        <button class="save-profile-btn" style="background:#838383;" onclick="closeActionForm()">CANCEL</button>
    `;
    

    formBox.scrollIntoView({ behavior: 'smooth' });
}

function openFormForNew(catGroup) {
    currentActiveViewCategory = catGroup;
    renderInventoryTable();

    const formBox = document.getElementById("product-manipulation-form");
    formBox.classList.remove("hidden-form");

    document.getElementById("prod-index-id").value = "";
    document.getElementById("prod-name").value = "";
    document.getElementById("prod-category").value = catGroup;
    document.getElementById("prod-sub-tag").value = "";
    document.getElementById("prod-sex").value = (catGroup === "Livestock") ? "Male" : "N/A";
    document.getElementById("prod-price").value = "";
    document.getElementById("prod-image").value = "images/logo.png";
    document.getElementById("prod-description").value = "";

    document.getElementById("form-action-title").textContent = `Add New ${catGroup} Entry`;
    document.getElementById("form-buttons-container").innerHTML = `
        <button class="save-profile-btn" onclick="saveProductToDatabase()">CREATE NEW</button>
        <button class="save-profile-btn" style="background:#838383;" onclick="closeActionForm()">CLOSE</button>
    `;

    formBox.scrollIntoView({ behavior: 'smooth' });
}

function saveProductToDatabase() {
    const indexId = document.getElementById("prod-index-id").value;
    const name = document.getElementById("prod-name").value.trim();
    const category = document.getElementById("prod-category").value;
    const subTag = document.getElementById("prod-sub-tag").value.trim();
    const sex = document.getElementById("prod-sex").value.trim();
    const price = parseFloat(document.getElementById("prod-price").value);
    const image = document.getElementById("prod-image").value.trim();
    const description = document.getElementById("prod-description").value.trim();

    if (!name || !subTag || isNaN(price)) {
        alert("Please compile all mandatory fields marked with an asterisk (*).");
        return;
    }

    let inventory = JSON.parse(localStorage.getItem("farmInventory")) || [];
    const productPayload = { name, price, image, category, subTag, sex, description };

    if (indexId !== "") {
        inventory[parseInt(indexId)] = productPayload;
        alert(`"${name}" modifications successfully written to cache folders.`);
    } else {
        inventory.push(productPayload);
        alert(`"${name}" inserted successfully into active storefront inventories!`);
    }

    localStorage.setItem("farmInventory", JSON.stringify(inventory));
    closeActionForm();
    renderInventoryTable();
}

function deleteProductFromDatabase(indexId) {
    let inventory = JSON.parse(localStorage.getItem("farmInventory")) || [];
    if (confirm(`Wipe "${inventory[indexId].name}" data rows from public registries permanently?`)) {
        inventory.splice(indexId, 1);
        localStorage.setItem("farmInventory", JSON.stringify(inventory));
        closeActionForm();
        renderInventoryTable();
    }
}

function closeActionForm() {
    document.getElementById("product-manipulation-form").classList.add("hidden-form");
}


/* GLOBAL FOOTER NEWSLETTER DELEGATION HANDLING */
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