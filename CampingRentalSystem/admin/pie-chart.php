<?php
// Assuming you have established a database connection ($mysqli)

// Database connection configuration
$host = 'localhost';     // MySQL server hostname
$username = 'root';  // MySQL username
$password = '';  // MySQL password
$database = 'campingsystem';  // MySQL database name

// Create a new MySQLi object
$conn = new mysqli($host, $username, $password, $database);
if ($conn->connect_errno) {
    echo "Failed to connect to MySQL: " . $mysqli->connect_error;
    // You can handle the connection error gracefully based on your requirements
    exit();
}

// Fetch data from the database
$sql = "SELECT customer_name, SUM(paid_amount) AS total_paid FROM tbl_payment GROUP BY customer_name";
$result = mysqli_query($conn, $sql);

// Prepare data for the chart
$data = array();
while ($row = mysqli_fetch_array($result)) {
    $data[] = array($row["customer_name"], (int)$row["total_paid"]);
}

// Convert data to JSON format
$jsonData = json_encode($data);
?>

<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
    google.charts.load('current', {'packages': ['corechart']});
    google.charts.setOnLoadCallback(drawChart);

    function drawChart() {
        var data = google.visualization.arrayToDataTable([
            ['Customer Name', 'Paid Amount'],
            <?php
            foreach ($data as $row) {
                echo "['" . $row[0] . "', " . $row[1] . "],";
            }
            ?>
        ]);

        var options = {
            title: 'Customer Paid Amount Analysis',
            titleTextStyle: {
                fontSize: 18 // Set the desired font size here
            }
        };

        var chart = new google.visualization.PieChart(document.getElementById('piechart'));

        chart.draw(data, options);
    }
</script>

<!-- The HTML container for the pie chart -->
<div id="piechart" style="width: 400px; height: 300px;"></div>
