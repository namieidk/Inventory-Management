<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Customer Form</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
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
                    <button class="nav-link" id="details-tab" data-bs-toggle="pill" data-bs-target="#details" type="button" role="tab" aria-controls="details" aria-selected="false">Other Details</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="address-tab" data-bs-toggle="pill" data-bs-target="#address" type="button" role="tab" aria-controls="address" aria-selected="true">Address</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="remarks-tab" data-bs-toggle="pill" data-bs-target="#remarks" type="button" role="tab" aria-controls="remarks" aria-selected="false">Remarks</button>
                </li>
            </ul>

            <!-- Tab Content -->
            <div class="tab-content" id="myTabContent">
                <!-- Other Details Tab -->
                <div class="tab-pane fade" id="details" role="tabpanel" aria-labelledby="details-tab">
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
                <div class="tab-pane fade show active" id="address" role="tabpanel" aria-labelledby="address-tab">
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

    <!-- Bootstrap JS and Custom Script -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Copy Billing Address to Shipping Address
        function copyBillingAddress() {
            const billingFields = document.querySelectorAll('#address .col-md-6:first-child input');
            const shippingFields = document.querySelectorAll('#address .col-md-6:last-child input');

            billingFields.forEach((billingInput, index) => {
                shippingFields[index].value = billingInput.value;
            });
        }
    </script>
</body>
</html>