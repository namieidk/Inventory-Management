<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Supplier Order</title>
    <link href="../statics/bootstrap css/bootstrap.min.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/31e24a5c2a.js" crossorigin="anonymous"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
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

        .sidebar {
            width: 250px;
            background-color: #343F79;
            color: #fff;
            padding: 20px;
        }
        .sidebar a {
            color: #fff;
            text-decoration: none;
            display: block;
            margin: 10px 0;
        }
        .main-content {
            flex-grow: 1;
            padding: 40px;
        }
        .tab-content {
            margin-top: 20px;
        }
        .btn-save {
            background-color: #343F79;
            color: #fff;
        }
        .sidebar {
            background-color: #343F79;
            width: 250px;
            height: 100vh;
            position: fixed;
            padding: 20px;
            color: white;
        }
        .container {
            margin-left: 270px;
            padding: 20px;
        }
        .item-table img {
            width: 40px;
            height: 40px;
            border-radius: 5px;
        }
        .total-section {
            border: 1px solid #ccc;
            padding: 15px;
            border-radius: 10px;
            background-color: #f8f9fa;
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
    <div class="container">
        <h1>New Supplier Order</h1>
        <form method="post">
            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Supplier Name</label>
                    <input type="text" class="form-control" name="supplier_name" style="width: 400px; height: 40px;">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Date</label>
                    <input type="date" class="form-control" name="date" style="width: 400px; height: 40px;">
                </div>
                <div class="col-md-6 mt-3">
                    <label class="form-label">TIN</label>
                    <input type="text" class="form-control" name="tin" style="width: 400px; height: 40px;">
                </div>
                <div class="col-md-6 mt-3">
                    <label class="form-label">Delivery Date</label>
                    <input type="date" class="form-control" name="delivery_date" style="width: 400px; height: 40px;">
                </div>
                <div class="col-md-6 mt-3">
                    <label class="form-label">Payment Terms</label>
                    <input type="text" class="form-control" name="payment_terms" style="width: 400px; height: 40px;">
                </div>
            </div>
            <h4>Item Table</h4>
            <table class="table item-table">
                <thead>
                    <tr>
                        <th>Order Details</th>
                        <th>Account</th>
                        <th>Quantity</th>
                        <th>Rate</th>
                        <th>Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Table body will be dynamically added here when an order is placed -->
                </tbody>
            </table>
            <button type="button" class="btn btn-success mt-3">Add Order</button>
            <div class="row">
                <div class="col-md-6">
                    <label class="form-label" style="margin-top: 50px;">Documents</label>
                    <input type="file" class="form-control" style="width: 400px; ">
                </div>
                <div class="col-md-6" style="margin-left: 600px; margin-top: -60px;">
                    <div class="total-section" style="width: 500px; height: 200px;">
                        <p class="m-2" style="margin-top: 10px;"><strong>Sub Total:</strong></p>
                        <p class="m-2" style="margin-top: 10px;"><strong>Discount:</strong></p>
                        <h5 class="m-2" style="margin-top: 10px;"><strong>Total:</strong></h5>
                    </div>
                </div>
            </div>
            <div class="mt-3">
                <button type="submit" name="save" class="btn btn-primary">Save</button>
                <button type="button" class="btn btn-secondary">Cancel</button>
            </div>
        </form>
    </div>
    <script>
    document.querySelectorAll('.toggle-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            const dropdown = btn.closest('.dropdown');
            dropdown.classList.toggle('active');
        });
    });
    </script>
</body>
</html>
