<?php
ob_start();
session_start();
include("../../admin/inc/config.php");
include("../../admin/inc/functions.php");
// Getting all language variables into array as global variable
$i=1;
$statement = $pdo->prepare("SELECT * FROM tbl_language");
$statement->execute();
$result = $statement->fetchAll(PDO::FETCH_ASSOC);							
foreach ($result as $row) {
    define('LANG_VALUE_'.$i,$row['lang_value']);
    $i++;
}
?>
<?php
if (!isset($_REQUEST['msg'])) {
    if (empty($_POST['transaction_info'])) {
        header('location: ../../checkout.php');
    } else {
        $payment_date = date('Y-m-d H:i:s');
        $payment_id = time();

        // Calculate the total payment amount based on cart items' prices and quantities
        $total_amount = 0;
        foreach ($_SESSION['cart_p_qty'] as $key => $value) {
            $total_amount += $_SESSION['cart_p_current_price'][$key] * $value;
        }

        $statement = $pdo->prepare("INSERT INTO tbl_payment (   
            customer_id,
            customer_name,
            customer_email,
            payment_date,
            txnid, 
            paid_amount,   -- This will be updated with the total payment amount
            card_number,
            card_cvv,
            card_month,
            card_year,
            bank_transaction_info,
            payment_method,
            payment_status,
            shipping_status,
            payment_id
        ) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
        $statement->execute(array(
            $_SESSION['customer']['cust_id'],
            $_SESSION['customer']['cust_name'],
            $_SESSION['customer']['cust_email'],
            $payment_date,
            '',
            $total_amount,  // Set the 'paid_amount' to the calculated total amount
            '', 
            '',
            '', 
            '',
            $_POST['transaction_info'],
            'Bank Deposit',
            'Pending',
            'Pending',
            $payment_id
        ));

        $i=0;
        foreach ($_SESSION['cart_p_id'] as $key => $value) {
            $i++;
            $arr_cart_p_id[$i] = $value;
        }

        // ... (existing code remains unchanged)

        // Update the 'paid_amount' in the tbl_payment table
        $statement = $pdo->prepare("UPDATE tbl_payment SET paid_amount=? WHERE payment_id=?");
        $statement->execute(array($total_amount, $payment_id));

        // ... (existing code remains unchanged)

        header('location: ../../payment_success.php');
    }
}
?>
