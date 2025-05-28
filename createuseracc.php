<?php
include 'connection.php';
$conn = openCon();
if (isset($_POST['btnSubmit'])) {
}

$name = isset($_GET['name']) ? htmlspecialchars($_GET['name']) : '';
$email = isset($_GET['email']) ? htmlspecialchars($_GET['email']) : '';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Create Membership Profile</title>
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
            margin-top: 180px;
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

    <form action="usersrecordsaved.php" method="post">
        <input type="hidden" name="formSource" value="createuseracc">
        <input type="hidden" name="name" value="<?php echo $name; ?>">
        <h1>Create User Account</h1>

        <div class="form-row">
            <label for="uname"><b>Username:</b></label>
            <input type="text" id="uname" name="uname" required>
        </div>

        <div class="form-row">
            <label for="pass"><b>Password:</b></label>
            <input type="password" id="pass" name="pass" required>
        </div>
        <input type="hidden" name="email" value="<?php echo $email?>">
        <div class="form-buttons">
            <button type="button" class="cancel-btn" onclick="window.location.href='createmembershipprofile.php'">Cancel</button>
            <button type="submit" name="btnSubmit" class="submit-btn">Submit</button>
        </div>
    </form>

    <?php closeCon($conn); ?>
</body>
</html>
