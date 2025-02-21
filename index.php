<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #dbe4f0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            background-color: #ffffff;
            border-radius: 10px;
            padding: 30px;
            width: 700px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            display: flex;
            gap: 30px;
        }
        .form-group {
            display: flex;
            flex-direction: column;
            gap: 15px;
            width: 60%;
        }
        label {
            font-size: 16px;
            color: #333333;
        }
        input[type="text"], input[type="number"] {
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }
        .image-upload {
            width: 40%;
            text-align: center;
            border: 2px dashed #bbb;
            border-radius: 10px;
            padding: 20px;
            cursor: pointer;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }
        .image-upload img {
            width: 50px;
            height: 50px;
            margin-bottom: 10px;
        }
        .image-upload span {
            color: #007BFF;
            cursor: pointer;
            text-decoration: underline;
        }
        .buttons {
            display: flex;
            gap: 10px;
            justify-content: flex-end;
            margin-top: 20px;
        }
        button {
            border: none;
            border-radius: 5px;
            color: white;
            font-size: 16px;
            padding: 10px 20px;
            cursor: pointer;
        }
        .save {
            background-color: #3456DC;
        }
        .cancel {
            background-color: #333333;
        }
    </style>
</head>
<body>

    <div class="container">
        <div class="form-group">
            <h2>Edit Product</h2>
            <label for="productName">Product Name</label>
            <input type="text" id="productName">
            <label for="typeName">Type Name</label>
            <input type="text" id="typeName">
            <label for="price">Price</label>
            <input type="number" id="price">
            <label for="supplierName">Supplier Name</label>
            <input type="text" id="supplierName">
            <div class="buttons">
                <button class="save">Save</button>
                <button class="cancel">Cancel</button>
            </div>
        </div>

        <div class="image-upload">
            <img src="https://cdn-icons-png.flaticon.com/512/1829/1829586.png" alt="Upload Icon">
            <p>Drag Image(s) here or <span>Browse images</span></p>
        </div>
    </div>

</body>
</html>
