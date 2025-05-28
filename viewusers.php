<?php
include 'connection.php';
$conn = openCon();

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

$sql = "SELECT * FROM ownerusers WHERE ID = $id"; 
$Result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html>
<head>
    <title>User Profile</title>
    <style>
        body {
            margin: 0;
            font-family: 'Poppins', sans-serif;
            background-color: #f4f7fa;
            display: flex;
            height: 100vh; 
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
            display: block;
            color: white;
            padding: 12px 16px;
            font-size: 16px;
            text-decoration: none;
            border-radius: 5px;
            margin: 4px 0;
            transition: background-color 0.3s ease;
        }

        .sidebar a:hover {
            background-color: #336699;
        }

        .main {
            margin-left: 220px;
            padding: 30px;
            background-color: #fff;
            display: flex;
            justify-content: center;
            align-items: center;
            padding-top: 100px; 
            flex: 1; 
        }

        .formcontainer {
            background: rgba(255, 255, 255, 0.97);
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.2);
            width: 100%;
            max-width: 580px;
        }

        h1 {
            font-size: 24px;
            margin-bottom: 20px;
            color: #004080;
            text-align: center;
        }

        .profile-header {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }

        img {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            margin-right: 20px;
            border: 2px solid #004080;
        }

        .profile-name {
            font-size: 22px;
            font-weight: bold;
            color: #333;
        }

        table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
        }

        td {
            padding: 10px;
            border-bottom: 1px solid #ccc;
        }

        td strong {
            color: #004080;
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
            margin-top: 20px;
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
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 10;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .header .logo {
            font-size: 25px;
            font-weight: bold;
        }

        .header .logoutbtn {
            padding: 9px 20px;
            background-color: #336699;
            color: white;
            border: none;
            border-radius: 5px;
        }

        .header .logoutbtn:hover {
            background-color: #1e4e76;
        }
    </style>
</head>
<body>

    <div class="sidebar"><br><br><br><br>
        <a href="ownerhomepage.php">Home</a>
        <a href="usermanagement.php">User Management</a>
    </div>

    <div class="header">
        <div class="logo">Multipurpose Cooperative</div>
        <button class="logoutbtn" onclick="logout()">Log Out</button>
    </div>

    <script>
        function logout() {
            const confirmation = confirm("Are you sure you want to log out?");
            if (confirmation) {
                window.location.href = 'index.php';
            }
        }
    </script>

<?php
    if (mysqli_num_rows($Result) > 0) {
        $rows = mysqli_fetch_array($Result);
    ?>
    
    <div class="main">
        <div class="formcontainer">
            <h1>User Profile</h1>
            <div class="profile-header">
                <img src="anony.png" alt="Profile Picture">
                <div class="profile-name"><?php echo $rows["fullname"] ?></div>
            </div>
            <table>
                <tr>
                    <td><strong>Username:</strong></td>
                    <td><?php echo $rows["username"] ?></td>
                </tr>
                <tr>
                    <td><strong>Password:</strong></td>
                    <td><?php echo $rows["password"] ?></td>
                </tr>
                <tr>
                    <td><strong>Email:</strong></td>
                    <td><?php echo $rows["email"] ?></td>
                </tr>
                <tr>
                    <td><strong>Role:</strong></td>
                    <td><?php echo $rows["role"] ?></td>
                </tr>
            </table>

            <div style="display: flex; justify-content: space-between;">
                <button class="form-button cancel-button" type="button" onclick="window.location.href='usermanagement.php'">Back</button>
            </div>
        </div>
    </div>
    <?php
    } else {
        echo '<p style="text-align: center;">No user found.</p>'; 
    }
    mysqli_close($conn);
?>
</body>
</html>