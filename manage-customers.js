function logoutAdmin() {
    localStorage.removeItem("isAdminLoggedIn");
    window.location.href = "index.html";
}

document.addEventListener("DOMContentLoaded", () => {
    const adminSessionName = localStorage.getItem("adminTokenName") || "Sayeda Tasnim Sinthia";
    const usernameBadge = document.getElementById("admin-username");
    if (usernameBadge) usernameBadge.textContent = adminSessionName;

    populateOrdersSection();

    const activeTab = localStorage.getItem("activeAdminTab") || "customer-list";
    localStorage.removeItem("activeAdminTab"); 
    
    const targetCardButton = document.getElementById(`btn-${activeTab}`);
    if (targetCardButton) {
        document.querySelectorAll(".menu-action-card").forEach(btn => btn.classList.remove("active"));
        targetCardButton.classList.add("active");
        
        document.querySelectorAll(".admin-data-section").forEach(sec => sec.classList.add("hidden-section"));
        document.getElementById(`section-${activeTab}`).classList.remove("hidden-section");
    }
});

function showAdminSection(sectionIdKey) {
    document.querySelectorAll(".menu-action-card").forEach(btn => btn.classList.remove("active"));
    event.currentTarget.classList.add("active");

    document.querySelectorAll(".admin-data-section").forEach(sec => sec.classList.add("hidden-section"));
    document.getElementById(`section-${sectionIdKey}`).classList.remove("hidden-section");
}

function populateOrdersSection() {
    const container = document.getElementById("order-rows-container");
    if (!container) return;

    let checkoutOrders = JSON.parse(localStorage.getItem("globalOrderHistory")) || [];

    if (checkoutOrders.length === 0) {
        container.innerHTML = `<tr><td colspan="5" style="text-align:center; padding: 30px; color:#000000; font-weight: 500;">No pending customer orders found in dynamic logs.</td></tr>`;
        return;
    }

    container.innerHTML = "";
    checkoutOrders.forEach((order, index) => {
        container.innerHTML += `
            <tr>
                <td><strong>${order.id}</strong></td>
                <td>${order.date}</td>
                <td style="font-weight:700; color:#13705A;">${order.amount}</td>
                <td><span style="font-weight:bold; color:#0D0551;">${order.status}</span></td>
                <td>
                    <form action="manage-customers.php" method="POST" id="form-order-${index}">
                        <input type="hidden" name="order_index" value="${index}">
                        <select name="order_status_decision" class="admin-select-dropdown" onchange="document.getElementById('form-order-${index}').submit();">
                            <option value="Processing" ${order.status === 'Processing' ? 'selected' : ''}>Processing</option>
                            <option value="Completed" ${order.status === 'Completed' ? 'selected' : ''}>Completed</option>
                            <option value="Cancelled" ${order.status === 'Cancelled' ? 'selected' : ''}>Cancelled</option>
                        </select>
                        <input type="hidden" name="update_order_action" value="1">
                    </form>
                </td>
            </tr>
        `;
    });
}
