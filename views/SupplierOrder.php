<?php
include '../database/database.php';
session_start();

// Handle form submission for editing
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id'])) {
    try {
        $order_id = $_POST['order_id'];
        $contact_person = $_POST['contact_person'];
        $order_date = $_POST['order_date'];
        $delivery_date = $_POST['delivery_date'];
        $total = $_POST['total'];
        $quantity = $_POST['quantity'];
        $status = $_POST['status'];

        $stmt = $conn->prepare("
            UPDATE SupplierOrders 
            SET SupplierName = :contact_person,
                OrderDate = :order_date,
                DeliveryDate = :delivery_date,
                Total = :total,
                Status = :status
            WHERE OrderID = :order_id
        ");
        
        $stmt->execute([
            ':order_id' => $order_id,
            ':contact_person' => $contact_person,
            ':order_date' => $order_date,
            ':delivery_date' => $delivery_date,
            ':total' => $total,
            ':status' => $status
        ]);

        $stmt = $conn->prepare("
            UPDATE SupplierOrderItems 
            SET Quantity = :quantity
            WHERE OrderID = :order_id
        ");
        $stmt->execute([
            ':quantity' => $quantity,
            ':order_id' => $order_id
        ]);
    } catch (PDOException $e) {
        $error = "Error updating order: " . $e->getMessage();
    }
}

// Handle AJAX request for order details
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['get_details']) && isset($_GET['order_id'])) {
    try {
        $order_id = $_GET['order_id'];
        $stmt = $conn->prepare("
            SELECT 
                ItemID,
                ProductName,
                Quantity,
                Rate,
                Amount
            FROM SupplierOrderItems
            WHERE OrderID = :order_id
        ");
        $stmt->execute([':order_id' => $order_id]);
        $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        header('Content-Type: application/json');
        echo json_encode(['success' => true, 'items' => $items]);
        exit();
    } catch (PDOException $e) {
        header('Content-Type: application/json');
        header('HTTP/1.1 500 Internal Server Error');
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        exit();
    }
}

// Get sorting and filtering parameters
$order_by = $_GET['order_by'] ?? 'OrderDate';
$order_dir = $_GET['order_dir'] ?? 'DESC';
$filter = $_GET['filter'] ?? '';

// Build the base query for orders
$query = "
    SELECT 
        so.OrderID,
        so.SupplierName AS ContactPerson,
        so.OrderDate,
        so.DeliveryDate,
        so.Total,
        so.SubTotal,
        so.Discount,
        SUM(soi.Quantity) AS TotalQuantity,
        so.Status
    FROM SupplierOrders so
    LEFT JOIN SupplierOrderItems soi ON so.OrderID = soi.OrderID
";

// Add WHERE clause for filtering
$where_clauses = [];
$params = [];
if ($filter) {
    switch ($filter) {
        case 'Below ₱1,000':
            $where_clauses[] = "so.Total < 1000";
            break;
        case '₱1,000 - ₱5,000':
            $where_clauses[] = "so.Total BETWEEN 1000 AND 5000";
            break;
        case '₱5,000 - ₱10,000':
            $where_clauses[] = "so.Total BETWEEN 5000 AND 10000";
            break;
        case 'Above ₱10,000':
            $where_clauses[] = "so.Total > 10000";
            break;
    }
}

if (!empty($where_clauses)) {
    $query .= " WHERE " . implode(" AND ", $where_clauses);
}

// Add GROUP BY and ORDER BY
$query .= "
    GROUP BY so.OrderID, so.SupplierName, so.OrderDate, so.DeliveryDate, so.Total, so.SubTotal, so.Discount, so.Status
";

$valid_order_columns = ['OrderID', 'SupplierName', 'OrderDate', 'Total', 'Status'];
$order_column = in_array($order_by, $valid_order_columns) ? $order_by : 'OrderDate';
$order_dir = strtoupper($order_dir) === 'ASC' ? 'ASC' : 'DESC';
$query .= " ORDER BY so.$order_column $order_dir";

try {
    $stmt = $conn->prepare($query);
    $stmt->execute($params);
    $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error = "Database error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supplier Order Dashboard</title>
    <link href="../statics/bootstrap css/bootstrap.min.css" rel="stylesheet">
    <link href="../statics/CustomerOrder.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/31e24a5c2a.js" crossorigin="anonymous"></script>
    <style>
        .left-sidebar {
            position: fixed;
            top: 0;
            left: -250px;
            width: 250px;
            height: 100%;
            background-color: #343F79;
            transition: left 0.3s ease;
            z-index: 1000;
        }
        .left-sidebar.active {
            left: 0;
        }
        .main-content {
            width: 100%;
            max-width: 100%;
            margin-left: auto;
            margin-right: 0;
            padding: 20px;
            box-sizing: border-box;
        }
        .menu-btn {
            font-size: 24px;
            background: none;
            border: none;
            cursor: pointer;
            margin-right: 10px;
        }
        .header-container {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }
        .search-container {
            position: relative;
            width: 300px;
            margin-left: 45px;
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
        .success-notification {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1050;
        }
        .order-link {
            cursor: pointer;
            color: #007bff;
            text-decoration: underline;
        }
        .order-link:hover {
            color: #0056b3;
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
                <li><a href="UserManagement.php" style="color: white; text-decoration: none;">User Management </a></li>
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
        <h1>Supplier Order</h1>
    </div>

    <?php if (isset($error)): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= htmlspecialchars($error) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="d-flex justify-content-between mb-3">
        <div class="search-container">
            <i class="fa fa-search"></i>
            <input type="text" class="form-control" placeholder="Search...">
        </div>
        <div>
            <button class="btn btn-dark" id="newProductBtn">New <i class="fa fa-plus"></i></button>
            <select class="btn btn-outline-secondary" id="orderBySelect">
                <option value="">Order By</option>
                <option value="SupplierName|ASC" <?= $order_by === 'SupplierName' && $order_dir === 'ASC' ? 'selected' : '' ?>>Ascending (A → Z)</option>
                <option value="SupplierName|DESC" <?= $order_by === 'SupplierName' && $order_dir === 'DESC' ? 'selected' : '' ?>>Descending (Z → A)</option>
                <option value="Total|ASC" <?= $order_by === 'Total' && $order_dir === 'ASC' ? 'selected' : '' ?>>Low Price (Ascending)</option>
                <option value="Total|DESC" <?= $order_by === 'Total' && $order_dir === 'DESC' ? 'selected' : '' ?>>High Price (Descending)</option>
                <option value="OrderDate|DESC" <?= $order_by === 'OrderDate' && $order_dir === 'DESC' ? 'selected' : '' ?>>Newest</option>
                <option value="OrderDate|ASC" <?= $order_by === 'OrderDate' && $order_dir === 'ASC' ? 'selected' : '' ?>>Oldest</option>
            </select>
            <select class="btn btn-outline-secondary" id="filterBySelect">
                <option value="">Filter By</option>
                <option value="Below ₱1,000" <?= $filter === 'Below ₱1,000' ? 'selected' : '' ?>>Below ₱1,000</option>
                <option value="₱1,000 - ₱5,000" <?= $filter === '₱1,000 - ₱5,000' ? 'selected' : '' ?>>₱1,000 - ₱5,000</option>
                <option value="₱5,000 - ₱10,000" <?= $filter === '₱5,000 - ₱10,000' ? 'selected' : '' ?>>₱5,000 - ₱10,000</option>
                <option value="Above ₱10,000" <?= $filter === 'Above ₱10,000' ? 'selected' : '' ?>>Above ₱10,000</option>
            </select>
        </div>
    </div>

    <table class="table table-striped table-hover" id="ordersTable">
        <thead>
            <tr>
                <th>Supplier ID</th>
                <th>Supplier Name</th>
                <th>Order#</th>
                <th>Quantity</th>
                <th>Total</th>
                <th>Status</th>
                <th>Delivery Date</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if (isset($error) && empty($orders)): ?>
                <tr><td colspan="8" class="text-center text-danger"><?= htmlspecialchars($error) ?></td></tr>
            <?php elseif (!empty($orders)): ?>
                <?php foreach ($orders as $order): ?>
                    <tr>
                        <td><?= htmlspecialchars($order['OrderID']) ?></td>
                        <td><?= htmlspecialchars($order['ContactPerson']) ?></td>
                        <td><span class="order-link" data-order-id="<?= htmlspecialchars($order['OrderID']) ?>"><?= htmlspecialchars($order['OrderID']) ?></span></td>
                        <td><?= htmlspecialchars($order['TotalQuantity']) ?></td>
                        <td>₱<?= number_format($order['Total'], 2) ?></td>
                        <td><?= htmlspecialchars($order['Status']) ?></td>
                        <td><?= htmlspecialchars($order['DeliveryDate']) ?></td>
                        <td>
                            <button class="btn btn-sm btn-primary edit-btn" 
                                    data-order-id="<?= htmlspecialchars($order['OrderID']) ?>"
                                    data-contact="<?= htmlspecialchars($order['ContactPerson']) ?>"
                                    data-date="<?= htmlspecialchars($order['OrderDate']) ?>"
                                    data-delivery="<?= htmlspecialchars($order['DeliveryDate']) ?>"
                                    data-total="<?= htmlspecialchars($order['Total']) ?>"
                                    data-quantity="<?= htmlspecialchars($order['TotalQuantity']) ?>"
                                    data-status="<?= htmlspecialchars($order['Status']) ?>"
                                    title="Edit Order">
                                <i class="fas fa-edit"></i>
                            </button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="8" class="text-center text-muted">No supplier orders available. Input new order.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>

    <!-- Edit Modal -->
    <div class="modal fade" id="editOrderModal" tabindex="-1" aria-labelledby="editOrderModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editOrderModalLabel">Edit Supplier Order</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editOrderForm" method="POST">
                    <div class="modal-body">
                        <input type="hidden" name="order_id" id="editOrderId">
                        <div class="mb-3">
                            <label for="editContactPerson" class="form-label">Supplier Name</label>
                            <input type="text" class="form-control" id="editContactPerson" name="contact_person" required>
                        </div>
                        <div class="mb-3">
                            <label for="editOrderDate" class="form-label">Order Date</label>
                            <input type="date" class="form-control" id="editOrderDate" name="order_date" required>
                        </div>
                        <div class="mb-3">
                            <label for="editDeliveryDate" class="form-label">Delivery Date</label>
                            <input type="date" class="form-control" id="editDeliveryDate" name="delivery_date" required>
                        </div>
                        <div class="mb-3">
                            <label for="editTotal" class="form-label">Total</label>
                            <input type="number" class="form-control" id="editTotal" name="total" step="0.01" required>
                        </div>
                        <div class="mb-3">
                            <label for="editQuantity" class="form-label">Quantity</label>
                            <input type="number" class="form-control" id="editQuantity" name="quantity" required>
                        </div>
                        <div class="mb-3">
                            <label for="editStatus" class="form-label">Status</label>
                            <select class="form-control" id="editStatus" name="status">
                                <option value="Pending">Pending</option>
                                <option value="Processing">Processing</option>
                                <option value="Shipped">Shipped</option>
                                <option value="Delivered">Delivered</option>
                                <option value="Cancelled">Cancelled</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Details Modal -->
    <div class="modal fade" id="orderDetailsModal" tabindex="-1" aria-labelledby="orderDetailsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="orderDetailsModalLabel">Supplier Order Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h6>Order ID: <span id="detailsOrderId"></span></h6>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Item ID</th>
                                <th>Product Name</th>
                                <th>Quantity</th>
                                <th>Rate</th>
                                <th>Amount</th>
                            </tr>
                        </thead>
                        <tbody id="orderItemsTableBody">
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var dropdowns = document.querySelectorAll('.dropdown');
    dropdowns.forEach(function(dropdown) {
        var toggleBtn = dropdown.querySelector('.toggle-btn');
        toggleBtn.addEventListener('click', function() {
            dropdown.classList.toggle('active');
        });
    });

    document.getElementById('newProductBtn').addEventListener('click', function() {
        window.location.href = 'NewSupplierOrder.php';
    });

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

    // Edit button functionality
    document.querySelectorAll('.edit-btn').forEach(button => {
        button.addEventListener('click', function() {
            const orderId = this.getAttribute('data-order-id');
            const contact = this.getAttribute('data-contact');
            const orderDate = this.getAttribute('data-date');
            const deliveryDate = this.getAttribute('data-delivery');
            const total = this.getAttribute('data-total');
            const quantity = this.getAttribute('data-quantity');
            const status = this.getAttribute('data-status');

            document.getElementById('editOrderId').value = orderId;
            document.getElementById('editContactPerson').value = contact;
            document.getElementById('editOrderDate').value = orderDate;
            document.getElementById('editDeliveryDate').value = deliveryDate;
            document.getElementById('editTotal').value = total;
            document.getElementById('editQuantity').value = quantity;
            document.getElementById('editStatus').value = status;

            const modal = new bootstrap.Modal(document.getElementById('editOrderModal'));
            modal.show();
        });
    });

    // Handle form submission with temporary success message
    document.getElementById('editOrderForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        fetch(window.location.href, {
            method: 'POST',
            body: new FormData(this)
        })
        .then(response => {
            if (response.ok) {
                const notification = document.createElement('div');
                notification.className = 'success-notification alert alert-success alert-dismissible fade show';
                notification.innerHTML = `
                    Order updated successfully
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                `;
                document.body.appendChild(notification);

                const modal = bootstrap.Modal.getInstance(document.getElementById('editOrderModal'));
                modal.hide();

                setTimeout(() => location.reload(), 1500);
                setTimeout(() => notification.remove(), 3000);
            } else {
                throw new Error('Update failed');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Failed to update order: ' + error.message);
        });
    });

    // Handle sorting and filtering
    const orderBySelect = document.getElementById('orderBySelect');
    const filterBySelect = document.getElementById('filterBySelect');

    function updateTable() {
        const orderValue = orderBySelect.value.split('|');
        const filterValue = filterBySelect.value;
        
        const params = new URLSearchParams(window.location.search);
        if (orderValue[0]) {
            params.set('order_by', orderValue[0]);
            params.set('order_dir', orderValue[1] || 'ASC');
        } else {
            params.delete('order_by');
            params.delete('order_dir');
        }
        if (filterValue) {
            params.set('filter', filterValue);
        } else {
            params.delete('filter');
        }

        const newUrl = window.location.pathname + (params.toString() ? '?' + params.toString() : '');
        window.history.pushState({}, '', newUrl);
        location.reload();
    }

    orderBySelect.addEventListener('change', updateTable);
    filterBySelect.addEventListener('change', updateTable);

    // Handle order number click for details
    document.querySelectorAll('.order-link').forEach(link => {
        link.addEventListener('click', function() {
            const orderId = this.getAttribute('data-order-id');
            document.getElementById('detailsOrderId').textContent = orderId;

            const urlParams = new URLSearchParams(window.location.search);
            urlParams.set('get_details', '1');
            urlParams.set('order_id', orderId);
            const fetchUrl = `${window.location.pathname}?${urlParams.toString()}`;

            fetch(fetchUrl)
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    if (!data.success) {
                        throw new Error(data.error || 'Failed to fetch order details');
                    }

                    const items = data.items;
                    const tbody = document.getElementById('orderItemsTableBody');
                    tbody.innerHTML = ''; // Clear existing rows

                    if (items.length === 0) {
                        tbody.innerHTML = '<tr><td colspan="5" class="text-center">No items found for this order</td></tr>';
                    } else {
                        items.forEach(item => {
                            const row = `
                                <tr>
                                    <td>${item.ItemID || 'N/A'}</td>
                                    <td>${item.ProductName || 'N/A'}</td>
                                    <td>${item.Quantity || 0}</td>
                                    <td>₱${item.Rate ? parseFloat(item.Rate).toFixed(2) : '0.00'}</td>
                                    <td>₱${item.Amount ? parseFloat(item.Amount).toFixed(2) : '0.00'}</td>
                                </tr>
                            `;
                            tbody.innerHTML += row;
                        });
                    }

                    const modal = new bootstrap.Modal(document.getElementById('orderDetailsModal'));
                    modal.show();
                })
                .catch(error => {
                    console.error('Fetch error:', error);
                    alert('Failed to load order details: ' + error.message);
                    const tbody = document.getElementById('orderItemsTableBody');
                    tbody.innerHTML = '<tr><td colspan="5" class="text-center">Error loading details: ' + error.message + '</td></tr>';
                    const modal = new bootstrap.Modal(document.getElementById('orderDetailsModal'));
                    modal.show();
                });
        });
    });
});
</script>
</body>
</html>