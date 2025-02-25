<?php
include '../database/database.php';
session_start();

try {
    $stmt = $conn->query("SELECT * FROM products");
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error = "Database error: " . $e->getMessage();
}

try{
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $id = $_POST['id'];
        $productName = $_POST['productName'];
        $productType = $_POST['productType'];
        $supplierName = $_POST['supplierName'];
        $price = $_POST['price'];
        $stock = $_POST['stock'];

        $imagePath = null;
        if (!empty($_FILES['productImage']['name'])) {
            $targetDir = "uploads/";
            if (!is_dir($targetDir)) {
                mkdir($targetDir, 0777, true); 
            }
            $targetFile = $targetDir . basename($_FILES["productImage"]["name"]);
            
            //Move file and check if successful
            if (move_uploaded_file($_FILES["productImage"]["tmp_name"], $targetFile)) {
                $imagePath = $targetFile; // Save the image path if move was successful
            } else {
                echo "<script>alert('Error uploading image.');</script>";
                $imagePath = null; // Ensure image path is null if upload fails
            }
        }
        $stmt = $conn->prepare("INSERT INTO products (product_name, product_type, supplier_name, price, stock, image_path) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$productName, $productType, $supplierName, $price, $stock, $imagePath]);

        echo "<script>alert('Product added successfully!'); </script>";
    }
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());

}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory Dashboard</title>
    <link href="../statics/bootstrap css/bootstrap.min.css" rel="stylesheet">
    <link href="../statics/Inventory.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/31e24a5c2a.js" crossorigin="anonymous"></script>
    
