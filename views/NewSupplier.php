<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Supplier</title>
    <link href="../statics/bootstrap css/bootstrap.min.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/31e24a5c2a.js" crossorigin="anonymous"></script>
    <style>
        body {
            display: flex;
            min-height: 100vh;
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
    </style>
</head>
<body>
<div class="left-sidebar">
    <img src="../images/Logo.jpg" alt="Le Parisien" class="logo">
    <ul class="menu">
        <li><i class="fa fa-home"></i><span> Home</span></li>
        <li><i class="fa fa-box"></i><span><a href="Inventory.php" style="color: white; text-decoration: none;"> Inventory</a></span></li>

        <li class="dropdown">
            <i class="fa fa-store"></i><span> Retailer</span><i class="fa fa-chevron-down toggle-btn"></i>
            <ul class="submenu">
                <li><a href="supplier.php" style="color: white; text-decoration: none;">Supplier</a></li>
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

<div class="main-content">
    <h1>New Supplier</h1>
    <form action="process_supplier.php" method="POST">
        <div class="mb-3 d-flex">
            <select class="form-select me-2" name="salutation" style="width: 100px; height: 45px;">
            <option>Mr.</option>
            <option>Ms.</option>
            <option>Mrs.</option>
            </select>
            <input type="text" class="form-control me-2" name="first_name" placeholder="First Name" style="width: 300px; height: 45px;">
            <input type="text" class="form-control" name="last_name" placeholder="Last Name" style="width: 300px; height: 45px;">
        </div>

        <div class="mb-3">
            <input type="text" class="form-control" name="company_name" placeholder="Company Name" style="width: 720px; height: 45px;">
        </div>

        <div class="mb-3">
            <input type="email" class="form-control" name="email" placeholder="Email Address" style="width: 720px; height: 45px;">
        </div>

        <div class="mb-3">
            <input type="tel" class="form-control" name="phone" placeholder="Phone" style="width: 720px; height: 45px;">
        </div>

        <ul class="nav nav-tabs" id="supplierTabs">
            <li class="nav-item">
                <a class="nav-link active" data-bs-toggle="tab" href="#details">Other Details</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#address">Address</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#remarks">Remarks</a>
            </li>
        </ul>

        <div class="tab-content">
            <div class="tab-pane fade show active" id="details">
                <div class="mb-3">
                    <input type="text" class="form-control" name="company_id" placeholder="Company ID" style="width: 700px; height: 45px;">
                </div>
                <div class="mb-3">
                    <input type="text" class="form-control" name="tax_rate" placeholder="Tax Rate" style="width: 700px; height: 45px;">
                </div>
                <div class="mb-3">
                    <input type="text" class="form-control" name="payment_terms" placeholder="Payment Terms" style="width: 700px; height: 45px;">
                </div>
                <div class="mb-3">
                    <input type="file" class="form-control" name="documents" style="width: 700px; height: 45px;">
                </div>
            </div>

            <div class="tab-pane fade" id="address">
                <div class="row">
                    <div class="col-md-6">
                        <h4>Billing Address</h4>
                        <input type="text" class="form-control mb-3" placeholder="Country/Region" required>
                        <input type="text" class="form-control mb-3" placeholder="Address 1" required>
                        <input type="text" class="form-control mb-3" placeholder="Address 2" required>
                        <input type="text" class="form-control mb-3" placeholder="Address 3">
                        <input type="text" class="form-control mb-3" placeholder="City" required>
                        <input type="text" class="form-control mb-3" placeholder="Zip Code" required>
                        <input type="text" class="form-control mb-3" placeholder="Phone" required>
                    </div>
                    <div class="col-md-6">
                        <h4>Shipping Address <a href="#" onclick="copyBillingAddress()" style="color: #007bff; font-size: 0.5em;">(📋 Copy Billing Address)</a></h4>
                        <input type="text" class="form-control mb-3" id="shipCountry" placeholder="Country/Region" required>
                        <input type="text" class="form-control mb-3" id="shipAddress1" placeholder="Address 1" required>
                        <input type="text" class="form-control mb-3" id="shipAddress2" placeholder="Address 2" required>
                        <input type="text" class="form-control mb-3" id="shipAddress3" placeholder="Address 3">
                        <input type="text" class="form-control mb-3" id="shipCity" placeholder="City" required>
                        <input type="text" class="form-control mb-3" id="shipZip" placeholder="Zip Code" required>
                        <input type="text" class="form-control mb-3" id="shipPhone" placeholder="Phone" required>
                    </div>
                </div>
            </div>

            <div class="tab-pane fade" id="remarks">
                <div class="mb-3">
                    <textarea class="form-control" name="remarks" placeholder="Remarks(For Internal Use Only)"></textarea>
                </div>
            </div>
        </div>

        <div class="mt-3">
            <button type="submit" class="btn btn-save">Save</button>
            <button type="reset" class="btn btn-secondary">Cancel</button>
        </div>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.querySelectorAll('.toggle-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            const dropdown = btn.closest('.dropdown');
            dropdown.classList.toggle('active');
        });
    });

    function copyBillingAddress() {
        document.getElementById('shipCountry').value = document.querySelector('[placeholder="Country/Region"]').value;
        document.getElementById('shipAddress1').value = document.querySelector('[placeholder="Address 1"]').value;
        document.getElementById('shipAddress2').value = document.querySelector('[placeholder="Address 2"]').value;
        document.getElementById('shipAddress3').value = document.querySelector('[placeholder="Address 3"]').value;
        document.getElementById('shipCity').value = document.querySelector('[placeholder="City"]').value;
        document.getElementById('shipZip').value = document.querySelector('[placeholder="Zip Code"]').value;
        document.getElementById('shipPhone').value = document.querySelector('[placeholder="Phone"]').value;
    }
</script>
</html>
