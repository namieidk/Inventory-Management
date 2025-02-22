<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Supplier Order</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
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
    <div class="sidebar">
        <h3>Le Parisien</h3>
        <nav>
            <ul class="nav flex-column">
                <li class="nav-item"><a href="#" class="nav-link text-white">Home</a></li>
                <li class="nav-item"><a href="#" class="nav-link text-white">Inventory</a></li>
                <li class="nav-item">
                    <a href="#" class="nav-link text-white">Retailer</a>
                    <ul class="nav flex-column ms-3">
                        <li class="nav-item"><a href="#" class="nav-link text-white">Supplier</a></li>
                        <li class="nav-item"><a href="#" class="nav-link text-white">Supplier Order</a></li>
                    </ul>
                </li>
                <li class="nav-item"><a href="#" class="nav-link text-white">Expenses</a></li>
                <li class="nav-item"><a href="#" class="nav-link text-white">Bills</a></li>
                <li class="nav-item">
                    <a href="#" class="nav-link text-white">Sales</a>
                    <ul class="nav flex-column ms-3">
                        <li class="nav-item"><a href="#" class="nav-link text-white">Reports</a></li>
                    </ul>
                </li>
                <li class="nav-item"><a href="#" class="nav-link text-white">Employees</a></li>
            </ul>
        </nav>
        <a href="#" class="text-white">Log Out</a>
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
                    <label class="form-label">Documents</label>
                    <input type="file" class="form-control">
                </div>
                <div class="col-md-6">
                    <div class="total-section">
                        <p><strong>Sub Total:</strong> ₱110,000</p>
                        <p><strong>Discount:</strong> ₱5,000</p>
                        <h5><strong>Total:</strong> ₱105,000</h5>
                    </div>
                </div>
            </div>
            <div class="mt-3">
                <button type="submit" name="save" class="btn btn-primary">Save</button>
                <button type="button" class="btn btn-secondary">Cancel</button>
            </div>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
