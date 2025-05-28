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
            max-width: 450px;
            margin-top: 80px;
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

    <form action="memberprorecordsaved.php" method="post">
        <input type="hidden" name="formSource" value="addmem">
        <h1>Add Membership Account</h1>

        <div class="form-row">
            <label for="name"><b>Full Name:</b></label>
            <input type="text" id="name" name="name" required>
        </div>

        <div class="form-row">
            <label for="address"><b>Address:</b></label>
            <input type="text" id="address" name="address" required>
        </div>

        <div class="form-row">
            <label for="bday"><b>Birthdate:</b></label>
            <input type="date" id="bday" name="bday" required>
        </div>

        <div class="form-row">
            <label for="sex"><b>Sex:</b></label>
            <select id="sex" name="sex" required>
                <option value="">Select...</option>
                <option value="Male">Male</option>
                <option value="Female">Female</option>
            </select>
        </div>

        <div class="form-row">
            <label for="email"><b>Email:</b></label>
            <input type="email" id="email" name="email" required>
        </div>
        
           <div class="form-row">
            <label for="application_type"><b>Application Type:</b></label>
            <select id="application_type" name="application_type" required>
                <option value="">Select...</option>
                <option value="Online">Online</option>
                <option value="Walk-In">Walk-In</option>
            </select>
        </div>

        <div class="form-buttons">
            <button type="button" class="cancel-btn" onclick="window.location.href='memberprofile.php'">Cancel</button>
            <button type="submit" name="btnSubmit" class="submit-btn">Submit</button>
        </div>
    </form>

    <?php closeCon($conn); ?>
</body>
</html>
