<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="../statics/css/bootstrap.min.css" rel="stylesheet">
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
            background: #435299;
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
            margin-left: 270px;
            padding: 20px;
            width: calc(100% - 270px);
            transition: width 0.3s;
        }

        .search-box {
            display: flex;
            align-items: center;
            border: 1px solid #ccc;
            border-radius: 5px;
            overflow: hidden;
            width: 230px;
            height: 35px;
        }

        .search-box input {
            border: none;
            padding: 5px 10px;
            outline: none;
            width: 100%;
        }

        .search-box i {
            padding: 10px;
            color: #333;
            background: #f1f1f1;
        }
    </style>
</head>

<body>
    <div class="left-sidebar">
        <img src="../images/Logo.jpg" alt="Le Parisien" class="logo">
        <ul class="menu">
            <li><a href="dashboard.php" style="color: inherit; text-decoration: none;"><i class="fa fa-home"></i><span> Home</span></a></li>
            <li><i class="fa fa-box"></i><span> Inventory</span></li>

            <li class="dropdown">
                <i class="fa fa-store"></i><span> Retailer</span><i class="fa fa-chevron-down toggle-btn"></i>
                <ul class="submenu">
                    <li>Supplier</li>
                    <li>Supplier Order</li>
                    <li>Expenses</li>
                    <li>Bills</li>
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

            <li><i class="fa fa-file-alt"></i><span> Reports</span></li>
            <li><i class="fa fa-users"></i><span> Employees</span></li>
        </ul>
    </div>

    <div class="container-fluid p-4 main-content">
        <h3>Inventory</h3>
        <div class="d-flex justify-content-between mb-3">
            <div class="search-box" style="width: 480px;">
            <i class="fa fa-search"></i>
            <input type="text" placeholder="Search Product Name">
            </div>
            <div class="d-flex">
            <select class="form-select me-2" style="width: auto;">
                <option value="Order By" disabled selected>Order By</option>
                <option value="price">Ascending (A → Z)</option>
                <option value="stock">Descending (Z → A)</option>
                <option value="name">Low Price (Ascending)</option>
                <option value="name">High Price (Descending)</option>
                <option value="name">Newest</option>
                <option value="name">Oldest</option>
                <option value="name">Best Seller</option>
            </select>
            <select class="form-select me-2" style="width: auto;">
                <option value="Filter By" disabled selected>Filter By</option>
                <option value="type">Product Type</option>
                <option value="supplier">Product Supplier</option>
                <option value="supplier">Below ₱1,000</option>
                <option value="supplier">₱1,000 - ₱5,000</option>
                <option value="supplier">₱5,000 - ₱10,000</option>
                <option value="supplier">Above ₱10,000</option>
                <option value="supplier">In-Stock</option>
                <option value="supplier">Out of Stock</option>
            </select>
            </div>
        </div>

        <table class="table table-striped">
            <thead>
                <tr>
                    <th>No ID</th>
                    <th>Product</th>
                    <th>Type Name</th>
                    <th>Supplier Name</th>
                    <th>Price</th>
                    <th>Stock</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <!-- Add your table rows here -->
                <tr>
                    <td colspan="7" class="text-center">No products added yet. <a href="add_product.php">Add new product</a></td>
                </tr>
            </tbody>
        </table>
    </div>
</body>

</html>
