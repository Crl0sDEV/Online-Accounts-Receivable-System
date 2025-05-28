<?php
session_start();
include 'connection.php';
$conn = openCon();

$errorMessage = isset($_SESSION['errorMessage']) ? $_SESSION['errorMessage'] : '';
if ($errorMessage) {
    unset($_SESSION['errorMessage']);
}

if (isset($_POST['btnOk'])) {
    $username = $_POST['uname'];
    $password = $_POST['pass'];

    // Check for hardcoded admin credentials
    if ($username === 'Admin' && $password === 'Admin123') {
        header("Location: ownerhomepage.php");
        exit();
    }

    // First check the ownerusers table
    $sql = "SELECT * FROM ownerusers WHERE BINARY Username = '$username' AND BINARY Password = '$password'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
        $_SESSION['role'] = $user['role'];
        $_SESSION['username'] = $user['Username'];

        switch ($user['role']) {
            case 'admin':
                header("Location: adminhomepage.php");
                break;
            case 'member':
                header("Location: memhomepage.php");
                break;
            case 'accountant':
                header("Location: accountanthomepage.php");
                break;
            default:
                header("Location: index.php");
                $_SESSION['errorMessage'] = 'Unauthorized access. Please contact administrator.';
                break;
        }
        exit();
    } 
    // If not found in ownerusers, check the users table
    else {
        $sql = "SELECT * FROM users WHERE BINARY Username = '$username' AND BINARY Password = '$password'";
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) > 0) {
            $user = mysqli_fetch_assoc($result);
            $_SESSION['role'] = 'member'; // Assuming all users in this table are members
            $_SESSION['username'] = $user['Username'];
            
            header("Location: memhomepage.php");
            exit();
        } else {
            $_SESSION['errorMessage'] = 'Invalid Username or Password. Please try again.';
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        }
    }

    mysqli_close($conn);
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Log In</title>
    <style>
        body {
            margin: 0;
            height: 100vh;
            font-family: Poppins;
            background: url('money.png') no-repeat center center fixed;
            background-size: cover;
            display: flex;
            align-items: center;
            justify-content: flex-end;
        }

        .container {
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            width: 310px;
            box-shadow: 0px 4px 20px rgba(0, 0, 0, 0.1);
            margin-right: 170px;
        }

        p {
            display: block;
            margin-bottom: 6px;
            font-size: 13px;
            color: #333;
        }

        h1 {
            font-size: 50px;
            text-align: center;
            margin-bottom: 20px;
            color: #004080;
        }

        label {
            display: block;
            margin-bottom: 6px;
            font-size: 16px;
            color: #333;
        }

        input {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 16px;
        }

        button {
            background-color: rgb(81, 160, 209);
            color: white;
            padding: 10px;
            width: 100%;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            margin-bottom: 15px;
        }

        button:hover {
            background-color: #4f9fd1;
        }

        a {
            color: #004080;
            text-decoration: none;
            font-size: 14px;
            display: inline-block;
            margin-top: 10px;
        }

        .errormessage {
            color: #ff4d4d;
            margin-top: 10px;
            font-size: 14px;
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="container">
        <form method="post" action="">
            <h1>Log In</h1>
            <label for="uname"><b>Username:</b></label>
            <input id="uname" name="uname" placeholder="Enter Username" required>

            <label for="pass"><b>Password:</b></label>
            <input id="pass" name="pass" type="password" placeholder="Enter Password" required>

            <button id="btnOk" name="btnOk">LOGIN</button>

            <a href="forgotPass.php">Forgot Password?</a><br>
            <a href="createmembershipprofile.php">Apply Membership?</a>
            <hr>
            <?php if ($errorMessage): ?>
                <div class="errormessage"><?php echo $errorMessage; ?></div>
            <?php endif; ?>
        </form>
    </div>
</body>

</html>