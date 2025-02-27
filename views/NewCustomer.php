<?php
include '../database/database.php';

session_start();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $errors = array();

    // Required fields validation
    $required_fields = array(
        'first_name', 'last_name', 'company_name', 'email', 'phone',
        'billing_country', 'billing_address1', 'billing_city', 'billing_zip', 'billing_phone',
        'shipping_country', 'shipping_address1', 'shipping_city', 'shipping_zip', 'shipping_phone'
    );
    foreach ($required_fields as $field) {
        if (empty(trim($_POST[$field]))) {
            $errors[] = "Please fill in the $field field.";
        }
    }

    // Email validation
    if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email address.";
    }

    // File upload handling (documents, optional)
    $document_path = NULL;
    if (isset($_FILES['documents']['error']) && $_FILES['documents']['error'] == 0) {
        $filename = $_FILES['documents']['name'];
        $filetype = pathinfo($filename, PATHINFO_EXTENSION);
        $allowed_extensions = array('pdf', 'doc', 'docx', 'jpg', 'jpeg', 'png');
        if (!in_array(strtolower($filetype), $allowed_extensions)) {
            $errors[] = "Invalid file type. Only PDF, DOC, DOCX, JPG, JPEG, PNG are allowed.";
        }
        if ($_FILES['documents']['size'] > 2097152) { // 2MB limit
            $errors[] = "File size exceeds the limit (2MB).";
        }
        if (empty($errors)) {
            $unique_filename = uniqid() . '.' . $filetype;
            $target_path = "uploads/" . $unique_filename;
            if (move_uploaded_file($_FILES['documents']['tmp_name'], $target_path)) {
                $document_path = $target_path;
            } else {
                $errors[] = "Error moving file.";
            }
        }
    }

    if (!empty($errors)) {
        foreach ($errors as $error) {
            echo $error . "<br>";
        }
        exit;
    }

    try {
        $conn = new PDO($dsn, $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $conn->beginTransaction();

        // Insert into customer table
        $stmt = $conn->prepare("INSERT INTO customer (salutation, first_name, last_name, company_name, email, phone, company_id, payment_terms, document_path, remarks) VALUES (:salutation, :first_name, :last_name, :company_name, :email, :phone, :company_id, :payment_terms, :document_path, :remarks)");
        $stmt->bindParam(':salutation', $_POST['salutation']);
        $stmt->bindParam(':first_name', $_POST['first_name']);
        $stmt->bindParam(':last_name', $_POST['last_name']);
        $stmt->bindParam(':company_name', $_POST['company_name']);
        $stmt->bindParam(':email', $_POST['email']);
        $stmt->bindParam(':phone', $_POST['phone']);
        $stmt->bindParam(':company_id', $_POST['company_id']);
        $stmt->bindParam(':payment_terms', $_POST['payment_terms']);
        $stmt->bindParam(':document_path', $document_path);
        $stmt->bindParam(':remarks', $_POST['remarks']);
        $stmt->execute();

        $customer_id = $conn->lastInsertId();

        // Insert billing address
        $stmt = $conn->prepare("INSERT INTO addresses (customer_id, address_type, country, address1, address2, address3, city, zip, phone) VALUES (:customer_id, 'billing', :country, :address1, :address2, :address3, :city, :zip, :phone)");
        $stmt->bindParam(':customer_id', $customer_id);
        $stmt->bindParam(':country', $_POST['billing_country']);
        $stmt->bindParam(':address1', $_POST['billing_address1']);
        $stmt->bindParam(':address2', $_POST['billing_address2']);
        $stmt->bindParam(':address3', $_POST['billing_address3']);
        $stmt->bindParam(':city', $_POST['billing_city']);
        $stmt->bindParam(':zip', $_POST['billing_zip']);
        $stmt->bindParam(':phone', $_POST['billing_phone']);
        $stmt->execute();

        // Insert shipping address
        $stmt = $conn->prepare("INSERT INTO addresses (customer_id, address_type, country, address1, address2, address3, city, zip, phone) VALUES (:customer_id, 'shipping', :country, :address1, :address2, :address3, :city, :zip, :phone)");
        $stmt->bindParam(':customer_id', $customer_id);
        $stmt->bindParam(':country', $_POST['shipping_country']);
        $stmt->bindParam(':address1', $_POST['shipping_address1']);
        $stmt->bindParam(':address2', $_POST['shipping_address2']);
        $stmt->bindParam(':address3', $_POST['shipping_address3']);
        $stmt->bindParam(':city', $_POST['shipping_city']);
        $stmt->bindParam(':zip', $_POST['shipping_zip']);
        $stmt->bindParam(':phone', $_POST['shipping_phone']);
        $stmt->execute();

        $conn->commit();
        echo "Customer created successfully.";
    } catch (Exception $e) {
        $conn->rollback();
        if (isset($document_path) && !empty($document_path)) {
            unlink($document_path); // Delete uploaded file on error
        }
        $errors[] = "Error creating customer: " . $e->getMessage();
    }

    if (!empty($errors)) {
        foreach ($errors as $error) {
            echo $error . "<br>";
        }
    }
} else {
    header("Location: Customers.php");
    exit;
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Customer</title>
    <link href="../statics/bootstrap css/bootstrap.min.css" rel="stylesheet">
    <link href="../statics/NewCustomer.css" rel="stylesheet">
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
        <form action="Customers.php" method="POST">
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
                        <input type="text" class="form-control" name="company_id" placeholder="Contact Person" style="width: 700px; height: 45px;">
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
        const billingFields = document.querySelectorAll('#address .col-md-6:first-child input');
        const shippingFields = document.querySelectorAll('#address .col-md-6:last-child input');

        billingFields.forEach((billingInput, index) => {
            shippingFields[index].value = billingInput.value;
        });
    }
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 