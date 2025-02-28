<?php
include '../database/database.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['customer_id'])) {
        $customer_id = $_POST['customer_id'];
        $company_name = $_POST['company_name'];
        $phone = $_POST['phone'];
        $contact_person = $_POST['contact_person'];
        $email = $_POST['email'];
        $payment_terms = $_POST['payment_terms'];
        $billing_address1 = $_POST['billing_address1'];
        $billing_address2 = $_POST['billing_address2'];
        $remarks = $_POST['remarks'];
        $stmt = $conn->prepare("UPDATE Customers SET CompanyName = ?, Phone = ?, ContactPerson = ?, Email = ?, PaymentTerms = ?, BillingAddress1 = ?, BillingAddress2 = ?, Remarks = ? WHERE CustomerID = ?");
        $stmt->execute([$company_name, $phone, $contact_person, $email, $payment_terms, $billing_address1, $billing_address2, $remarks, $customer_id]);
        echo 'success';
        exit;
    } elseif (isset($_POST['companyName'])) {
        $companyName = $_POST['companyName'];
        $phone = $_POST['phone'];
        $contactPerson = $_POST['contactPerson'];
        $email = $_POST['email'];
        $paymentTerms = $_POST['paymentTerms'];
        $billing_address1 = $_POST['billing_address1'];
        $billing_address2 = $_POST['billing_address2'];
        $remarks = $_POST['remarks'];
        $userId = isset($_SESSION['userId']) ? $_SESSION['userId'] : 1;
        $stmt = $conn->prepare("INSERT INTO Customers (CompanyName, Phone, ContactPerson, Email, PaymentTerms, BillingAddress1, BillingAddress2, Remarks, UserId) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$companyName, $phone, $contactPerson, $email, $paymentTerms, $billing_address1, $billing_address2, $remarks, $userId]);
        echo 'success';
        exit;
    }
}

if (isset($_GET['customer_id'])) {
    $customer_id = $_GET['customer_id'];
    $stmt = $conn->prepare("SELECT * FROM Customers WHERE CustomerID = ?");
    $stmt->execute([$customer_id]);
    $customer = $stmt->fetch(PDO::FETCH_ASSOC);
    echo $customer ? json_encode($customer) : json_encode(['error' => 'Customer not found']);
    exit;
}

