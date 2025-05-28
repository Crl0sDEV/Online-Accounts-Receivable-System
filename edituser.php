<?php
include 'connection.php';
$conn = openCon();

$profile = null;
$id = $_GET['id'] ?? null;

if ($id) {
    $sql = "SELECT * FROM ownerusers WHERE id = $id";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $profile = mysqli_fetch_assoc($result);
    }
}

if (isset($_POST['btnUpdate'])) {
    $fullname = $_POST['name'];
    $username = $_POST['uname'];
    $password = $_POST['pass'];
    $email = $_POST['email'];
    $role = $_POST['role'];

    $sql = "UPDATE ownerusers SET 
        fullname = '$fullname',
        username = '$username',
        password = '$password',
        email = '$email',
        role = '$role'
        WHERE id = $id";

    if (mysqli_query($conn, $sql)) {
        echo "<script>
                alert('Updated Successfully!');
                window.location.href='usermanagement.php'; 
              </script>";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit User</title>
    <style>
        body {
            margin: 0;
            font-family: 'Poppins', sans-serif;
            display: flex;
            background-color: #f4f7fa;
        }

        .sidebar {
            width: 220px;
            height: 100vh;
            background-color: #004080;
            color: white;
            padding-top: 15px;
            position: fixed;
            top: 0;
            left: 0;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
        }

        .sidebar a {
            box-sizing: border-box; 
            display: block;
            color: white;
            padding: 12px 16px;
            font-size: 16px;
            text-decoration: none;
            transition: background-color 0.3s ease;
            border-radius: 5px;
            margin: 4px 0;
        }

        .sidebar a:hover {
            background-color: #336699;
        }

        .main {
            margin-left: 220px;
            padding: 30px;
            flex: 1;
            background-color: #fff;
            text-align: center;
            margin-top: 60px;
            height: 85vh;
            display: flex;
            justify-content: center;
            align-items: flex-start;
        }

        .formcontainer {
            background: rgba(255, 255, 255, 0.97);
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.2);
            width: 100%;
            max-width: 400px;
            margin-top: 40px;
        }

        h1 {
            font-size: 30px;
            margin-bottom: 20px;
            color: #004080;
        }

        label {
            display: block;
            margin-bottom: 5px;
            text-align: left;
        }

        input, select {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 16px;
        }

        input:focus, select:focus {
            border-color: #336699;
            outline: none;
        }

        .form-button {
            padding: 10px;
            font-size: 14px;
            background-color: rgb(81, 160, 209);
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            width: 48%;
            margin-top: 10px;
        }

        .form-button:hover {
            background-color: #1e4e76;
        }

        .cancel-button {
            background-color: #ccc;
        }

        .cancel-button:hover {
            background-color: #999;
        }

        .header {
            background-color: #004080;
            color: white;
            padding: 8px 20px;
            font-size: 16px;
            text-align: left;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 10;
            height: 50px;
        }

        .header .logo {
            font-size: 25px;
            font-weight: bold;
            line-height: 50px;
        }

        .header .logoutbtn {
            float: right;
            font-size: 14px;
            padding: 9px 20px;
            background-color: #336699;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            margin-top: -40px;
        }

        .header .logoutbtn:hover {
            background-color: #1e4e76;
        }
    </style>
</head>
<body>

    <div class="sidebar">
        <br><br><br><br>
        <a href="ownerhomepage.php">Home</a>
        <a href="usermanagement.php">User Management</a>
    </div>

    <div class="header">
        <div class="logo">Multipurpose Cooperative</div>
        <button class="logoutbtn" onclick="logout()">Log Out</button>
    </div>

    <script>
        function logout(){
            const confirmation = confirm("Are you sure you want to log out?");
            if (confirmation) {
                window.location.href = 'index.php';
            }
        }
    </script>

    <div class="main">
        <div class="formcontainer">
            <form action="edituser.php?id=<?php echo $id ?>" method="post">
                <h1>Edit User</h1>
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" value="<?php echo $profile['fullname'] ?? '' ?>" required>

                <label for="uname">Username:</label>
                <input type="text" id="uname" name="uname" value="<?php echo $profile['username'] ?? '' ?>" required>

                <label for="pass">Password:</label>
                <input type="password" id="pass" name="pass" value="<?php echo $profile['password'] ?? '' ?>" required>

                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo $profile['email'] ?? '' ?>" required>

                <label for="role">Role:</label>
                <select id="role" name="role" required>
                    <option value="admin" <?php if (($profile['role'] ?? '') == 'admin') echo 'selected'?>>Admin</option>
                    <option value="member" <?php if (($profile['role'] ?? '') == 'member') echo 'selected'?>>Member</option>
                    <option value="accountant" <?php if (($profile['role'] ?? '') == 'accountant') echo 'selected'?>>Accountant</option>
                </select>

                <div style="display: flex; justify-content: space-between;">
                    <button class="form-button cancel-button" type="button" onclick="window.location.href='usermanagement.php'">Cancel</button>
                    <button class="form-button" type="submit" name="btnUpdate">Update</button>
                </div>
            </form>
        </div>
    </div>

    <?php closeCon($conn); ?>
</body>
</html>
