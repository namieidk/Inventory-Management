<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory Dashboard</title>
    <link href="../statics/bootstrap css/bootstrap.min.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/31e24a5c2a.js" crossorigin="anonymous"></script>
    <style>
        body {
            display: flex;
            font-family: Arial, sans-serif;
            background: #f4f4f4;
            margin: 0;
        }

        .left-sidebar {
            width: 250px;
            background: #343F79;
            color: white;
            height: 100vh;
            padding: 20px;
            position: fixed;
            display: flex;
            flex-direction: column;
            align-items: center;
            overflow-y: auto;
        }

        .logo {
            width: 138px;
            height: 138px;
            object-fit: cover;
            border-radius: 50%;
            margin-bottom: 20px;
        }

        .menu {
            list-style: none;
            padding: 0;
            width: 100%;
        }

        .menu li {
            padding: 15px;
            cursor: pointer;
            display: flex;
            align-items: center;
            position: relative;
            transition: background-color 0.3s ease;
        }

        .menu li i {
            margin-right: 8px;
        }

        .menu li:hover {
            background: #3e4a8c;
        }
        

        .dropdown {
            position: relative;
            width: 100%;
        }

        .toggle-btn {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
        }

        .submenu {
            display: none;
            list-style: none;
            padding-left: 0;
            margin-top: 5px;
        }

        .submenu li {
            padding: 10px 15px;
            background: #435299;
            border-left: 3px solid #1abc9c;
            margin-left: 10px; 
            transition: background-color 0.3s ease;
        }

        .submenu li:hover {
            background-color: #5264b3;
            color: white;
        }

        .dropdown.active .submenu {
            display: block;
        }

        .main-content {
            margin-left: 250px; 
            padding: 20px;
            width: calc(100% - 250px); 
            transition: width 0.3s;
        }

        .main-content.right-sidebar-open {
            width: calc(100% - 510px);
        }

        .cards {
            display: flex;
            gap: 20px;
        }

        .card {
            background: white;
            padding: 20px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
            flex: 1;
            text-align: center;
        }

        .right-card {
            width: 200px;
            height: 105px;
            background: white;
            padding: 20px;
            box-shadow: 0 2px 5px #FFFFFF;
            text-align: center;
            margin-bottom: 20px;
        }

        .graph-placeholder {
            background: white;
            height: 200px;
            display: flex;
            justify-content: center;
            align-items: center;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
            margin-top: 20px;
        }

        .inventory-summary {
            background: white;
            padding: 20px;
            margin-top: 20px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        }

        table {
            width: 100%;
            background: white;
            margin-top: 20px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
            border-collapse: collapse;
        }

        th,
        td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background: #34495e;
            color: white;
        }

        .search-container {
            position: relative;
            width: 50%;
        }

        .search-container input {
            width: 100%;
            padding-left: 30px;
        }

        .search-container .fa-search {
            position: absolute;
            left: 10px;
            top: 50%;
            transform: translateY(-50%);
            color: #aaa;
        }
    </style>
</head>
<body>
<div class="left-sidebar">
        <img src="../images/Logo.jpg" alt="Le Parisien" class="logo">
        <ul class="menu">
            <li><i class="fa fa-home"></i><span> Home</span></li>
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
        <h1>Invoice</h1>
        <div class="d-flex justify-content-between mb-3">
            <div class="search-container">
            <i class="fa fa-search"></i>
            <input type="text" class="form-control" placeholder="Search...">
            </div>
            <div>
                <button class="btn btn-dark" id="newProductBtn">New <i class="fa fa-plus"></i></button>
                <script>
                    document.getElementById('newProductBtn').addEventListener('click', function() {
                        window.location.href = 'NewInvoice.php';
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
                </select>
                <select class="btn btn-outline-secondary">
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
                    <th>Invoice#</th>
                    <th>Order#</th>
                    <th>Customer Name</th>
                    <th>Status</th>
                    <th>Due Date</th>
                    <th>Amount</th>
                    <th>Balance Due</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td colspan="8" class="text-center text-muted">No products available. Input new product.</td>
                </tr>
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
</script>
</body>
</html>
