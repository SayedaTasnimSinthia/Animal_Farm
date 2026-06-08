function routeToPortal(targetPageUrl) {
    // This will now forward all clicks straight to whatever URL you passed in
    console.log(`Forwarding administrative terminal to: ${targetPageUrl}`);
    window.location.href = targetPageUrl;
}

function logoutAdmin() {
    alert("Admin Secure Session Cleared... Terminating Control Terminal Access logs.");
    localStorage.removeItem("isAdminLoggedIn");
    localStorage.removeItem("adminTokenName");
    window.location.href = "index.html";
}

function validateCustomerLogin() {
    const emailInput = document.getElementById("customer-email").value.trim();
    const passwordInput = document.getElementById("customer-password").value.trim();

    if (emailInput === "" || passwordInput === "") {
        alert("Please completely fill out both your email and password fields.");
        return;
    }


    if (emailInput === "a" && passwordInput === "a") {
        localStorage.setItem("isAdminLoggedIn", "true");
        localStorage.setItem("adminTokenName", "Sayeda Tasnim Sinthia");

        alert("Login Successful! Redirecting you straight to your Admin Dashboard panel.");
        window.location.href = "admin-dashboard.html";
    } else {
        alert("Error: Invalid email or password.");
    }
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