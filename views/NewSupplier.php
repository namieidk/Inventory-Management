<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Supplier</title>
    <link href="../statics/bootstrap css/bootstrap.min.css" rel="stylesheet">
    <link href="../statics/NewSupplier.css" rel="stylesheet">
    <script src="../statics/js/bootstrap.bundle.min.js"></script>
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
<div class="container mt-4">
        <!-- New Customer Form -->
        <h1>New Customer</h1>
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

            <!-- Navigation Buttons -->
            <ul class="nav nav-pills mb-3" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="details-tab" data-bs-toggle="pill" data-bs-target="#details" type="button" role="tab" aria-controls="details" aria-selected="true">Other Details</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="address-tab" data-bs-toggle="pill" data-bs-target="#address" type="button" role="tab" aria-controls="address" aria-selected="false">Address</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="remarks-tab" data-bs-toggle="pill" data-bs-target="#remarks" type="button" role="tab" aria-controls="remarks" aria-selected="false">Remarks</button>
                </li>
            </ul>

            <!-- Tab Content -->
            <div class="tab-content" id="myTabContent">
                <!-- Other Details Tab (Now Active by Default) -->
                <div class="tab-pane fade show active" id="details" role="tabpanel" aria-labelledby="details-tab">
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

                <!-- Address Tab -->
                <div class="tab-pane fade" id="address" role="tabpanel" aria-labelledby="address-tab">
                    <div class="row">
                        <div class="col-md-6">
                            <h4>Billing Address</h4>
                            <input type="text" class="form-control mb-3" name="billing_country" placeholder="Country/Region" required>
                            <input type="text" class="form-control mb-3" name="billing_address1" placeholder="Address 1" required>
                            <input type="text" class="form-control mb-3" name="billing_address2" placeholder="Address 2" required>
                            <input type="text" class="form-control mb-3" name="billing_address3" placeholder="Address 3">
                            <input type="text" class="form-control mb-3" name="billing_city" placeholder="City" required>
                            <input type="text" class="form-control mb-3" name="billing_zip" placeholder="Zip Code" required>
                            <input type="text" class="form-control mb-3" name="billing_phone" placeholder="Phone" required>
                        </div>
                        <div class="col-md-6">
                            <h4>Shipping Address <a href="#" onclick="copyBillingAddress()" style="color: #007bff; font-size: 0.5em;">(📋 Copy Billing Address)</a></h4>
                            <input type="text" class="form-control mb-3" id="shipCountry" name="shipping_country" placeholder="Country/Region" required>
                            <input type="text" class="form-control mb-3" id="shipAddress1" name="shipping_address1" placeholder="Address 1" required>
                            <input type="text" class="form-control mb-3" id="shipAddress2" name="shipping_address2" placeholder="Address 2" required>
                            <input type="text" class="form-control mb-3" id="shipAddress3" name="shipping_address3" placeholder="Address 3">
                            <input type="text" class="form-control mb-3" id="shipCity" name="shipping_city" placeholder="City" required>
                            <input type="text" class="form-control mb-3" id="shipZip" name="shipping_zip" placeholder="Zip Code" required>
                            <input type="text" class="form-control mb-3" id="shipPhone" name="shipping_phone" placeholder="Phone" required>
                        </div>
                    </div>
                </div>

                <!-- Remarks Tab -->
                <div class="tab-pane fade" id="remarks" role="tabpanel" aria-labelledby="remarks-tab">
                    <div class="mb-3">
                        <textarea class="form-control" name="remarks" placeholder="Remarks (For Internal Use Only)"></textarea>
                    </div>
                </div>
            </div>

            <!-- Submit Button (Optional) -->
            <div class="mt-3">
                <button type="submit" class="btn btn-primary">Submit</button>
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

    function copyBillingAddress() {
        document.getElementById('shipCountry').value = document.querySelector('[placeholder="Country/Region"]').value;
        document.getElementById('shipAddress1').value = document.querySelector('[placeholder="Address 1"]').value;
        document.getElementById('shipAddress2').value = document.querySelector('[placeholder="Address 2"]').value;
        document.getElementById('shipAddress3').value = document.querySelector('[placeholder="Address 3"]').value;
        document.getElementById('shipCity').value = document.querySelector('[placeholder="City"]').value;
        document.getElementById('shipZip').value = document.querySelector('[placeholder="Zip Code"]').value;
        document.getElementById('shipPhone').value = document.querySelector('[placeholder="Phone"]').value;
    }

    function copyBillingAddress() {
        const billingFields = document.querySelectorAll('#address .col-md-6:first-child input');
        const shippingFields = document.querySelectorAll('#address .col-md-6:last-child input');

        billingFields.forEach((billingInput, index) => {
            shippingFields[index].value = billingInput.value;
        });
    }
</script>
</html>
