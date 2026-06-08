function navigateTo(pageUrl) {
    window.location.href = pageUrl;
}

function logoutUser() {
    alert("Logging out from your account safely... Redirecting home.");

    localStorage.clear();
    

    window.location.href = "logout.php";
}

document.addEventListener("DOMContentLoaded", () => {
    const verifiedSessionName = localStorage.getItem("currentUserName") || "Sayeda Tasnim Sinthia";
    const userBadge = document.getElementById("dynamic-username");
    if (userBadge) {
        userBadge.textContent = verifiedSessionName;
    }
});
