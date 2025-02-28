<?php
include '../database/database.php';
session_start();

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);


// Handle AJAX requests
if (isset($_GET['product_id'])) {
    $product_id = $_GET['product_id'];
    $stmt = $conn->prepare("SELECT p.*, u.username AS created_by FROM products p LEFT JOIN users u ON p.userId = u.usersId WHERE p.id = ?");
    $stmt->execute([$product_id]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);
    echo $product ? json_encode($product) : json_encode(['error' => 'Product not found']);
    exit;
}

if (isset($_GET['barcode'])) {
    $barcode = $_GET['barcode'];
    $stmt = $conn->prepare("SELECT p.*, u.username AS created_by FROM products p LEFT JOIN users u ON p.usersId = u.usersId WHERE p.barcode = ?");
    $stmt->execute([$barcode]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);
    echo $product ? json_encode(['product_name' => $product['product_name'], 'created_by' => $product['created_by'], 'created_date' => $product['created_date']]) : json_encode(['error' => 'Not in database']);
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['product_id']) && !isset($_POST['update_status'])) {
        $product_id = $_POST['product_id'];
        $product_name = $_POST['product_name'];
        $product_type = $_POST['product_type'];
        $supplier_name = $_POST['supplier_name'];
        $price = floatval($_POST['price']);
        $stock = intval($_POST['stock']);
        $status = $_POST['status'];
        $stmt = $conn->prepare("UPDATE products SET product_name = ?, product_type = ?, supplier_name = ?, price = ?, stock = ?, status = ? WHERE id = ?");
        $stmt->execute([$product_name, $product_type, $supplier_name, $price, $stock, $status, $product_id]);
        echo 'success';
        exit;
    } elseif (isset($_POST['update_status'])) {
        $product_id = $_POST['product_id'];
        $status = $_POST['status'];
        $stmt = $conn->prepare("UPDATE products SET status = ? WHERE id = ?");
        $stmt->execute([$status, $product_id]);
        echo 'success';
        exit;
    } elseif (isset($_POST['productName']) && !empty($_POST['price']) && !empty($_POST['stock'])) {
        $productName = $_POST['productName'];
        $productType = $_POST['productType'];
        $supplierName = $_POST['supplierName'];
        $price = floatval($_POST['price']);
        $stock = intval($_POST['stock']);
        $status = $_POST['status'];
        $userId = isset($_SESSION['usersId']) ? $_SESSION['usersId'] : 1; // Default to 1 if no user logged in
        if ($price >= 0 && $stock >= 0) {
            $stmt = $conn->prepare("INSERT INTO products (product_name, product_type, supplier_name, price, stock, status, usersId) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$productName, $productType, $supplierName, $price, $stock, $status, $userId]);
        }
    }
}

