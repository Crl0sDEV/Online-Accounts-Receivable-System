<?php
require_once 'connection.php';
$conn = openCon();
session_start();

error_reporting(0);

$accountData = [];
$transactions = [];
$message = '';
$messageType = '';


if (isset($_GET['search'])) {
    $acct = mysqli_real_escape_string($conn, $_GET['search']);
    
    $sql = "SELECT fd.*, m.fullname, 
            (SELECT COUNT(*) FROM fixed_transactions WHERE fixed_account_id = fd.id AND transaction_type = 'withdrawal') as has_withdrawal
            FROM fixed_accounts fd
            JOIN member_profiles m ON fd.memberid = m.memberid
            WHERE fd.account_number = '$acct'";
    $res = mysqli_query($conn, $sql);

    if (mysqli_num_rows($res) > 0) {
        $accountData = mysqli_fetch_assoc($res);
        
        $transactionSql = "SELECT t.transaction_id, t.fixed_account_id, t.memberid, t.amount, 
                t.transaction_type,
                CASE 
                    WHEN t.transaction_type = 'withdrawal' THEN CONCAT('Withdrawal - ', IFNULL(fa.account_type, ''))
                    WHEN t.transaction_type = 'deposit' THEN CONCAT('Deposit - ', IFNULL(fa.account_type, ''))
                    ELSE t.transaction_type
                END AS description,
                t.transaction_date,
                t.remarks,
                fa.balance AS balance_after,
                CONCAT('FD', DATE_FORMAT(t.created_at, '%Y%m%d%H%i%s')) AS reference_number
                FROM fixed_transactions t
                JOIN fixed_accounts fa ON t.fixed_account_id = fa.id
                WHERE fa.account_number = '$acct'
                ORDER BY t.transaction_date DESC
                LIMIT 10";
        
        $transactionRes = mysqli_query($conn, $transactionSql);
        while ($row = mysqli_fetch_assoc($transactionRes)) {
            $transactions[] = $row;
        }
    } else {
        $message = 'Account not found';
        $messageType = 'error';
    }
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['transaction_type'])) {
    $acct = mysqli_real_escape_string($conn, $_POST['account_number']);
    $amount = floatval($_POST['amount']);
    $tType = mysqli_real_escape_string($conn, $_POST['transaction_type']);
    $remarks = mysqli_real_escape_string($conn, $_POST['description'] ?? '');
    $transactionBy = isset($_SESSION['username']) ? mysqli_real_escape_string($conn, $_SESSION['username']) : 'System';
    $reference = 'FD' . date('YmdHis') . rand(1000, 9999);
    $transactionDate = date('Y-m-d H:i:s');

    
    $acctSql = "SELECT fd.*, fd.id as fixed_account_id, m.memberid,
                (SELECT COUNT(*) FROM fixed_transactions 
                 WHERE fixed_account_id = fd.id AND transaction_type = 'withdrawal') as has_withdrawal
                FROM fixed_accounts fd
                JOIN member_profiles m ON fd.memberid = m.memberid
                WHERE fd.account_number = '$acct'";
    $acctRes = mysqli_query($conn, $acctSql);

    if (mysqli_num_rows($acctRes) > 0) {
        $acctRow = mysqli_fetch_assoc($acctRes);

        if ($acctRow['has_withdrawal'] > 0) {
            $message = 'This account has already been withdrawn. Only one withdrawal is permitted per fixed deposit account.';
            $messageType = 'error';
        } else {
            $balance = floatval($acctRow['balance']);
            $memberid = $acctRow['memberid'];
            $fixed_account_id = $acctRow['fixed_account_id'];
            $interestRate = floatval($acctRow['interest_rate']);
            $matured = (date('Y-m-d') >= date('Y-m-d', strtotime($acctRow['maturity_date'])));

            $principal = $balance;
            $interest = ($principal * $interestRate) / 100;
            $penalty = 0.00;

            if ($matured) {
                $totalPayout = $principal + $interest;
                if (abs($amount - $totalPayout) > 0.01) {
                    $message = 'Partial withdrawals are not permitted. You must withdraw the full amount: ₱' . number_format($totalPayout, 2);
                    $messageType = 'error';
                } else {
                    $newBal = 0;
                    $note = 'Full withdrawal with complete interest';
                }
            } else {
                $penalty = $interest * 0.40;
                $netInterest = $interest - $penalty;
                $totalPayout = $principal + $netInterest;
                if (abs($amount - $totalPayout) > 0.01) {
                    $message = 'Partial withdrawals are not permitted. You must withdraw the full amount: ₱' . number_format($totalPayout, 2);
                    $messageType = 'error';
                } else {
                    $newBal = 0;
                    $note = "Early withdrawal with 40% interest penalty (₱" . number_format($penalty, 2) . ")";
                }
            }

            if (empty($message)) { 
                
                $upd = "UPDATE fixed_accounts SET balance = $newBal WHERE account_number = '$acct'";
                if (!mysqli_query($conn, $upd)) {
                    $message = 'Failed to update balance: ' . mysqli_error($conn);
                    $messageType = 'error';
                } else {
                    
                    $fullRemarks = $remarks . ($remarks ? ' - ' : '') . $note;
                    $ins = "INSERT INTO fixed_transactions
                            (fixed_account_id, memberid, amount, transaction_type, 
                            transaction_date, remarks, created_at, updated_at)
                            VALUES
                            ($fixed_account_id, $memberid, $amount, '$tType',
                            '$transactionDate', '$fullRemarks', NOW(), NOW())";

                    if (!mysqli_query($conn, $ins)) {
                        $message = 'Failed to record transaction: ' . mysqli_error($conn);
                        $messageType = 'error';
                    } else {
                        $message = 'Full withdrawal completed. New balance: ₱' . number_format($newBal, 2) . '. Ref: ' . $reference;
                        $messageType = 'success';
                        
                        
                        header("Location: fixedtransaction.php?search=$acct&message=" . urlencode($message) . "&message_type=$messageType");
                        exit;
                    }
                }
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
    <title>Fixed Deposit Transactions</title>
    <meta charset="utf-8" />
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
            color:rgb(14, 13, 13);
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

        .not-found {
            color: #a94442;
            font-style: italic;
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
        <div class="logoutbtn"><button onclick="logout()">Log Out</button></div>
    </div>

    <div class="main">
        <div class="main-content">
            <div class="top-row">
                <div class="content-box account-section">
                    <h2>Account Search</h2>
                    <form method="GET" action="fixedtransaction.php">
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
                            <div class="account-detail-row">
                                <div class="account-detail-label">Status:</div>
                                <div style="<?php echo $accountData['has_withdrawal'] ? 'color:#cc3300;' : 'color:green;'; ?>">
                                    <?php echo $accountData['has_withdrawal'] ? 'WITHDRAWN' : 'AVAILABLE FOR WITHDRAWAL'; ?>
                                </div>
                            </div>
                        <?php else: ?>
                            <p>No account data found. Please search for an account.</p>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="content-box transaction-section">
                    <h2>Full Withdrawal Processing</h2>
                    
                    <?php if (isset($message)): ?>
                        <div class="message <?php echo $messageType; ?>"><?php echo htmlspecialchars($message); ?></div>
                    <?php endif; ?>
                    
                    <div id="withdrawal-notice" style="margin-bottom:15px;padding:10px;background:#fff6e0;border-radius:5px;font-size:14px;">
                        Fixed deposits require full withdrawal only. Partial withdrawals are not allowed.
                    </div>
                    
                    <?php if (!empty($accountData) && !$accountData['has_withdrawal']): ?>
                    <form method="POST" action="fixedtransaction.php" class="transaction-form">
                        <input type="hidden" name="account_number" value="<?php echo htmlspecialchars($accountData['account_number']); ?>">
                        <input type="hidden" name="transaction_type" value="withdrawal">
                        
                        <?php
                            
                            $principal = floatval($accountData['balance']);
                            $interestRate = floatval($accountData['interest_rate']);
                            $interest = ($principal * $interestRate) / 100;
                            
                            $today = new DateTime();
                            $maturity = new DateTime($accountData['maturity_date']);
                            if ($today >= $maturity) {
                                $fullAmount = $principal + $interest;
                            } else {
                                $penalty = $interest * 0.40;
                                $netInterest = $interest - $penalty;
                                $fullAmount = $principal + $netInterest;
                            }
                        ?>
                        
                        <div class="form-group">
                            <label for="amount">Full Withdrawal Amount</label>
                            <input type="number" id="amount" name="amount" min="0.01" step="0.01" 
                                   value="<?php echo number_format($fullAmount, 2, '.', ''); ?>" required readonly>
                            <div style="font-size:12px;color:#666;margin-top:5px;">
                                System will calculate full withdrawal amount with applicable interest and penalties
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="description">Description (optional)</label>
                            <textarea id="description" name="description" rows="3"></textarea>
                        </div>
                        <button type="submit" id="submit-btn">Process Full Withdrawal</button>
                    </form>
                    <?php elseif (!empty($accountData) && $accountData['has_withdrawal']): ?>
                        <p>This account has already been withdrawn and cannot be withdrawn again.</p>
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
                                <th>Remarks</th>
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
                                        <td><?php echo htmlspecialchars($transaction['remarks']); ?></td>
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
                    
                    if (!isMatured) {
                        if (!confirm("Warning: Early Withdrawal\n\nThis account has not yet reached its maturity date. An early withdrawal will result in a 40% penalty on earned interest.\n\nAre you sure you want to proceed with the full withdrawal?")) {
                            e.preventDefault();
                            return;
                        }
                    }
                    
                    if (!confirm("Confirm Full Withdrawal\n\nYou are about to process a complete withdrawal of this fixed deposit account. This action cannot be undone, and no further withdrawals will be allowed.\n\nDo you want to continue?")) {
                        e.preventDefault();
                    }
                });
            }
        });
    </script>
</body>
</html>