function saveProfileData() {
    const profileName = document.getElementById("prof-name").value.trim();
    const profilePhone = document.getElementById("prof-phone").value.trim();
    const profileAltPhone = document.getElementById("prof-alt-phone").value.trim();
    const profileEmail = document.getElementById("prof-email").value.trim();
    const profileCity = document.getElementById("prof-city").value.trim();
    const profileAddress = document.getElementById("prof-address").value.trim();
    const profileNewPass = document.getElementById("prof-new-pass").value;

    
    if(profileName === "" || profilePhone === "" || profileEmail === "" || profileCity === "" || profileAddress === "") {
        alert("Please make sure to fill out all the necessary required input fields marked with an asterisk (*).");
        return;
    }

    const payload = {
        name: profileName,
        phone: profilePhone,
        altPhone: profileAltPhone,
        email: profileEmail, 
        city: profileCity,
        address: profileAddress,
        newPass: profileNewPass
    };


    fetch('profile-update-handler.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(payload)
    })
    .then(res => res.json())
    .then(data => {
        if(data.status === 'success') {
            
            localStorage.setItem("currentUserName", profileName);
            localStorage.setItem("currentUserPhone", profilePhone);
            localStorage.setItem("currentUserAltPhone", profileAltPhone);
            localStorage.setItem("currentUserEmail", profileEmail);
            localStorage.setItem("currentUserCity", profileCity);
            localStorage.setItem("currentUserAddress", profileAddress);

            document.getElementById("dynamic-username").textContent = profileName;
            alert(`Success! Profile data for "${profileName}" has been permanently saved to the database! 🎉`);
        } else {
            alert("Database Error: Failed to save data onto database registries.");
        }
    })
    .catch(err => {
        console.error("Error updating profile:", err);
        alert("Server connection failed. Make sure XAMPP is running!");
    });
}