</head>
<body>
<div class="left-sidebar">
        <img src="../images/Logo.jpg" alt="Le Parisien" class="logo">
        <ul class="menu">
        <li><i class="fa fa-home"></i><span><a href="dashboard.php" style="color: white; text-decoration: none;"> Home</a></span></li>
            <li><i class="fa fa-box"></i><span><a href="Inventory.php" style="color: white; text-decoration: none;"> Inventory</a></span></li>
            <li><i class="fa fa-credit-card"></i><span><a href="Payment.php" style="color: white; text-decoration: none;"> Payment</a></span></li>

            <li class="dropdown">
                <i class="fa fa-store"></i><span> Retailer</span><i class="fa fa-chevron-down toggle-btn"></i>
                <ul class="submenu">
                    <li><a href="supplier.php" style="color: white; text-decoration: none;">Supplier</a></li>
                    <li><a href="SupplierOrder.php" style="color: white; text-decoration: none;">Supplier Order</a></li>
                </ul>
            </li>

            <li class="dropdown">
                <i class="fa fa-chart-line"></i><span> Sales</span><i class="fa fa-chevron-down toggle-btn"></i>
                <ul class="submenu">
                    <li>Customers</li>
                    <li>Invoice</li>
                    <li>Sales Order</li>
                </ul>
            </li>

            <li class="dropdown">
                <i class="fa fa-store"></i><span> Bills</span><i class="fa fa-chevron-down toggle-btn"></i>
                <ul class="submenu">
                    <li><a href="supplier.php" style="color: white; text-decoration: none;">Invoice</a></li>
                    <li><a href="SupplierOrder.php" style="color: white; text-decoration: none;">Payment</a></li>
                </ul>
            </li>

            <li class="dropdown">
                <i class="fa fa-store"></i><span> Reports</span><i class="fa fa-chevron-down toggle-btn"></i>
                <ul class="submenu">
                    <li><a href="supplier.php" style="color: white; text-decoration: none;">Sales Report</a></li>
                    <li><a href="SupplierOrder.php" style="color: white; text-decoration: none;">Inventory Report</a></li>
                    <li><a href="SupplierOrder.php" style="color: white; text-decoration: none;">Payment Report</a></li>
                </ul>
            </li>
            
            <li class="dropdown">
                <i class="fa fa-store"></i><span> Admin</span><i class="fa fa-chevron-down toggle-btn"></i>
                <ul class="submenu">
                    <li><a href="supplier.php" style="color: white; text-decoration: none;">User Management </a></li>
                    <li><a href="SupplierOrder.php" style="color: white; text-decoration: none;">Employees</a></li>
                    <li><a href="SupplierOrder.php" style="color: white; text-decoration: none;">Audit Logs</a></li>
                </ul>
            </li>
        </ul>
    </div>

    <div class="main-content">
        <h1>Inventory</h1>
        <div class="d-flex justify-content-between mb-3">
            <div class="search-container">
            <i class="fa fa-search"></i>
            <input type="text" class="form-control" placeholder="Search...">
            </div>
            <div>
                <button class="btn btn-dark" id="newProductBtn">New <i class="fa fa-plus"></i></button>
                <div id="newProductForm" style="display: none; position: absolute; top: 50%; left: 55%; transform: translate(-50%, -50%); background: lightblue; padding: 40px; box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2); z-index: 1000; width: 1000px; height: 500px;">
                    <button id="closeFormBtn" style="position: absolute; top: 0px; right: 10px; background: none; border: none; font-size: 40px; cursor: pointer;">&times;</button>
                    <h2 class="text-center mb-4">Add New Product</h2>
                    <form method="POST" enctype="multipart/form-data">
                        
    <div class="form-group d-flex align-items-center mb-3">
        <label for="productName" class="mr-3" style="width: 150px;">Product Name</label>
        <input type="text" class="form-control" id="productName" name="productName" placeholder="Enter product name" style="width: 350px; height: 50px;" required>
    </div>

    <div class="form-group d-flex align-items-center mb-3">
        <label for="productType" class="mr-3" style="width: 150px;">Product Type</label>
        <input type="text" class="form-control" id="productType" name="productType" placeholder="Enter product type" style="width: 350px; height: 50px;" required>
    </div>

    <div class="form-group d-flex align-items-center mb-3">
        <label for="supplierName" class="mr-3" style="width: 150px;">Supplier Name</label>
        <input type="text" class="form-control" id="supplierName" name="supplierName" placeholder="Enter supplier name" style="width: 350px; height: 50px;" required>
    </div>

    <div class="form-group d-flex align-items-center mb-3">
        <label for="price" class="mr-3" style="width: 150px;">Price</label>
        <input type="number" class="form-control" id="price" name="price" placeholder="Enter price" style="width: 350px; height: 50px;" required>
    </div>

    <div class="form-group d-flex align-items-center mb-3">
        <label for="stock" class="mr-3" style="width: 150px;">Stock</label>
        <input type="number" class="form-control" id="stock" name="stock" placeholder="Enter stock" style="width: 350px; height: 50px;" required>
    </div>

    
    <div id="imageContainer" style="position: absolute; top: 120px; right: 110px; width: 200px; height: 200px; border: 2px solid rgba(0, 0, 0, 0.7); display: flex; justify-content: center; align-items: center; overflow: hidden; border-radius: 8px;">
    <img id="previewImage" src="<?= isset($uploadedImage) && !empty($uploadedImage) ? htmlspecialchars($uploadedImage) : '#'; ?>" alt="Image Preview" style="<?= isset($uploadedImage) && !empty($uploadedImage) ? 'display: block;' : 'display: none;'; ?> width: 100%; height: 100%; object-fit: cover; cursor: pointer;">
    <input type="file" name="productImage" id="productImage" accept="image/*" style="display: none;">
    <?php if (!isset($uploadedImage) || empty($uploadedImage)): ?>
        <label for="productImage" id="uploadLabel" style="cursor: pointer; background-color: #6c757d; color: white; padding: 10px 15px; border-radius: 5px; font-size: 14px; width: 90%; text-align: center;">
            Upload Image
        </label>
    <?php endif; ?>
</div>

<script>
    const productImageInput = document.getElementById('productImage');
    const previewImage = document.getElementById('previewImage');
    const uploadLabel = document.getElementById('uploadLabel');

    productImageInput.addEventListener('change', function () {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function () {
                previewImage.src = reader.result;
                previewImage.style.display = 'block';
                if (uploadLabel) uploadLabel.style.display = 'none';
            };
            reader.readAsDataURL(file);
        }
    });

    previewImage.addEventListener('click', () => {
        productImageInput.click();
    });

    window.addEventListener('load', () => {
        if (previewImage.src && previewImage.src !== window.location.href + '#') {
            if (uploadLabel) uploadLabel.style.display = 'none';
        }
    });
