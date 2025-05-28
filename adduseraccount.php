<?php
include 'connection.php';
$conn = openCon();
if (isset($_POST['btnSubmit'])) {
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Add User Account</title>
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
            margin-top: 100px;
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

        input, select {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 16px;
            transition: border-color 0.3s ease;
        }

        input:focus, select:focus {
            border-color: #336699; 
            outline: none;
        }
    </style>
</head>
<body>

    <form action="usersrecordsaved.php" method="post">
        <input type="hidden" name="formSource" value="adduseracc">
        <h1>Add User Account</h1>

        <div class="form-row">
            <label for="name"><b>Full Name:</b></label>
            <input type="text" id="name" name="name" required>
        </div>

        <div class="form-row">
            <label for="uname"><b>Username:</b></label>
            <input type="text" id="uname" name="uname" required>
        </div>

        <div class="form-row">
            <label for="pass"><b>Password:</b></label>
            <input type="password" id="pass" name="pass" required>
        </div>

        <div class="form-row">
        <label for="email"><b>Email:</b></label>
                <input type="email" id="email" name="email" required>
        </div>

        <div class="form-row">
                <label for="role"><b>Role:</b></label>
                <select id="role" name="role" required>
                    <option value="admin">Admin</option>
                    <option value="member">Member</option>
                    <option value="accountant">Accountant</option>
                </select>
        </div>

        <div class="form-buttons">
            <button type="button" class="cancel-btn" onclick="window.location.href='useraccounts.php'">Cancel</button>
            <button type="submit" name="btnSubmit" class="submit-btn">Submit</button>
        </div>
    </form>

    <?php closeCon($conn); ?>
</body>
</html>
