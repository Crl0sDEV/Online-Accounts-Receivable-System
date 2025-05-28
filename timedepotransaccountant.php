<?php
require_once 'connection.php';
$conn = openCon();
session_start();


$accountData = [];
$transactions = [];
$message = '';
$messageType = '';


if (isset($_GET['search'])) {
    $accountNumber = mysqli_real_escape_string($conn, $_GET['search']);


    $searchQuery = "SELECT td.*, m.fullname, m.memberid
                  FROM time_deposit_accounts td
                  JOIN member_profiles m ON td.memberid = m.memberid
                  WHERE td.account_number = '$accountNumber'";
    $searchResult = mysqli_query($conn, $searchQuery);

    if (mysqli_num_rows($searchResult) > 0) {
        $accountData = mysqli_fetch_assoc($searchResult);


        $transactionQuery = "SELECT *, 
                            CONCAT(transaction_type, ' - ', IFNULL(account_type, '')) AS description,
                            balance AS balance_after,
                            CONCAT('TD', DATE_FORMAT(created_at, '%Y%m%d%H%i%s')) AS reference_number,
                            created_at AS transaction_date
                            FROM time_deposit_transactions
                            WHERE account_number = '$accountNumber'
                            ORDER BY created_at DESC
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
    $referenceNumber = 'TD' . date('YmdHis') . rand(1000, 9999);


    $accountQuery = "SELECT td.*, m.memberid FROM time_deposit_accounts td
                    JOIN member_profiles m ON td.memberid = m.memberid
                    WHERE td.account_number = '$accountNumber'";
    $accountResult = mysqli_query($conn, $accountQuery);

    if (mysqli_num_rows($accountResult) > 0) {
        $account = mysqli_fetch_assoc($accountResult);
        $balance = floatval($account['balance']);
        $memberid = $account['memberid'];
        $accountType = $account['account_type'];
        $interestRate = floatval($account['interest_rate']);
        $createdAt = $account['created_at'];
        $matured = (date('Y-m-d') >= date('Y-m-d', strtotime($account['maturity_date'])));

        $principal = $balance;
        $interest = ($principal * $interestRate) / 100;
        $penalty = 0.00;

        if ($matured) {
            $totalPayout = $principal + $interest;
            if ($amount > $totalPayout) {
                $message = 'Insufficient funds (including interest)';
                $messageType = 'error';
            } else {
                $newBal = $totalPayout - $amount;
                $note = 'Matured withdrawal with full interest';
            }
        } else {
            $penalty = $interest * 0.20;
            $netInterest = $interest - $penalty;
            $totalPayout = $principal + $netInterest;

            if ($amount > $totalPayout) {
                $message = 'Insufficient funds (early withdrawal with penalty)';
                $messageType = 'error';
            } else {
                $newBal = $totalPayout - $amount;
                $note = "Early withdrawal with 20% interest penalty (₱" . number_format($penalty, 2) . ")";
            }
        }

        if (empty($message)) {

            $updateQuery = "UPDATE time_deposit_accounts
                           SET balance = $newBal
                           WHERE account_number = '$accountNumber'";
            if (mysqli_query($conn, $updateQuery)) {

                $insertQuery = "INSERT INTO time_deposit_transactions
                               (memberid, account_number, transaction_type, amount, 
                               interest_earned, penalty_amount, balance, account_type, 
                               interest_rate, maturity_date, description, transaction_by, reference_number)
                               VALUES
                               ($memberid, '$accountNumber', 'withdrawal', $amount,
                               $interest, $penalty, $newBal, '$accountType',
                               $interestRate, '{$account['maturity_date']}', '$description', '$transactionBy', '$referenceNumber')";

                if (mysqli_query($conn, $insertQuery)) {
                    $message = "Withdrawal completed. New balance: ₱" . number_format($newBal, 2) . ". Ref: $referenceNumber";
                    $messageType = 'success';


                    header("Location: timedepositstransaction.php?search=$accountNumber&message=" . urlencode($message) . "&message_type=$messageType");
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
<html lang="en">

<head>
    <title>Time Deposit Transactions</title>
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
            color:rgb(0, 0, 0);
        }

        .matured {
            color: green;
            font-weight: bold;
        }

        .not-matured {
            color:rgb(0, 0, 0);
            font-weight: bold;
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
        <div class="main-content">
            <div class="top-row">
                <div class="content-box account-section">
                    <h2>Search</h2>
                    <form method="GET" action="timedepositstransaction.php">
                        <input type="text" name="search" placeholder="Search Account Number"
                            value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>"
                            style="margin-bottom:20px;padding:8px;width:100%;max-width:300px;">
                        <button type="submit">Search</button>
                    </form>

                    <div id="account-details" style="background:#eef5ff;padding:15px;border-radius:8px;text-align:left;">
                        <h3>Account Details</h3>
                        <?php if (!empty($accountData)): ?>
                            <div class="account-detail-row">
                                <div class="account-detail-label">Account No.:</div>
                                <div><?php echo htmlspecialchars($accountData['account_number']); ?></div>
                            </div>
                            <div class="account-detail-row">
                                <div class="account-detail-label">Holder:</div>
                                <div><?php echo htmlspecialchars($accountData['fullname']); ?></div>
                            </div>
                            <div class="account-detail-row">
                                <div class="account-detail-label">Opened On:</div>
                                <div><?php echo htmlspecialchars(explode(' ', $accountData['created_at'])[0]); ?></div>
                            </div>
                            <div class="account-detail-row">
                                <div class="account-detail-label">Matures On:</div>
                                <div style="<?php
                                            $today = new DateTime();
                                            $maturity = new DateTime($accountData['maturity_date']);
                                            echo ($today >= $maturity) ? 'color:green;' : 'color:#cc3300;';
                                            ?>">
                                    <?php echo htmlspecialchars(explode(' ', $accountData['maturity_date'])[0]); ?>
                                    <?php echo ($today >= $maturity) ? '<strong>(Matured)</strong>' : '<strong>(Not Mature)</strong>'; ?>
                                </div>
                            </div>
                            <div class="account-detail-row">
                                <div class="account-detail-label">Interest Rate:</div>
                                <div><?php echo htmlspecialchars($accountData['interest_rate']) . '%'; ?></div>
                            </div>
                            <div class="account-detail-row">
                                <div class="account-detail-label">Balance:</div>
                                <div>₱<?php echo number_format($accountData['balance'], 2); ?></div>
                            </div>
                        <?php else: ?>
                            <p>Please search for an account.</p>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="content-box transaction-section">
                    <h2>Withdrawal</h2>

                    <?php if (isset($message)): ?>
                        <div class="message <?php echo $messageType; ?>"><?php echo htmlspecialchars($message); ?></div>
                    <?php endif; ?>

                    <?php if (!empty($accountData)): ?>
                        <form method="POST" action="timedepositstransaction.php" class="transaction-form">
                            <input type="hidden" name="account_number" value="<?php echo htmlspecialchars($accountData['account_number']); ?>">
                            <input type="hidden" name="transaction_type" value="withdraw">

                            <div class="form-group">
                                <label for="amount">Amount</label>
                                <input type="number" id="amount" name="amount" min="0.01" step="0.01" required>
                            </div>
                            <div class="form-group">
                                <label for="description">Encoded By:</label>
                                <textarea id="description" name="description" rows="3"></textarea>
                            </div>
                            <button type="submit" id="submit-btn">Process Withdrawal</button>
                        </form>
                    <?php else: ?>
                        <p>Please search for an account first.</p>
                    <?php endif; ?>
                </div>
            </div>

            <div class="bottom-row">
                <div class="content-box transactions-section">
                    <h2>Recent Transactions</h2>
                    <table>
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Reference</th>
                                <th>Type</th>
                                <th>Amount</th>
                                <th>Balance After</th>
                                <th>Encoded By:</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($transactions)): ?>
                                <?php foreach ($transactions as $transaction): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars(explode(' ', $transaction['transaction_date'])[0]); ?></td>
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
                    const today = new Date();
                    const maturity = new Date('<?php echo $accountData['maturity_date'] ?? ''; ?>');
                    const isMatured = today >= maturity;
                    const amount = parseFloat(document.getElementById('amount').value);

                    if (!isMatured) {
                        if (!confirm(`Warning: Early Withdrawal\n\nThis account has not yet reached its maturity date. An early withdrawal will result in a 20% penalty on earned interest.\n\nAre you sure you want to withdraw ₱${amount.toFixed(2)}?`)) {
                            e.preventDefault();
                        }
                    } else {
                        if (!confirm(`Confirm Withdrawal\n\nYou are about to withdraw ₱${amount.toFixed(2)} from this time deposit account.\n\nDo you want to continue?`)) {
                            e.preventDefault();
                        }
                    }
                });
            }
        });
    </script>
</body>

</html>