<?php require_once('header.php'); ?>

<?php
// Check if the customer is logged in or not
if (!isset($_SESSION['customer'])) {
    header('location: ' . BASE_URL . 'logout.php');
    exit;
} else {
    // If the customer is logged in, but admin made them inactive, then force logout this user.
    $statement = $pdo->prepare("SELECT * FROM tbl_customer WHERE cust_id=? AND cust_status=?");
    $statement->execute(array($_SESSION['customer']['cust_id'], 0));
    $total = $statement->rowCount();
    if ($total) {
        header('location: ' . BASE_URL . 'logout.php');
        exit;
    }
}
?>

<style>
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        line-height: 1.6;
        background-color: #f8f8f8;
    }

    .page {
        padding: 20px;
    }

    .user-content {
        background-color: #fff;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        margin-top: 20px;
    }

    .customer-info {
        padding-bottom: 20px;
        border-bottom: 1px solid #ddd;
    }

    .customer-info h4 {
        margin: 0;
        font-size: 24px;
        color: #333;
    }

    .customer-info p {
        margin: 8px 0;
        font-size: 16px;
        color: #777;
    }

    .latest-order-status {
        padding-top: 20px;
    }

    .latest-order-status h4 {
        margin: 0;
        font-size: 20px;
        color: #333;
    }

    .latest-order-status p {
        margin: 5px 0;
        font-size: 16px;
        color: #777;
    }

    .view-all-orders {
        margin-top: 20px;
        text-align: center;
    }

    .btn-primary {
        background-color: #007bff;
        color: #fff;
        padding: 12px 30px;
        text-decoration: none;
        border-radius: 5px;
        display: inline-block;
        font-size: 18px;
        transition: background-color 0.3s ease;
    }

    .btn-primary:hover {
        background-color: #0056b3;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }

    th, td {
        padding: 12px;
        border-bottom: 1px solid #ddd;
        text-align: left;
        font-size: 16px;
    }

    th {
        background-color: #f5f5f5;
        color: #333;
        font-weight: 600;
    }

    tr:hover {
        background-color: #f9f9f9;
    }

</style>


<div class="page">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <?php require_once('customer-sidebar.php'); ?>
            </div>
            <div class="col-md-12">
                <div class="user-content">
                    <h3 class="text-center">
                        <?php echo LANG_VALUE_90; ?>
                    </h3>

                    <!-- Existing customer information section -->
                    <div class="customer-info">
                        <h4>Welcome, <?php echo $_SESSION['customer']['cust_name']; ?>!</h4>
                        <p>Email: <?php echo $_SESSION['customer']['cust_email']; ?></p>
                        <p>Registered on: <?php echo $_SESSION['customer']['cust_datetime']; ?></p>
                    </div>

                    <!-- Existing latest order status section -->
                    <div class="latest-order-status">
                        <h4>Latest Order Status:</h4>
                        <?php
                        $statement = $pdo->prepare("SELECT * FROM tbl_payment WHERE customer_id = ? ORDER BY payment_date DESC LIMIT 1");
                        $statement->execute([$_SESSION['customer']['cust_id']]);
                        $latest_order = $statement->fetch(PDO::FETCH_ASSOC);

                        if ($latest_order) {
                            echo "<p>Order ID: " . $latest_order['payment_id'] . "</p>";
                            echo "<p>Payment Date: " . $latest_order['payment_date'] . "</p>";
                            echo "<p>Payment Method: " . $latest_order['payment_method'] . "</p>";
                            echo "<p>Paid Amount: $" . $latest_order['paid_amount'] . "</p>";
                            echo "<p>Payment Status: " . $latest_order['payment_status'] . "</p>";
                            echo "<p>Shipping Status: " . $latest_order['shipping_status'] . "</p>";
                        } else {
                            echo "<p>No recent orders found.</p>";
                        }
                        ?>
                    </div>

                    <!-- New customer order history chart section -->
                    <div class="customer-chart">
                        <h4>Order History Chart</h4>
                        <canvas id="orderChart" width="400" height="200"></canvas>
                    </div>

                    <!-- View all orders button -->
                    <div class="view-all-orders">
                        <a href="customer-order.php" class="btn btn-primary">View All Orders</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once('footer.php'); ?>

<!-- Chart.js library -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    // JavaScript code to create the chart
    document.addEventListener("DOMContentLoaded", function () {
        var ctx = document.getElementById('orderChart').getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($chart_labels); ?>,
                datasets: [{
                    label: 'Number of Orders',
                    data: <?php echo json_encode($chart_data_values); ?>,
                    backgroundColor: 'rgba(54, 162, 235, 0.7)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    x: {
                        type: 'time',
                        time: {
                            unit: 'day'
                        }
                    },
                    y: {
                        beginAtZero: true,
                        stepSize: 1
                    }
                }
            }
        });
    });
</script>
