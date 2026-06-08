<?php
session_start();
include('db.php');


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['save_product_action'])) {
    $prodId = isset($_POST['prod_id']) ? intval($_POST['prod_id']) : 0;
    $name = trim($_POST['fullName']);
    $category = trim($_POST['category']);
    $subTag = trim($_POST['subTag']);
    $sex = trim($_POST['sex']);
    $price = floatval($_POST['price']);
    $image = trim($_POST['image']);
    $description = trim($_POST['description']);

    if ($prodId > 0) {
        $stmt = $conn->prepare("UPDATE products SET name=?, price=?, image=?, category=?, sub_tag=?, sex=?, description=? WHERE id=?");
        $stmt->bind_param("sdsssssi", $name, $price, $image, $category, $subTag, $sex, $description, $prodId);
        $stmt->execute();
        $stmt->close();
    } else {
        $stmt = $conn->prepare("INSERT INTO products (name, price, image, category, sub_tag, sex, description) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sdsssss", $name, $price, $image, $category, $subTag, $sex, $description);
        $stmt->execute();
        $stmt->close();
    }
    header("Location: manage-products.php?view=" . $category);
    exit();
}


if (isset($_GET['delete_item'])) {
    $deleteId = intval($_GET['delete_item']);
    $cat = trim($_GET['cat'] ?? 'Livestock');
    $stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
    $stmt->bind_param("i", $deleteId);
    $stmt->execute();
    $stmt->close();
    header("Location: manage-products.php?view=" . $cat);
    exit();
}

$currentView = isset($_GET['view']) ? trim($_GET['view']) : 'Livestock';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Animal Farm 360 - Manage Products</title>
    <link rel="stylesheet" href="manage-products.css">
    <link href="https://fonts.googleapis.com/css2?family=Livvic:wght@400;900&family=Nunito+Sans:wght@400;700;900&family=Oleo+Script+Swash+Caps&family=Poppins:wght@400;700;900&family=Ubuntu:wght@400;700&display=swap" rel="stylesheet">
