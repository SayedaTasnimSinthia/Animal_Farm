
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