// Fetch products with sorting and filtering
try {
    $sql = "SELECT p.*, u.username AS created_by FROM products p LEFT JOIN users u ON p.usersId = u.usersId WHERE 1=1";
    $orderBy = isset($_POST['orderBy']) ? $_POST['orderBy'] : '';
    $filterBy = isset($_POST['filterBy']) ? $_POST['filterBy'] : '';
    $whereClause = '';
    $orderClause = " ORDER BY p.id ASC";

    switch ($filterBy) {
        case 'product-type':
            break;
        case 'product-supplier':
            break;
        case 'price-below-1000':
            $whereClause .= " AND p.price < 1000";
            break;
        case 'price-1000-5000':
            $whereClause .= " AND p.price BETWEEN 1000 AND 5000";
            break;
        case 'price-5000-10000':
            $whereClause .= " AND p.price BETWEEN 5000 AND 10000";
            break;
        case 'price-above-10000':
            $whereClause .= " AND p.price > 10000";
            break;
        case 'in-stock':
            $whereClause .= " AND p.stock > 0";
            break;
        case 'out-of-stock':
            $whereClause .= " AND p.stock = 0";
            break;
    }

    switch ($orderBy) {
        case 'name-asc':
            $orderClause = " ORDER BY p.product_name ASC";
            break;
        case 'name-desc':
            $orderClause = " ORDER BY p.product_name DESC";
            break;
        case 'price-asc':
            $orderClause = " ORDER BY p.price ASC";
            break;
        case 'price-desc':
            $orderClause = " ORDER BY p.price DESC";
            break;
        case 'newest':
            $orderClause = " ORDER BY p.created_at DESC";
            break;
        case 'oldest':
            $orderClause = " ORDER BY p.created_at ASC";
            break;
        case 'best-seller':
            $orderClause = " ORDER BY p.stock DESC";
            break;
        case 'active':
            $whereClause .= " AND p.status = 'active'";
            break;
        case 'inactive':
            $whereClause .= " AND p.status = 'inactive'";
            break;
    }

    $sql .= $whereClause . $orderClause;
    $stmt = $conn->query($sql);
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error = "Database error: " . $e->getMessage();
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
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/@zxing/library@latest/umd/index.min.js"></script>
    <style>
        .left-sidebar {
            position: fixed;
            top: 0;
            left: -250px; /* Initially hidden */
            width: 250px;
            height: 100%;
            background-color: #343F79;
            transition: left 0.3s ease;
            z-index: 1000; /* Above main content */
        }
        .left-sidebar.active {
            left: 0; /* Visible when active */
        }
        .main-content {
            margin-left: 0; /* Fixed position, no shift */
            display: flex;
            flex-direction: column;
            width: 100%;
        }
        .menu-btn {
            font-size: 24px;
            background: none;
            border: none;
            cursor: pointer;
            margin-right: 10px;
            color: #000;
        }
        .header-container {
            display: flex;
            align-items: center;
            justify-content: flex-start;
            width: 100%;
            padding: 10px;
        }
        .controls-container {
            display: flex;
            align-items: center;
            justify-content: flex-start;
            margin-bottom: 15px;
            width: 100%;
        }
        .search-container {
            position: relative;
            width: 300px;
            margin-right: 550px;
            margin-left: 40px;
        }
        .search-container .form-control {
            padding-left: 35px;
        }
        .search-container .fa-search {
            position: absolute;
            left: 10px;
            top: 50%;
            transform: translateY(-50%);
            color: #6c757d;
        }
        .btn-dark.mr-2 {
            margin-right: 15px;
        }
        .btn-outline-secondary.mr-2 {
            margin-right: 15px;
        }
        .table-container {
            width: 100%;
            overflow-x: auto;
            margin-left: auto;
        }
        .table {
            width: 100%;
            table-layout: auto;
        }
        .table th, .table td {
            white-space: nowrap;
            padding: 8px;
        }
    </style>
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
                <li><a href="Customers.php" style="color: white; text-decoration: none;">Customers</a></li>
                <li><a href="Invoice.php" style="color: white; text-decoration: none;">Invoice</a></li>
                <li><a href="CustomerOrder.php" style="color: white; text-decoration: none;">Customer Order</a></li>
            </ul>
        </li>
        <li class="dropdown">
            <i class="fa fa-store"></i><span> Admin</span><i class="fa fa-chevron-down toggle-btn"></i>
            <ul class="submenu">
                <li><a href="UserManagement.php" style="color: white; text-decoration: none;">User Management</a></li>
                <li><a href="Employees.php" style="color: white; text-decoration: none;">Employees</a></li>
                <li><a href="AuditLogs.php" style="color: white; text-decoration: none;">Audit Logs</a></li>
            </ul>
        </li>
        <li>
            <a href="Reports.php" style="text-decoration: none; color: inherit;">
                <i class="fas fa-file-invoice-dollar"></i><span> Reports</span>
            </a>
        </li>
    </ul>
</div>

<div class="main-content">
    <div class="header-container">
        <button class="menu-btn" id="menuToggleBtn"><i class="fas fa-bars"></i></button>
        <h1>Inventory Dashboard</h1>
    </div>

    <div class="controls-container">
        <div class="search-container">
            <i class="fa fa-search"></i>
            <input type="text" class="form-control" placeholder="Search...">
        </div>
        <button type="button" class="btn btn-dark mr-2" id="newProductBtn">New <i class="fa fa-plus"></i></button>
        <form name="filterForm" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" class="d-flex align-items-center">
            <select class="btn btn-outline-secondary mr-2" name="orderBy" onchange="this.form.submit();">
                <option value="">Order By</option>
                <option value="name-asc" <?php if ($orderBy == 'name-asc') echo 'selected'; ?>>Ascending (A → Z)</option>
                <option value="name-desc" <?php if ($orderBy == 'name-desc') echo 'selected'; ?>>Descending (Z → A)</option>
                <option value="price-asc" <?php if ($orderBy == 'price-asc') echo 'selected'; ?>>Low Price (Ascending)</option>
                <option value="price-desc" <?php if ($orderBy == 'price-desc') echo 'selected'; ?>>High Price (Descending)</option>
                <option value="newest" <?php if ($orderBy == 'newest') echo 'selected'; ?>>Newest</option>
                <option value="oldest" <?php if ($orderBy == 'oldest') echo 'selected'; ?>>Oldest</option>
                <option value="best-seller" <?php if ($orderBy == 'best-seller') echo 'selected'; ?>>Best Seller</option>
                <option value="active" <?php if ($orderBy == 'active') echo 'selected'; ?>>Active</option>
                <option value="inactive" <?php if ($orderBy == 'inactive') echo 'selected'; ?>>Inactive</option>
            </select>
            <select class="btn btn-outline-secondary" name="filterBy" onchange="this.form.submit();">
                <option value="">Filtered By</option>
                <option value="product-type" <?php if ($filterBy == 'product-type') echo 'selected'; ?>>Product Type</option>
                <option value="product-supplier" <?php if ($filterBy == 'product-supplier') echo 'selected'; ?>>Product Supplier</option>
                <option value="price-below-1000" <?php if ($filterBy == 'price-below-1000') echo 'selected'; ?>>Below ₱1,000</option>
                <option value="price-1000-5000" <?php if ($filterBy == 'price-1000-5000') echo 'selected'; ?>>₱1,000 - ₱5,000</option>
                <option value="price-5000-10000" <?php if ($filterBy == 'price-5000-10000') echo 'selected'; ?>>₱5,000 - ₱10,000</option>
                <option value="price-above-10000" <?php if ($filterBy == 'price-above-10000') echo 'selected'; ?>>Above ₱10,000</option>
                <option value="in-stock" <?php if ($filterBy == 'in-stock') echo 'selected'; ?>>In-Stock</option>
                <option value="out-of-stock" <?php if ($filterBy == 'out-of-stock') echo 'selected'; ?>>Out of Stock</option>
            </select>
        </form>
    </div>

    <div class="table-container">
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>Prod ID</th>
                    <th>Product</th>
                    <th>Type Name</th>
                    <th>Supplier Name</th>
                    <th>Price</th>
                    <th>Stock</th>
                    <th>Status</th>
                    <th>Created By</th>
                    <th>Created Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if (isset($error)): ?>
                    <tr><td colspan="10" class="text-center text-danger"><?= htmlspecialchars($error) ?></td></tr>
                <?php elseif (!empty($products)): ?>
                    <?php foreach ($products as $product): ?>
                        <tr>
                            <td><?= htmlspecialchars($product['id']) ?></td>
                            <td><?= htmlspecialchars($product['product_name']) ?></td>
                            <td><?= htmlspecialchars($product['product_type']) ?></td>
                            <td><?= htmlspecialchars($product['supplier_name']) ?></td>
                            <td>₱<?= number_format($product['price'], 2) ?></td>
                            <td><?= htmlspecialchars($product['stock']) ?></td>
                            <td><?= htmlspecialchars($product['status']) ?></td>
                            <td><?= htmlspecialchars($product['created_by'] ?? 'Unknown') ?></td>
                            <td><?= htmlspecialchars($product['created_at'] ?? 'N/A') ?></td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <button class="btn btn-warning btn-sm me-1" data-product-id="<?php echo $product['id']; ?>" data-toggle="modal" data-target="#editProductModal">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-info btn-sm me-1" data-toggle="modal" data-target="#scannerModal">
                                        <i class="fas fa-barcode"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="10" class="text-center">No products found</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- New Product Form -->
    <div id="newProductForm" style="display: none; position: absolute; top: 50%; left: 55%; transform: translate(-50%, -50%); background: lightblue; padding: 40px; box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2); z-index: 1000; width: 1000px; height: 500px;">
        <form method="POST" enctype="multipart/form-data">
            <button id="closeFormBtn" style="position: absolute; top: 0px; right: 10px; background: none; border: none; font-size: 40px; cursor: pointer;">×</button>
            <h2 class="text-center mb-4">Add New Product</h2>
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
                <input type="number" class="form-control" id="price" name="price" placeholder="Enter price" style="width: 350px; height: 50px;" step="0.01" min="0" required>
            </div>
            <div class="form-group d-flex align-items-center mb-3">
                <label for="stock" class="mr-3" style="width: 150px;">Stock</label>
                <input type="number" class="form-control" id="stock" name="stock" placeholder="Enter stock" style="width: 350px; height: 50px;" min="0" required>
            </div>
            <div class="form-group d-flex align-items-center mb-3">
                <label for="status" class="mr-3" style="width: 150px;">Status</label>
                <select class="form-control" id="status" name="status" style="width: 350px; height: 50px;" required>
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>
            <div id="imageContainer" style="position: absolute; top: 120px; right: 110px; width: 200px; height: 200px; border: 2px solid rgba(0, 0, 0, 0.7); display: flex; justify-content: center; align-items: center; overflow: hidden; border-radius: 8px;">
                <img id="previewImage" src="#" alt="Image Preview" style="display: none; width: 100%; height: 100%; object-fit: cover; cursor: pointer;">
                <input type="file" name="productImage" id="productImage" accept="image/*" style="display: none;">
                <label for="productImage" id="uploadLabel" style="cursor: pointer; background-color: #6c757d; color: white; padding: 10px 15px; border-radius: 5px; font-size: 14px; width: 90%; text-align: center;">
                    Upload Image
                </label>
            </div>
            <button type="submit" class="btn btn-primary" style="position: absolute; top: 400px; left: 800px;">Submit</button>
            <label for="productImage" class="mb-2" style="position: absolute; top: 80px; right: 180px; font-size: 25px;">Edit Image</label>
        </form>
    </div>

    <!-- Edit Product Modal -->
    <div class="modal fade" id="editProductModal" tabindex="-1" role="dialog" aria-labelledby="editProductModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editProductModalLabel">Edit Product</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="editProductForm">
                        <input type="hidden" id="editProductId" name="product_id">
                        <div class="form-group">
                            <label for="editProductName">Product Name</label>
                            <input type="text" class="form-control" id="editProductName" name="product_name" required>
                        </div>
                        <div class="form-group">
                            <label for="editProductType">Product Type</label>
                            <input type="text" class="form-control" id="editProductType" name="product_type" required>
                        </div>
                        <div class="form-group">
                            <label for="editSupplierName">Supplier Name</label>
                            <input type="text" class="form-control" id="editSupplierName" name="supplier_name" required>
                        </div>
                        <div class="form-group">
                            <label for="editPrice">Price</label>
                            <input type="number" class="form-control" id="editPrice" name="price" step="0.01" min="0" required>
                        </div>
                        <div class="form-group">
                            <label for="editStock">Stock</label>
                            <input type="number" class="form-control" id="editStock" name="stock" min="0" required>
                        </div>
                        <div class="form-group">
                            <label for="editStatus">Status</label>
                            <select class="form-control" id="editStatus" name="status" required>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="saveEditButton">Save changes</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Barcode Scanner Modal -->
    <div class="modal fade" id="scannerModal" tabindex="-1" role="dialog" aria-labelledby="scannerModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="scannerModalLabel">Scan Product Barcode</h5>
                </div>
                <div class="modal-body">
                    <video id="interactive" style="width: 100%; height: auto;"></video>
                    <button id="startButton" class="btn btn-primary mt-2">Start Camera</button>
                    <button id="stopButton" class="btn btn-secondary mt-2" style="display: none;">Stop Camera</button>
                    <p id="result">Scan a barcode to see results here.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Dependencies -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="https://unpkg.com/@zxing/library@latest/umd/index.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Sidebar toggle functionality
    const menuToggleBtn = document.getElementById('menuToggleBtn');
    const sidebar = document.querySelector('.left-sidebar');

    if (menuToggleBtn && sidebar) {
        menuToggleBtn.addEventListener('click', function(event) {
            sidebar.classList.toggle('active');
            event.stopPropagation();
        });

        document.addEventListener('click', function(event) {
            if (sidebar.classList.contains('active') && 
                !sidebar.contains(event.target) && 
                !menuToggleBtn.contains(event.target)) {
                sidebar.classList.remove('active');
            }
        });

        sidebar.addEventListener('click', function(event) {
            event.stopPropagation();
        });
    } else {
        console.error('Sidebar or menu button not found');
    }

    // Dropdown toggle
    var dropdowns = document.querySelectorAll('.dropdown');
    dropdowns.forEach(function(dropdown) {
        var toggleBtn = dropdown.querySelector('.toggle-btn');
        toggleBtn.addEventListener('click', function() {
            dropdown.classList.toggle('active');
        });
    });

    // New Product Form handling
    document.getElementById('closeFormBtn').addEventListener('click', function() {
        document.getElementById('newProductForm').style.display = 'none';
    });
    document.getElementById('newProductBtn').addEventListener('click', function() {
        var form = document.getElementById('newProductForm');
        form.style.display = form.style.display === 'none' ? 'block' : 'none';
    });
    const productImageInput = document.getElementById('productImage');
    const previewImage = document.getElementById('previewImage');
    const uploadLabel = document.getElementById('uploadLabel');
    productImageInput.addEventListener('change', function() {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function() {
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

    // Edit Product Modal
    $('#editProductModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var productId = button.data('product-id');
        $.get('Inventory.php', { product_id: productId }, function(data) {
            if (data.error) {
                alert(data.error);
                $('#editProductModal').modal('hide');
            } else {
                $('#editProductId').val(data.id);
                $('#editProductName').val(data.product_name);
                $('#editProductType').val(data.product_type);
                $('#editSupplierName').val(data.supplier_name);
                $('#editPrice').val(data.price);
                $('#editStock').val(data.stock);
                $('#editStatus').val(data.status);
            }
        }, 'json');
    });

    document.getElementById('saveEditButton').addEventListener('click', function() {
        var form = document.getElementById('editProductForm');
        var data = new FormData(form);
        fetch('Inventory.php', {
            method: 'POST',
            body: data
        })
        .then(response => response.text())
        .then(data => {
            if (data === 'success') {
                alert('Product updated successfully');
                $('#editProductModal').modal('hide');
                location.reload();
            } else {
                alert('Error updating product: ' + data);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error updating product');
        });
    });

    // Barcode Scanner
    const codeReader = new ZXing.BrowserMultiFormatReader();
    const videoElement = document.getElementById('interactive');
    let scanning = false;

    $('#scannerModal').on('shown.bs.modal', function () {
        document.getElementById('startButton').style.display = 'block';
        document.getElementById('stopButton').style.display = 'none';
        document.getElementById('result').textContent = 'Scan a barcode to see results here.';
    });

    $('#scannerModal').on('hidden.bs.modal', function () {
        if (scanning) {
            codeReader.reset();
            scanning = false;
            document.getElementById('startButton').style.display = 'block';
            document.getElementById('stopButton').style.display = 'none';
        }
    });

    document.getElementById('startButton').addEventListener('click', () => {
        scanning = true;
        document.getElementById('startButton').style.display = 'none';
        document.getElementById('stopButton').style.display = 'block';

        codeReader.decodeFromVideoDevice(null, 'interactive', (result, err) => {
            if (result) {
                const barcode = result.text;
                fetch('Inventory.php?barcode=' + encodeURIComponent(barcode))
                    .then(response => response.json())
                    .then(data => {
                        if (data.error) {
                            alert('Barcode: ' + barcode + '\nNot in database');
                        } else {
                            alert('Barcode: ' + barcode + '\nProduct: ' + data.product_name + '\nCreated By: ' + data.created_by + '\nCreated Date: ' + data.created_date);
                        }
                        codeReader.reset();
                        scanning = false;
                        $('#scannerModal').modal('hide');
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Error fetching product for barcode: ' + barcode);
                    });
            }
            if (err && !(err instanceof ZXing.NotFoundException)) {
                console.error(err);
                document.getElementById('result').textContent = 'Error scanning barcode';
            }
        }).catch(err => {
            console.error(err);
            document.getElementById('result').textContent = 'Error starting camera';
        });
    });

    document.getElementById('stopButton').addEventListener('click', () => {
        codeReader.reset();
        scanning = false;
        document.getElementById('startButton').style.display = 'block';
        document.getElementById('stopButton').style.display = 'none';
        document.getElementById('result').textContent = 'Scan a barcode to see results here.';
    });
});
</script>

</body>
</html>