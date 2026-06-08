function navigateTo(pageUrl) {
    window.location.href = pageUrl;
}

function logoutUser() {
    localStorage.removeItem("isLoggedIn");
    localStorage.removeItem("currentUserName");
    window.location.href = "index.html";
}


function toggleAccordionItem(headerElement) {
    const parentItem = headerElement.parentElement;
    const contentPanel = parentItem.querySelector(".invoice-accordion-content");
    const arrowIndicator = parentItem.querySelector(".accordion-arrow-indicator");


    const isActive = contentPanel.classList.contains("active-panel");


    document.querySelectorAll(".invoice-accordion-content").forEach(panel => {
        panel.classList.remove("active-panel");
    });
    document.querySelectorAll(".accordion-arrow-indicator").forEach(arrow => {
        arrow.textContent = "▼";
    });


    if (!isActive) {
        contentPanel.classList.add("active-panel");
        arrowIndicator.textContent = "▲";
    }
}

document.addEventListener("DOMContentLoaded", () => {
    
    const currentActiveClient = localStorage.getItem("currentUserName") || "Sayeda Tasnim Sinthia";
    document.getElementById("dynamic-username").textContent = currentActiveClient;

    buildLiveInvoicesFromCache();
});

function buildLiveInvoicesFromCache() {
    const accordionContainer = document.getElementById("invoices-accordion-container");
    if (!accordionContainer) return;


    let liveOrdersHistory = JSON.parse(localStorage.getItem("globalOrderHistory")) || [];
    
   
    if (liveOrdersHistory.length === 0) {
        return; 
    }


    accordionContainer.innerHTML = "";

    liveOrdersHistory.forEach((order, index) => {
   
        const panelVisibilityClass = (index === 0) ? "active-panel" : "";
        const arrowSymbol = (index === 0) ? "▲" : "▼";

        const clientPhone = localStorage.getItem("currentUserPhone") || "+1 (717) 555-2846";
        const clientCity = localStorage.getItem("currentUserCity") || "New York";

        const invoiceItemHTML = `
            <div class="invoice-accordion-item">
                <div class="invoice-accordion-header" onclick="toggleAccordionItem(this)">
                    <span class="invoice-id-lbl">${order.id}</span>
                    <span class="accordion-arrow-indicator">${arrowSymbol}</span>
                </div>
                <div class="invoice-accordion-content ${panelVisibilityClass}">
                    <h3 class="details-section-heading">Product Details</h3>
                    <div class="invoice-table-responsive-box">
                        <table class="invoice-data-table">
                            <thead>
                                <tr>
                                    <th>Type</th>
                                    <th>Sex</th>
                                    <th>Date</th>
                                    <th>Quantity</th>
                                    <th>Weight</th>
                                    <th>Phone Number</th>
                                    <th>Delivery City</th>
                                    <th>Total Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Farm Products Batch</td>
                                    <td>Mixed / N/A</td>
                                    <td>${order.date}</td>
                                    <td>Batch Lot</td>
                                    <td>Standard Cargo</td>
                                    <td>${clientPhone}</td>
                                    <td>${clientCity}</td>
                                    <td class="bold-amount-cell" style="color: #007E2F;">${order.amount}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="payment-info-footer-block">
                        <h4>Payment Information</h4>
                        <p>Cash On delivery</p>
                    </div>
                </div>
            </div>
        `;
        accordionContainer.innerHTML += invoiceItemHTML;
    });
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

function buildLiveInvoicesFromCache() {
    const accordionContainer = document.getElementById("invoices-accordion-container");
    if (!accordionContainer) return;

    let liveOrdersHistory = JSON.parse(localStorage.getItem("globalOrderHistory")) || [];
    

    if (liveOrdersHistory.length === 0) {
        accordionContainer.innerHTML = `
            <p style="padding: 30px; font-size: 18px; color: #000000; text-align: center; ">
                No invoices found. Place an order at checkout to generate invoice sheets!
            </p>`;
        return;
    }

 
    accordionContainer.innerHTML = "";

    liveOrdersHistory.forEach((order, index) => {
        const panelVisibilityClass = (index === 0) ? "active-panel" : "";
        const arrowSymbol = (index === 0) ? "▲" : "▼";

        const clientPhone = localStorage.getItem("currentUserPhone") || "+1 (717) 555-2846";
        const clientCity = localStorage.getItem("currentUserCity") || "New York";

        const invoiceItemHTML = `
            <div class="invoice-accordion-item">
                <div class="invoice-accordion-header" onclick="toggleAccordionItem(this)">
                    <span class="invoice-id-lbl">${order.id}</span>
                    <span class="accordion-arrow-indicator">${arrowSymbol}</span>
                </div>
                <div class="invoice-accordion-content ${panelVisibilityClass}">
                    <h3 class="details-section-heading">Product Details</h3>
                    <div class="invoice-table-responsive-box">
                        <table class="invoice-data-table">
                            <thead>
                                <tr>
                                    <th>Type</th>
                                    <th>Sex</th>
                                    <th>Date</th>
                                    <th>Quantity</th>
                                    <th>Weight</th>
                                    <th>Phone Number</th>
                                    <th>Delivery City</th>
                                    <th>Total Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Farm Products Batch</td>
                                    <td>Mixed / N/A</td>
                                    <td>${order.date}</td>
                                    <td>Batch Lot</td>
                                    <td>Standard Cargo</td>
                                    <td>${clientPhone}</td>
                                    <td>${clientCity}</td>
                                    <td class="bold-amount-cell" style="color: #007E2F;">${order.amount}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="payment-info-footer-block">
                        <h4>Payment Information</h4>
                        <p>Cash On delivery</p>
                    </div>
                </div>
            </div>
        `;
        accordionContainer.innerHTML += invoiceItemHTML;
    });
}