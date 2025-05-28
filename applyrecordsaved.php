<?php
include 'connection.php';
$conn = openCon();

if (isset($_POST['btnSubmit'])) {
    if (!isset($_POST['memberid'], $_POST['pamount'], $_POST['ltype'], $_POST['sex'])) {
        echo "Missing required fields.";
        exit;
    }

    $memberid = $_POST['memberid'];
    $account_number = 'ACC' . strtoupper(substr(md5(mt_rand()), 0, 5));
    $principal_amount = $_POST['pamount'];
    $loan_type = $_POST['ltype'];
    $loan_term_input = $_POST['sex'];

    switch ($loan_term_input) {
        case 'smonths':
            $loan_term = 6;
            break;
        case 'nmonths':
            $loan_term = 9;
            break;
        case 'tmonths':
            $loan_term = 12;
            break;
        default:
            echo "Invalid loan term.";
            exit;
    }

    $interest_rates = [
        'savings' => [6 => 0.05, 9 => 0.06, 12 => 0.07],
        'fixed' => [6 => 0.07, 9 => 0.08, 12 => 0.09],
        'tdeposit' => [6 => 0.04, 9 => 0.05, 12 => 0.06]
    ];

    if (!isset($interest_rates[$loan_type][$loan_term])) {
        echo "Invalid loan type or term.";
        exit;
    }

    $interest_rate = $interest_rates[$loan_type][$loan_term];

    $check_member_sql = "SELECT memberid FROM member_profiles WHERE memberid = '$memberid'";
    $check_member_result = mysqli_query($conn, $check_member_sql);

    if (mysqli_num_rows($check_member_result) === 0) {
        echo "Member ID '$memberid' not found in member_profiles.";
        exit;
    }

    $check_loan_app_sql = "SELECT id FROM loan_applications WHERE memberid = '$memberid'";
    $check_loan_app_result = mysqli_query($conn, $check_loan_app_sql);

    if (mysqli_num_rows($check_loan_app_result) === 0) {
        $insert_loan_app_sql = "INSERT INTO loan_applications (memberid) VALUES ('$memberid')";
        if (!mysqli_query($conn, $insert_loan_app_sql)) {
            echo "Error inserting into loan_applications: " . mysqli_error($conn);
            exit;
        }
        $loan_application_id = mysqli_insert_id($conn);
    } else {
        $existing_row = mysqli_fetch_assoc($check_loan_app_result);
        $loan_application_id = $existing_row['id'];
    }

    $insert_account_loan_sql = "INSERT INTO account_loans (loan_application_id, memberid, account_number, principal_amount, interest_rate, loan_term, loan_type) 
    VALUES ('$loan_application_id', '$memberid', '$account_number', '$principal_amount', '$interest_rate', '$loan_term', '$loan_type')";

    if (!mysqli_query($conn, $insert_account_loan_sql)) {
        echo "Error inserting into account_loans: " . mysqli_error($conn);
        exit;
    }

    switch ($loan_type) {
        case 'savings':
            $insert_specific = "INSERT INTO savings_accounts (memberid, account_number, account_type, balance, principal_amount, interest_rate, loan_term) 
                VALUES ('$memberid', '$account_number', '$loan_type', '$principal_amount', '$principal_amount', '$interest_rate', '$loan_term')";
            break;
        case 'fixed':
            $account_type = $loan_type;
            $created_at = date('Y-m-d');
            $maturity_date = date('Y-m-d', strtotime("+$loan_term months"));
            $balance = $principal_amount;

            $insert_specific = "INSERT INTO fixed_accounts (memberid, account_number, account_type, interest_rate, maturity_date, created_at, balance) 
                VALUES ('$memberid', '$account_number', '$account_type', '$interest_rate', '$maturity_date', '$created_at', '$balance')";
            break;

        case 'tdeposit':
            $account_type = $loan_type;
            $created_at = date('Y-m-d');
            $maturity_date = date('Y-m-d', strtotime("+$loan_term months"));
            $balance = $principal_amount;

            $insert_specific = "INSERT INTO time_deposit_accounts (memberid, account_number, account_type, interest_rate, maturity_date, created_at, balance) 
                    VALUES ('$memberid', '$account_number', '$account_type', '$interest_rate', '$maturity_date', '$created_at', '$balance')";
            break;

        default:
            echo "Invalid loan type.";
            exit;
    }

    if (!mysqli_query($conn, $insert_specific)) {
        echo "Error inserting into $loan_type table: " . mysqli_error($conn);
    } else {
        echo "<script>alert('Loan application submitted successfully.'); window.location.href='loanapplication.php';</script>";
    }
}

mysqli_close($conn);
?>
