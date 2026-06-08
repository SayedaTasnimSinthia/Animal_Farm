function navigateTo(pageUrl) {
    window.location.href = pageUrl;
}

function logoutUser() {
    localStorage.removeItem("isLoggedIn");
    localStorage.removeItem("currentUserName");
    window.location.href = "index.html";
}

document.addEventListener("DOMContentLoaded", () => {
   
    const activeUserToken = localStorage.getItem("currentUserName");
    if (activeUserToken) {
        document.getElementById("dynamic-username").textContent = activeUserToken;
    }


    renderLiveOrderHistory();


    setupFooterNewsletter();
});

function renderLiveOrderHistory() {

    const historyCardContainer = document.querySelector(".history-table-card");
    if (!historyCardContainer) return;


    let storedOrders = JSON.parse(localStorage.getItem("globalOrderHistory")) || [];


    historyCardContainer.innerHTML = `
        <div class="table-row table-header-row">
            <div class="table-cell">ORDER ID</div>
            <div class="table-cell">ORDER DATE</div>
            <div class="table-cell">AMOUNT</div>
            <div class="table-cell">STATUS</div>
        </div>
    `;


    if (storedOrders.length === 0) {
        historyCardContainer.innerHTML += `<p style="text-align:center; padding: 40px; font-size: 18px; color: #555555;">No records found. You haven't placed any orders yet!</p>`;
        return;
    }


    storedOrders.forEach(order => {
      
        const lowerStatus = order.status.toLowerCase(); 
        
        const orderRowHTML = `
            <div class="table-row">
                <div class="table-cell id-text">${order.id}</div>
                <div class="table-cell">${order.date}</div>
                <div class="table-cell price-text">${order.amount}</div>
                <div class="table-cell">
                    <span class="status-badge status-${lowerStatus}">${order.status}</span>
                </div>
            </div>
        `;
        historyCardContainer.innerHTML += orderRowHTML;
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