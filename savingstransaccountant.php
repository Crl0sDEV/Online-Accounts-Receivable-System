<?php
require_once 'connection.php';
$conn = openCon();
session_start();


$accountData = [];
$transactions = [];
$message = '';
$messageType = '';


if (isset($_GET['search'])) {
    $accountNumber = $_GET['search'];


    $searchQuery = "SELECT sa.*, m.fullname, m.memberid
                  FROM savings_accounts sa
                  JOIN member_profiles m ON sa.memberid = m.memberid
                  WHERE sa.account_number = '$accountNumber'";
    $searchResult = mysqli_query($conn, $searchQuery);

    if (mysqli_num_rows($searchResult) > 0) {
        $accountData = mysqli_fetch_assoc($searchResult);


        $transactionQuery = "SELECT * FROM savings_transactions 
                          WHERE account_number = '$accountNumber' 
                          ORDER BY transaction_date DESC 
                          LIMIT 10";
        $transactionResult = mysqli_query($conn, $transactionQuery);

        while ($row = mysqli_fetch_assoc($transactionResult)) {
            $transactions[] = $row;
        }
    } else {
        $message = 'Account not found';
        $messageType = 'error';
    }
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['transaction_type'])) {
    $accountNumber = mysqli_real_escape_string($conn, $_POST['account_number']);
    $amount = floatval($_POST['amount']);
    $transactionType = mysqli_real_escape_string($conn, $_POST['transaction_type']);
    $description = mysqli_real_escape_string($conn, $_POST['description'] ?? '');
    $transactionBy = isset($_SESSION['username']) ? mysqli_real_escape_string($conn, $_SESSION['username']) : 'System';
    $referenceNumber = 'TXN' . date('YmdHis') . rand(1000, 9999);


    $balanceQuery = "SELECT balance, memberid FROM savings_accounts WHERE account_number = '$accountNumber'";
    $balanceResult = mysqli_query($conn, $balanceQuery);

    if (mysqli_num_rows($balanceResult) > 0) {
        $account = mysqli_fetch_assoc($balanceResult);
        $currentBalance = floatval($account['balance']);
        $memberid = $account['memberid'];


        if ($transactionType === 'deposit') {
            $newBalance = $currentBalance + $amount;
        } elseif ($transactionType === 'withdraw') {
            $newBalance = $currentBalance - $amount;
            if ($newBalance < 0) {
                $message = 'Insufficient funds';
                $messageType = 'error';
            }
        }

        if (empty($message)) {

            $updateQuery = "UPDATE savings_accounts SET balance = $newBalance WHERE account_number = '$accountNumber'";
            if (mysqli_query($conn, $updateQuery)) {

                $insertQuery = "INSERT INTO savings_transactions 
                               (account_number, memberid, transaction_type, amount, balance_after, description, transaction_by, reference_number)
                               VALUES ('$accountNumber', '$memberid', '$transactionType', $amount, $newBalance, '$description', '$transactionBy', '$referenceNumber')";

                if (mysqli_query($conn, $insertQuery)) {
                    $message = "Transaction completed successfully. New balance: ₱" . number_format($newBalance, 2) . ". Ref: $referenceNumber";
                    $messageType = 'success';


                    header("Location: savingstransaction.php?search=$accountNumber&message=" . urlencode($message) . "&message_type=$messageType");
                    exit;
                } else {
                    $message = 'Failed to record transaction: ' . mysqli_error($conn);
                    $messageType = 'error';
                }
            } else {
                $message = 'Failed to update balance: ' . mysqli_error($conn);
                $messageType = 'error';
            }
        }
    } else {
        $message = 'Account not found';
        $messageType = 'error';
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Savings Transactions</title>
    <style>
        body {
            margin: 0;
            font-family: 'Poppins', sans-serif;
            display: flex;
            background: #f4f7fa;
        }

        .sidebar {
            width: 220px;
            height: 100vh;
            background: #004080;
            color: #fff;
            padding-top: 15px;
            position: fixed;
            top: 0;
            left: 0;
            box-shadow: 2px 0 5px rgba(0, 0, 0, .1);
        }

        .sidebar a,
        .dropdown-btn {
            display: block;
            color: #fff;
            padding: 12px 16px;
            font-size: 16px;
            text-decoration: none;
            border: none;
            background: none;
            text-align: left;
            cursor: pointer;
            border-radius: 5px;
            margin: 4px 0;
            transition: .3s;
            box-sizing: border-box; 
        }

        .sidebar a:hover,
        .dropdown-btn:hover {
            background: #336699;
        }

        .dropdown-container {
            display: none;
            background: #003366;
            border-radius: 5px;
            margin: 0 10px;
            padding-left: 0;
            overflow: hidden;
        }

        .header {
            background: #004080;
            color: #fff;
            padding: 8px 20px;
            font-size: 16px;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 10;
            height: 50px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, .1);
        }

        .header .logo {
            font-size: 25px;
            font-weight: bold;
            line-height: 50px;
        }

        .header .logoutbtn {
            float: right;
            font-size: 14px;
            padding: 1px 4px;
            background: #336699;
            border-radius: 5px;
            line-height: 30px;
            margin-top: -40px;
        }

        .main {
            margin-left: 220px;
            padding: 30px;
            background: #f4f7fa;
            flex: 1;
            margin-top: 60px;
            width: calc(100% - 220px);
        }

        .container {
            display: flex;
            justify-content: space-between;
            gap: 20px;
            padding: 20px 0;
            flex-wrap: wrap;
        }

        .content-box {
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, .2);
        }

        .account-section {
            flex: 1;
            min-width: 400px;
        }

        .transaction-section {
            flex: 1;
            min-width: 400px;
        }

        .transactions-section {
            width: 100%;
            margin-top: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 5px rgba(0, 0, 0, .1);
        }

        th,
        td {
            padding: 12px;
            border: 1px solid #ccc;
            text-align: center;
        }

        th {
            background: #0059b3;
            color: #fff;
            font-weight: bold;
        }

        .account-detail-row {
            display: flex;
            margin-bottom: 8px;
        }

        .account-detail-label {
            font-weight: bold;
            width: 150px;
        }

        .transaction-form {
            background: #eef5ff;
            padding: 15px;
            border-radius: 8px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        button {
            padding: 8px 16px;
            font-size: 14px;
            background: #336699;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background: #1e4e76;
        }

        .message {
            padding: 10px;
            border-radius: 4px;
            margin: 10px 0;
        }

        .success {
            background: #dff0d8;
            color: #3c763d;
        }

        .error {
            background: #f2dede;
            color:rgb(12, 12, 12);
        }

        .main-content {
            display: flex;
            flex-direction: column;
            width: 100%;
        }

        .top-row {
            display: flex;
            gap: 20px;
            margin-bottom: 20px;
        }

        .bottom-row {
            width: 100%;
        }

    </style>
</head>

<body>
    <div class="sidebar"><br><br><br><br>
        <a href="adminhomepage.php">Home</a>
        <button class="dropdown-btn">Loan</button>
        <div class="dropdown-container">
            <a href="loanapplication.php">Loan Application</a>
            <a href="loant.php">Loan Type</a>
            <a href="loana.php">Loan Amortization</a>
            <a href="loani.php">Loan Interest</a>
        </div>
        <button class="dropdown-btn">Fixed</button>
        <div class="dropdown-container">
            <a href="fixedaccsaccountant.php">Accounts</a>
            <a href="fixedtransaccountant.php">Transactions</a>
        </div>
        <button class="dropdown-btn">Savings</button>
        <div class="dropdown-container">
            <a href="savingsaccountant.php">Accounts</a>
            <a href="savingstransaccountant.php">Transactions</a>
        </div>
        <button class="dropdown-btn">Time Deposit</button>
        <div class="dropdown-container">
            <a href="timedepoaccountant.php">Accounts</a>
            <a href="timedepotransaccountant.php">Transactions</a>
        </div>
        <a href="memprofaccountant.php">Member Profile</a>
        <a href="useraccsaccountant.php">User Accounts</a>
    </div>

    <div class="header">
        <div class="logo">Multipurpose Cooperative</div>
        <div class="logoutbtn"><button>Log Out</button></div>
    </div>

    <div class="main">
        <div class="container">
            <div class="account-section">
                <div class="content-box">
                    <h2>Account Search</h2>
                    <form method="GET" action="savingstransaction.php">
                        <input type="text" name="search" placeholder="Search Account Number"
                            value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>"
                            style="margin-bottom: 20px; padding: 8px; width: 100%; max-width: 300px;">
                        <button type="submit">Search</button>
                    </form>

                    <div id="account-details" style="text-align: left; background: #eef5ff; padding: 15px; margin-bottom: 20px; border-radius: 8px;">
                        <h3>Account Details</h3>
                        <?php if (!empty($accountData)): ?>
                            <div class="account-detail-row">
                                <div class="account-detail-label">Account Number:</div>
                                <div class="account-detail-value"><?php echo htmlspecialchars($accountData['account_number']); ?></div>
                            </div>
                            <div class="account-detail-row">
                                <div class="account-detail-label">Account Holder:</div>
                                <div class="account-detail-value"><?php echo htmlspecialchars($accountData['fullname']); ?></div>
                            </div>
                            <div class="account-detail-row">
                                <div class="account-detail-label">Account Type:</div>
                                <div class="account-detail-value"><?php echo htmlspecialchars($accountData['account_type']); ?></div>
                            </div>
                            <div class="account-detail-row">
                                <div class="account-detail-label">Creation Date:</div>
                                <div class="account-detail-value"><?php echo htmlspecialchars($accountData['created_at']); ?></div>
                            </div>
                            <div class="account-detail-row">
                                <div class="account-detail-label">Current Balance:</div>
                                <div class="account-detail-value">₱<?php echo number_format($accountData['balance'], 2); ?></div>
                            </div>
                        <?php else: ?>
                            <p>Please search for an account.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="transaction-section">
                <div class="content-box">
                    <h2>Transaction</h2>

                    <?php if (isset($message)): ?>
                        <div class="message <?php echo $messageType; ?>"><?php echo htmlspecialchars($message); ?></div>
                    <?php endif; ?>

                    <?php if (!empty($accountData)): ?>
                        <form method="POST" action="savingstransaction.php" class="transaction-form">
                            <input type="hidden" name="account_number" value="<?php echo htmlspecialchars($accountData['account_number']); ?>">

                            <div class="form-group">
                                <label for="transaction-type">Transaction Type</label>
                                <select id="transaction-type" name="transaction_type" required>
                                    <option value="">Select transaction type</option>
                                    <option value="deposit">Deposit</option>
                                    <option value="withdraw">Withdraw</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="amount">Amount</label>
                                <input type="number" id="amount" name="amount" min="0.01" step="0.01" required>
                            </div>

                            <div class="form-group">
                                <label for="description">Encoded By:</label>
                                <textarea id="description" name="description" rows="3"></textarea>
                            </div>

                            <div class="form-actions">
                                <button type="submit">Process Transaction</button>
                            </div>
                        </form>
                    <?php else: ?>
                        <p>Please search for an account first.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

             <div class="content-box" style="width: 95%; margin: 20px auto;">
            <h2>Recent Transactions</h2>
            <table>
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Reference</th>
                        <th>Type</th>
                        <th>Amount</th>
                        <th>Balance</th>
                        <th>Encoded By:</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($transactions)): ?>
                        <?php foreach ($transactions as $transaction): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($transaction['transaction_date']); ?></td>
                                <td><?php echo htmlspecialchars($transaction['reference_number']); ?></td>
                                <td><?php echo htmlspecialchars($transaction['transaction_type']); ?></td>
                                <td>₱<?php echo number_format($transaction['amount'], 2); ?></td>
                                <td>₱<?php echo number_format($transaction['balance_after'], 2); ?></td>
                                <td><?php echo htmlspecialchars($transaction['description'] ?? '-'); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php elseif (!empty($accountData)): ?>
                        <tr>
                            <td colspan="6">No transactions found for this account</td>
                        </tr>
                    <?php else: ?>
                        <tr>
                            <td colspan="6">Search for an account to see transactions</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('.dropdown-btn').forEach(button => {
                button.addEventListener('click', () => {
                    const container = button.nextElementSibling;
                    container.style.display = container.style.display === 'block' ? 'none' : 'block';
                    button.classList.toggle('active');
                });
            });


            const withdrawalForm = document.querySelector('.transaction-form');
            if (withdrawalForm) {
                withdrawalForm.addEventListener('submit', function(e) {
                    const transactionType = document.getElementById('transaction-type').value;
                    const amount = parseFloat(document.getElementById('amount').value);

                    if (transactionType === 'withdraw') {
                        if (!confirm(`Confirm withdrawal of ₱${amount.toFixed(2)}?\n\nThis action cannot be undone.`)) {
                            e.preventDefault();
                        }
                    } else if (transactionType === 'deposit') {
                        if (!confirm(`Confirm deposit of ₱${amount.toFixed(2)}?`)) {
                            e.preventDefault();
                        }
                    }
                });
            }
        });
    </script>
</body>

</html>