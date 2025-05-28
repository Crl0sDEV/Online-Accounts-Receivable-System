<?php
include 'connection.php';
$conn = openCon();

session_start();
$memberid = $_SESSION['memberid'] ?? $_GET['memberid'] ?? null;

?>
<!DOCTYPE html>
<html>
<head>
    <title>Apply Loan</title>
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
            margin-top: 120px;
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
    <form action="applyrecordsaved.php" method="post">
        <h1>Loan Application</h1>
        <input type="hidden" name="memberid" value="<?php echo htmlspecialchars($memberid); ?>">

        <div class="form-row">
            <label for="pamount"><b>Principal Amount:</b></label>
            <input type="number" id="pamount" name="pamount" min="1000" required>
        </div>

        <div class="form-row">
            <label for="ltype"><b>Loan Type:</b></label>
            <select id="ltype" name="ltype" required>
                <option value="">Select...</option>
                <option value="savings">Savings</option>
                <option value="fixed">Fixed</option>
                <option value="tdeposit">Time Deposit</option>
            </select>
        </div>

        <div class="form-row">
            <label for="sex"><b>Loan Term (Months):</b></label>
            <select id="sex" name="sex" required>
                <option value="">Select...</option>
                <option value="smonths">6 months</option>
                <option value="nmonths">9 months</option>
                <option value="tmonths">12 months</option>
            </select>
        </div>

        <div class="form-buttons">
            <button type="button" class="cancel-btn" onclick="window.location.href='loanapplication.php'">Cancel</button>
            <button type="submit" name="btnSubmit" class="submit-btn">Apply for Loan</button>
        </div>
    </form>

    <?php closeCon($conn); ?>
</body>
</html>