try {
    $stmt = $conn->prepare("SELECT * FROM Customers");
    $stmt->execute();
    $customers = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error = "Database error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customers Dashboard</title>
    <link href="../statics/bootstrap css/bootstrap.min.css" rel="stylesheet">
    <link href="../statics/supplier.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/31e24a5c2a.js" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
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
            margin-left: 0;
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
        }
        .header-container {
            display: flex;
            align-items: center;
            justify-content: flex-start;
            width: 100%;
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
        .modal-lg-custom {
            max-width: 900px;
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
        <h1>Customers</h1>
    </div>

    <div class="controls-container">
        <div class="search-container">
            <i class="fa fa-search"></i>
            <input type="text" class="form-control" placeholder="Search..." id="searchInput">
        </div>
        <button class="btn btn-dark mr-2" id="newCustomerBtn" style="margin-right: 10px;">New <i class="fa fa-plus"></i></button>
        <select class="btn btn-outline-secondary mr-2" style="margin-right: 10px;" id="orderBySelect">
            <option value="">Order By</option>
            <option value="CompanyName_asc">Ascending (A → Z)</option>
            <option value="CompanyName_desc">Descending (Z → A)</option>
            <option value="Phone_asc">Phone (Ascending)</option>
            <option value="Phone_desc">Phone (Descending)</option>
            <option value="CreatedAt_desc">Newest</option>
            <option value="CreatedAt_asc">Oldest</option>
        </select>
        <select class="btn btn-outline-secondary" id="filterBySelect">
            <option value="">Filtered By</option>
            <option value="hasPhone">Has Phone</option>
            <option value="hasEmail">Has Email</option>
            <option value="hasAddress">Has Address</option>
        </select>
    </div>

    <div class="table-container">
        <table class="table table-striped table-hover" id="customerTable">
            <thead>
                <tr>
                    <th>Customer ID</th>
                    <th>Company Name</th>
                    <th>Phone</th>
                    <th>Contact Person</th>
                    <th>Email</th>
                    <th>Payment Terms</th>
                    <th>Billing Address 1</th>
                    <th>Billing Address 2</th>
                    <th>Remarks</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody id="customerTableBody">
                <?php if (isset($error)): ?>
                    <tr><td colspan="10" class="text-center text-danger"><?= htmlspecialchars($error) ?></td></tr>
                <?php elseif (!empty($customers)): ?>
                    <?php foreach ($customers as $customer): ?>
                        <tr>
                            <td><?= htmlspecialchars($customer['CustomerID']) ?></td>
                            <td><?= htmlspecialchars($customer['CompanyName']) ?></td>
                            <td><?= htmlspecialchars($customer['Phone']) ?></td>
                            <td><?= htmlspecialchars($customer['ContactPerson']) ?></td>
                            <td><?= htmlspecialchars($customer['Email']) ?></td>
                            <td><?= htmlspecialchars($customer['PaymentTerms']) ?></td>
                            <td><?= htmlspecialchars($customer['BillingAddress1']) ?></td>
                            <td><?= htmlspecialchars($customer['BillingAddress2']) ?></td>
                            <td><?= htmlspecialchars($customer['Remarks']) ?></td>
                            <td>
                                <button class="btn btn-warning btn-sm me-1" data-customer-id="<?= htmlspecialchars($customer['CustomerID']) ?>" data-toggle="modal" data-target="#editCustomerModal">
                                    <i class="fas fa-edit"></i>
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="10" class="text-center text-muted">No customers available. Input new customer.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="modal fade" id="editCustomerModal" tabindex="-1" role="dialog" aria-labelledby="editCustomerModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg-custom" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editCustomerModalLabel">Edit Customer</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="editCustomerForm">
                        <input type="hidden" id="editCustomerId" name="customer_id">
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label for="editCompanyName">Company Name</label>
                                <input type="text" class="form-control" id="editCompanyName" name="company_name" required>
                            </div>
                            <div class="col-md-6 form-group">
                                <label for="editPhone">Phone</label>
                                <input type="text" class="form-control" id="editPhone" name="phone" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label for="editContactPerson">Contact Person</label>
                                <input type="text" class="form-control" id="editContactPerson" name="contact_person" required>
                            </div>
                            <div class="col-md-6 form-group">
                                <label for="editEmail">Email</label>
                                <input type="email" class="form-control" id="editEmail" name="email" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label for="editPaymentTerms">Payment Terms</label>
                                <input type="text" class="form-control" id="editPaymentTerms" name="payment_terms" required>
                            </div>
                            <div class="col-md-6 form-group">
                                <label for="editBillingAddress1">Billing Address 1</label>
                                <input type="text" class="form-control" id="editBillingAddress1" name="billing_address1" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label for="editBillingAddress2">Billing Address 2</label>
                                <input type="text" class="form-control" id="editBillingAddress2" name="billing_address2">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 form-group">
                                <label for="editRemarks">Remarks</label>
                                <textarea class="form-control" id="editRemarks" name="remarks"></textarea>
                            </div>
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
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Original sidebar functionality
    var dropdowns = document.querySelectorAll('.dropdown');
    dropdowns.forEach(function(dropdown) {
        var toggleBtn = dropdown.querySelector('.toggle-btn');
        toggleBtn.addEventListener('click', function() {
            dropdown.classList.toggle('active');
        });
    });

    document.getElementById('newCustomerBtn').addEventListener('click', function() {
        window.location.href = 'NewCustomer.php';
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

    // Store initial customer data
    const customersData = <?php echo json_encode($customers); ?>;

    // Function to render table
    function renderTable(customers) {
        const tbody = document.getElementById('customerTableBody');
        tbody.innerHTML = '';
        
        if (!customers || customers.length === 0) {
            tbody.innerHTML = '<tr><td colspan="10" class="text-center text-muted">No customers available.</td></tr>';
            return;
        }

        customers.forEach(customer => {
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td>${customer.CustomerID}</td>
                <td>${customer.CompanyName}</td>
                <td>${customer.Phone}</td>
                <td>${customer.ContactPerson}</td>
                <td>${customer.Email}</td>
                <td>${customer.PaymentTerms}</td>
                <td>${customer.BillingAddress1}</td>
                <td>${customer.BillingAddress2 || ''}</td>
                <td>${customer.Remarks || ''}</td>
                <td>
                    <button class="btn btn-warning btn-sm me-1" data-customer-id="${customer.CustomerID}" data-toggle="modal" data-target="#editCustomerModal">
                        <i class="fas fa-edit"></i>
                    </button>
                </td>
            `;
            tbody.appendChild(tr);
        });
    }

    // Sort function
    function sortCustomers(customers, sortBy) {
        if (!sortBy) return customers;
        
        const [field, direction] = sortBy.split('_');
        return [...customers].sort((a, b) => {
            let valA = a[field] || '';
            let valB = b[field] || '';
            
            if (field === 'CreatedAt') {
                valA = new Date(valA);
                valB = new Date(valB);
            }
            
            if (direction === 'asc') {
                return valA > valB ? 1 : valA < valB ? -1 : 0;
            } else {
                return valA < valB ? 1 : valA > valB ? -1 : 0;
            }
        });
    }

    // Filter function
    function filterCustomers(customers, filterBy) {
        if (!filterBy) return customers;
        
        return customers.filter(customer => {
            switch(filterBy) {
                case 'hasPhone':
                    return customer.Phone && customer.Phone.trim() !== '';
                case 'hasEmail':
                    return customer.Email && customer.Email.trim() !== '';
                case 'hasAddress':
                    return customer.BillingAddress1 && customer.BillingAddress1.trim() !== '';
                default:
                    return true;
            }
        });
    }

    // Handle sorting and filtering
    const orderBySelect = document.getElementById('orderBySelect');
    const filterBySelect = document.getElementById('filterBySelect');

    function updateTable() {
        let filteredCustomers = filterCustomers(customersData, filterBySelect.value);
        let sortedCustomers = sortCustomers(filteredCustomers, orderBySelect.value);
        renderTable(sortedCustomers);
    }

    orderBySelect.addEventListener('change', updateTable);
    filterBySelect.addEventListener('change', updateTable);

    // Initial render
    renderTable(customersData);

    // Edit modal functionality
    $('#editCustomerModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var customerId = button.data('customer-id');
        $.get('Customers.php', { customer_id: customerId }, function(data) {
            if (data.error) {
                alert(data.error);
                $('#editCustomerModal').modal('hide');
            } else {
                $('#editCustomerId').val(data.CustomerID);
                $('#editCompanyName').val(data.CompanyName);
                $('#editPhone').val(data.Phone);
                $('#editContactPerson').val(data.ContactPerson);
                $('#editEmail').val(data.Email);
                $('#editPaymentTerms').val(data.PaymentTerms);
                $('#editBillingAddress1').val(data.BillingAddress1);
                $('#editBillingAddress2').val(data.BillingAddress2);
                $('#editRemarks').val(data.Remarks);
            }
        }, 'json');
    });

    document.getElementById('saveEditButton').addEventListener('click', function() {
        var form = document.getElementById('editCustomerForm');
        var data = new FormData(form);
        fetch('Customers.php', {
            method: 'POST',
            body: data
        })
        .then(response => response.text())
        .then(data => {
            if (data === 'success') {
                alert('Customer updated successfully');
                $('#editCustomerModal').modal('hide');
                location.reload();
            } else {
                alert('Error updating customer: ' + data);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error updating customer');
        });
    });
});
</script>
</body>
</html>