</script>

<button type="submit" class="btn btn-primary">Submit</button>
                    <label for="productImage" class="mb-2" style="position: absolute; top: 80px; right: 180px; font-size: 25px;">Edit Image</label>
                    
                </div>
                <script>
                    document.getElementById('closeFormBtn').addEventListener('click', function() {
                        document.getElementById('newProductForm').style.display = 'none';
                    });
                </script>
                <script>
                    document.getElementById('newProductBtn').addEventListener('click', function() {
                        var form = document.getElementById('newProductForm');
                        form.style.display = form.style.display === 'none' ? 'block' : 'none';
                    });
                </script>
                <select class="btn btn-outline-secondary">
                    <option>Order By</option>
                    <option>Ascending (A → Z)</option>
                    <option>Descending (Z → A)</option>
                    <option>Low Price (Ascending)</option>
                    <option>High Price (Descending)</option>
                    <option>Newest</option>
                    <option>Oldest</option>
                    <option>Best Seller</option>
                    <option>Active</option>
                    <option>Inactive</option>
                </select>
                <select class="btn btn-outline-secondary">
                    <option>Filtered By</option>
                    <option>Product Type</option>
                    <option>Product Supplier</option>
                    <option>Below ₱1,000</option>
                    <option>₱1,000 - ₱5,000</option>
                    <option>₱5,000 - ₱10,000</option>
                    <option>Above ₱10,000</option>
                    <option>In-Stock</option>
                    <option>Out of Stock</option>
                </select>
            </div>
        </div>

        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>Prod ID</th>
                    <th>Product</th>
                    <th>Type Name</th>
                    <th>Supplier Name</th>
                    <th>Price</th>
                    <th>Stock</th>
                    <th>Image</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            <?php if (isset($error)): ?>
            <tr><td colspan="7" class="text-center text-danger"><?= htmlspecialchars($error) ?></td></tr>
        <?php elseif (!empty($products)): ?>
            <?php foreach ($products as $product): ?>
                <tr>
                 <td><?= htmlspecialchars($product['id']) ?></td>
                    <td><?= htmlspecialchars($product['product_name']) ?></td>
                    <td><?= htmlspecialchars($product['product_type']) ?></td>
                    <td><?= htmlspecialchars($product['supplier_name']) ?></td>
                    <td>₱<?= number_format($product['price'], 2) ?></td>
                    <td><?= htmlspecialchars($product['stock']) ?></td>
                    <td><img src="<?= htmlspecialchars($product['image_path']) ?>" alt="Product Image" width="80" height="80"></td>
                    <td>
                    <div class="d-flex align-items-center">
    <button class="btn btn-warning btn-sm me-1">
        <i class="fas fa-edit"></i> <!-- Edit icon -->
    </button>

    <button class="btn btn-info btn-sm me-1">
        <i class="fas fa-barcode"></i> <!-- Scanner icon -->
    </button>

    <select class="form-select form-select-sm" style="width: 100px; font-size: 14px; padding: 3px 8px;">
        <option>Active</option>
        <option>Inactive</option>
    </select>
</div>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="7" class="text-center text-muted">No inventory available. Input new inevtentory.</td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var dropdowns = document.querySelectorAll('.dropdown');
        dropdowns.forEach(function(dropdown) {
            var toggleBtn = dropdown.querySelector('.toggle-btn');
            toggleBtn.addEventListener('click', function() {
                dropdown.classList.toggle('active');
            });
        });
    });

    document.getElementById("productImage").addEventListener("change", function(event) {
        const preview = document.getElementById("previewImage");
        const file = event.target.files[0];

        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.style.display = "block";
            };
            reader.readAsDataURL(file);
        }
    });

    function removeRow(button, productId) {
    if (confirm("Are you sure you want to archive this product?")) {
        fetch('archive_product.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: 'product_id=' + encodeURIComponent(productId)
        })
        .then(response => response.text())
        .then(data => {
            if (data === 'success') {
                alert('Product archived successfully!');
                const row = button.closest('tr');
                row.parentNode.removeChild(row);
            } else {
                alert('Error: ' + data);
            }
        })
        
    }
}

</script>
</body>
</html>
