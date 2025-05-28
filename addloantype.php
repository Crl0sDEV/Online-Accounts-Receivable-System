<?php
include 'connection.php';
$conn = openCon();
if (isset($_POST['btnSubmit'])) {
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Add Membership</title>
    <style>
        body {
             background: url('moneyyyy.png') no-repeat center center fixed;
            font-family: 'Poppins', sans-serif;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            padding: 40px 20px;
        }

        form {
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 500px;
            margin-top: 150px;
        }

        h1 {
            text-align: center;
            color: #004080;
            margin-bottom: 30px;
        }

        .form-row {
            display: flex;
            margin-bottom: 10px;
            align-items: center;
        }

        .form-row label {
            width: 30%;
            font-weight: 500;
        }

        .form-row input,
        .form-row select {
            width: 70%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 14px;
        }

        .form-buttons {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 30px;
        }

        button {
            padding: 10px 20px;
            border: none;
            font-size: 14px;
            border-radius: 5px;
            cursor: pointer;
            color: white;
        }

        .submit-btn {
            background-color: #336699;
        }

        .submit-btn:hover {
            background-color: #1e4e76;
        }

        .cancel-btn {
            background-color: #999;
        }

        .cancel-btn:hover {
            background-color: #666;
        }
    </style>
</head>
<body>

    <form action="addloantyperecordsaved.php" method="post">
        <h1>Add Loan Type</h1>

        <div class="form-row">
            <label for="loantypename"><b>Loan Type Name:</b></label>
            <input type="text" id="loantypename" name="loantypename" required>
        </div>

        <div class="form-row">
            <label for="description"><b>Description:</b></label>
            <input type="text" id="description" name="description" required>
        </div>
        
        <div class="form-buttons">
            <button type="button" class="cancel-btn" onclick="window.location.href='loant.php'">Cancel</button>
            <button type="submit" name="btnSubmit" class="submit-btn">Submit</button>
        </div>
    </form>

    <?php closeCon($conn); ?>
</body>
</html>
