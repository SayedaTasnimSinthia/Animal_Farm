function navigateTo(pageUrl) {
    window.location.href = pageUrl;
}

function logoutUser() {
    alert("Logging out from your account safely... Redirecting home.");
    localStorage.clear();
    window.location.href = "index.html";
}

console.log("Profile form submission runtime channels initialized.");