</head>
<body>

    <header class="main-header">
        <div class="logo-area">
            <div class="logo-circle"></div>
            <span class="logo-text">Animal Farm 360</span>
        </div>
        <nav class="nav-links">
            <a href="admin-dashboard.html">Dashboard</a>
            <a href="#" onclick="window.history.back()">Back</a>
            <a href="#" onclick="logoutAdmin()">Log Out</a>
            <button class="login-btn">
                <span class="user-emoji">🙍‍♂️</span>
                <span id="admin-username">Sayeda Tasnim Sinthia</span>
            </button>
        </nav>
    </header>

    <div class="admin-dashboard-layout">
        <aside class="admin-sidebar-menu">
            <div class="menu-section">
                <h4 class="section-label-heading">Animal Actions</h4>
                <a href="manage-products.php?view=Livestock" class="menu-link-btn <?php echo $currentView === 'Livestock' ? 'active' : ''; ?>" style="text-decoration:none; display:block;">📄 View Animals List</a>
                <button class="menu-link-btn" onclick="openFormForNew('Livestock')">➕ Add Live Animal</button>
            </div>
           
            <div class="menu-section">
                <h4 class="section-label-heading">Product Actions</h4>
                <a href="manage-products.php?view=Produce" class="menu-link-btn <?php echo $currentView === 'Produce' ? 'active' : ''; ?>" style="text-decoration:none; display:block;">📄 View Produce List</a>
                <button class="menu-link-btn" onclick="openFormForNew('Produce')">➕ Add Farm Produce</button>
            </div>
        </aside>

        <main class="form-workspace-panel">
            <h1 class="portal-main-title" id="inventory-view-title">Manage <?php echo $currentView; ?> Inventory</h1>
           
            <div class="inventory-logs-wrapper" id="live-inventory-table">
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
                        <?php
                        $stmt = $conn->prepare("SELECT * FROM products WHERE category = ? ORDER BY id DESC");
                        $stmt->bind_param("s", $currentView);
                        $stmt->execute();
                        $res = $stmt->get_result();
                        while ($row = $res->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td><strong>" . htmlspecialchars($row['name']) . "</strong></td>";
                            echo "<td>" . htmlspecialchars($row['sub_tag']) . "</td>";
                            echo "<td style='color:#13705A; font-weight:bold;'>$" . number_format($row['price'], 2) . "</td>";
                            echo "<td><span class='edit-action-link' onclick='triggerEditForm(" . json_encode($row) . ")'>Click to Edit/Modify</span></td>";
                            echo "</tr>";
                        }
                        $stmt->close();
                        ?>
                    </tbody>
                </table>
            </div>

            <div class="profile-card-form hidden-form" id="product-manipulation-form">
                <h2 class="form-action-title" id="form-action-title">Add New Product</h2>
                <form action="manage-products.php" method="POST">
                    <input type="hidden" id="prod-index-id" name="prod_id" value="0">
                    <input type="hidden" id="prod-category" name="category" value="<?php echo $currentView; ?>">
               
                    <div class="form-input-group">
                        <label>Product/Animal Name *</label>
                        <input type="text" id="prod-name" name="fullName" placeholder="e.g., Merino Sheep or Fresh Mozzarella" required>
                    </div>

                    <div class="form-input-group">
                        <label>Sub-category Tag (Species/Type) *</label>
                        <input type="text" id="prod-sub-tag" name="subTag" placeholder="e.g., Sheep, Cattle, Dairy, Honey, Meat" required>
                    </div>

                    <div class="form-input-group" id="sex-field-group">
                        <label>Gender/Sex (If Live Stock)</label>
                        <input type="text" id="prod-sex" name="sex" placeholder="e.g., Male, Female, or N/A">
                    </div>

                    <div class="form-input-group">
                        <label>Price ($ USD amount) *</label>
                        <input type="number" step="0.01" id="prod-price" name="price" placeholder="e.g., 250" required>
                    </div>

                    <div class="form-input-group">
                        <label>Image Reference filename *</label>
                        <input type="text" id="prod-image" name="image" placeholder="e.g., images/merinosheep.png" required>
                    </div>

                    <div class="form-input-group">
                        <label>Detailed Description *</label>
                        <textarea id="prod-description" name="description" rows="3" placeholder="Describe the item parameters..." required></textarea>
                    </div>

                    <div class="action-submit-row" id="form-buttons-container">
                        <button type="submit" name="save_product_action" class="save-profile-btn">SAVE CHANGES</button>
                        <button type="button" class="save-profile-btn" style="background:#838383;" onclick="closeActionForm()">CLOSE</button>
                    </div>
                </form>
            </div>
        </main>
    </div>

    <footer class="main-footer">
        <div class="footer-top">
            <div class="footer-brand">
                <div class="logo-circle big"></div>
                <span class="logo-text">Animal Farm 360</span>
            </div>
            <div class="footer-links">
                <h4>About</h4>
                <a href="faq.html">FAQ</a>
                <a href="about-us.html">About Us</a>
                <a href="cookie-policy.html">Cookie Policy</a>
                <a href="privacy-policy.html">Privacy Policy</a>
                <a href="terms-conditions.html">Terms & Condition</a>
            </div>
            <div class="footer-newsletter">
                <h4>Newsletter</h4>
                <p>Subscribe to our Weekly Newsletter & Receive Latest Update</p>
                <div class="subscribe-box">
                    <input type="email" placeholder="Enter your mail here...">
                    <button class="go-btn">Go</button>
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            <p>© 2026 Animal Farm 360 | All Rights Reserved</p>
        </div>
    </footer>

    <script>
        function logoutAdmin() { localStorage.clear(); window.location.href = 'index.html'; }
        function closeActionForm() { document.getElementById("product-manipulation-form").classList.add("hidden-form"); }
        
        function openFormForNew(catGroup) {
            document.getElementById("product-manipulation-form").classList.remove("hidden-form");
            document.getElementById("prod-index-id").value = "0";
            document.getElementById("prod-name").value = "";
            document.getElementById("prod-sub-tag").value = "";
            document.getElementById("prod-sex").value = (catGroup === "Livestock") ? "Male" : "N/A";
            document.getElementById("prod-price").value = "";
            document.getElementById("prod-image").value = "images/logo.png";
            document.getElementById("prod-description").value = "";
            document.getElementById("form-action-title").textContent = "Add New " + catGroup;
            document.getElementById("form-buttons-container").innerHTML = `
                <button type="submit" name="save_product_action" class="save-profile-btn">CREATE NEW</button>
                <button type="button" class="save-profile-btn" style="background:#838383;" onclick="closeActionForm()">CLOSE</button>
            `;
        }

        function triggerEditForm(item) {
            document.getElementById("product-manipulation-form").classList.remove("hidden-form");
            document.getElementById("prod-index-id").value = item.id;
            document.getElementById("prod-name").value = item.name;
            document.getElementById("prod-sub-tag").value = item.sub_tag;
            document.getElementById("prod-sex").value = item.sex;
            document.getElementById("prod-price").value = item.price;
            document.getElementById("prod-image").value = item.image;
            document.getElementById("prod-description").value = item.description;
            document.getElementById("form-action-title").textContent = "Modify Entry ID: " + item.id;
            document.getElementById("form-buttons-container").innerHTML = `
                <button type="submit" name="save_product_action" class="save-profile-btn">SAVE CHANGES</button>
                <a href="manage-products.php?delete_item=${item.id}&cat=${item.category}" class="save-profile-btn delete-btn" style="text-align:center; line-height:65px; text-decoration:none;" onclick="return confirm('Wipe this item permanently?')">DELETE ITEM</a>
                <button type="button" class="save-profile-btn" style="background:#838383;" onclick="closeActionForm()">CANCEL</button>
            `;
        }
        document.addEventListener("DOMContentLoaded", () => {
            document.getElementById("admin-username").textContent = localStorage.getItem("adminTokenName") || "Sayeda Tasnim Sinthia";
        });
    </script>
</body>
</html>