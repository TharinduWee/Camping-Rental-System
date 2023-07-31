<?php require_once('header.php'); ?>

<?php
$statement = $pdo->prepare("SELECT * FROM tbl_settings WHERE id=1");
$statement->execute();
$result = $statement->fetchAll(PDO::FETCH_ASSOC);
foreach ($result as $row) {
    $banner_checkout = $row['banner_checkout'];
}
?>

<?php
if (!isset($_SESSION['cart_p_id'])) {
    header('location: cart.php');
    exit;
}
?>

<div class="page-banner" style="background-image: url(assets/uploads/<?php echo $banner_checkout; ?>)">
    <div class="overlay"></div>
    <div class="page-banner-inner">
        <h1><?php echo LANG_VALUE_22; ?></h1>
    </div>
</div>

<div class="page">
    <div class="container">
        <div class="row">
            <div class="col-md-12">

                <?php if (!isset($_SESSION['customer'])) : ?>
                    <p>
                        <a href="login.php" class="btn btn-md btn-danger"><?php echo LANG_VALUE_160; ?></a>
                    </p>
                <?php else : ?>

                    <h3 class="special"><?php echo LANG_VALUE_26; ?></h3>
                    <div class="cart">
                        <table class="table table-responsive table-hover table-bordered">
                            <!-- ... (existing table rows for cart items) ... -->
                            <tr>
                                <th colspan="7" class="total-text"><?php echo LANG_VALUE_81; ?></th>
                                <th class="total-amount"><?php echo LANG_VALUE_1; ?><?php echo $table_total_price; ?></th>
                            </tr>
                        </table>
                    </div>

                    <div class="cart-buttons">
                        <ul>
                            <li><a href="cart.php" class="btn btn-primary"><?php echo LANG_VALUE_21; ?></a></li>
                        </ul>
                    </div>

                    <div class="clear"></div>
                    <h3 class="special"><?php echo LANG_VALUE_33; ?></h3>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="row">

                                <div class="col-md-12 form-group">
                                    <label for=""><?php echo LANG_VALUE_34; ?> *</label>
                                    <select name="payment_method" class="form-control select2" id="advFieldsStatus">
                                        <option value=""><?php echo LANG_VALUE_35; ?></option>
                                        <option value="PayPal"><?php echo LANG_VALUE_36; ?></option>
                                        <option value="Bank Deposit"><?php echo LANG_VALUE_38; ?></option>
                                    </select>
                                </div>

                                <form class="paypal" action="<?php echo BASE_URL; ?>payment/paypal/payment_process.php" method="post" id="paypal_form" target="_blank">
                                    <input type="hidden" name="cmd" value="_xclick" />
                                    <input type="hidden" name="no_note" value="1" />
                                    <input type="hidden" name="lc" value="UK" />
                                    <input type="hidden" name="currency_code" value="USD" />
                                    <input type="hidden" name="bn" value="PP-BuyNowBF:btn_buynow_LG.gif:NonHostedGuest" />

                                    <input type="hidden" name="final_total" value="<?php echo $final_total; ?>">
                                    <div class="col-md-12 form-group">
                                        <input type="submit" class="btn btn-primary" value="<?php echo LANG_VALUE_46; ?>" name="form1">
                                    </div>
                                </form>

                                <form action="payment/bank/init.php" method="post" id="bank_form">
                                    <input type="hidden" name="amount" value="<?php echo $final_total; ?>">
                                    <div class="col-md-12 form-group">
                                        <label for=""><?php echo LANG_VALUE_43; ?></span></label><br>
                                        <?php
                                        $statement = $pdo->prepare("SELECT * FROM tbl_settings WHERE id=1");
                                        $statement->execute();
                                        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                                        foreach ($result as $row) {
                                            echo nl2br($row['bank_detail']);
                                        }
                                        ?>
                                    </div>
                                    <div class="col-md-12 form-group">
                                        <label for=""><?php echo LANG_VALUE_44; ?> <br><span style="font-size:12px;font-weight:normal;">(<?php echo LANG_VALUE_45; ?>)</span></label>
                                        <textarea name="transaction_info" class="form-control" cols="30" rows="10"></textarea>
                                    </div>
                                    <div class="col-md-12 form-group">
                                        <input type="submit" class="btn btn-primary" value="<?php echo LANG_VALUE_46; ?>" name="form3">
                                    </div>
                                </form>

                            </div>
                        </div>
                    </div>

                <?php endif; ?>

            </div>
        </div>
    </div>
</div>

<?php require_once('footer.php'); ?>
