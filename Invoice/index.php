<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "inventory";

// Create connection
$connection = new mysqli($servername, $username, $password, $database);

if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}

$query = "SELECT invoiceid FROM sales ORDER BY invoiceid DESC";
$result = mysqli_query($connection, $query);

// Check if the query was successful and fetched at least one row
if ($result && $result->num_rows > 0) {
    $row = mysqli_fetch_array($result);
    $lastid = $row['invoiceid'];
} else {
    $lastid = null;
}

if (empty($lastid)) {
    $number = "A-0000001";
} else {
    $idd = (int) str_replace("A-", "", $lastid);
    $id = str_pad($idd + 1, 7, '0', STR_PAD_LEFT);
    $number = 'A-' . $id;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $invoiceid = $_POST["invoiceid"];
    $prodname = $_POST["productname"];
    $price = $_POST["price"];

    if (!$connection) {
        die("Connection failed: " . mysqli_connect_error());
    } else {
        $sql = "INSERT INTO sales (invoiceid, prodname, price) VALUES ('$invoiceid', '$prodname', '$price')";
        if (mysqli_query($connection, $sql)) {
            $query = "SELECT invoiceid FROM sales ORDER BY invoiceid DESC";
            $result = mysqli_query($connection, $query);

            // Check if the query was successful and fetched at least one row
            if ($result && $result->num_rows > 0) {
                $row = mysqli_fetch_array($result);
                $lastid = $row['invoiceid'];
            } else {
                $lastid = null;
            }

            if (empty($lastid)) {
                $number = "A-0000001";
            } else {
                $idd = (int) str_replace("A-", "", $lastid);
                $id = str_pad($idd + 1, 7, '0', STR_PAD_LEFT);
                $number = 'A-' . $id;
            }
        } else {
            echo "Record insertion failed.";
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice Generating</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-sm-4">
                <form action="<?php echo($_SERVER["PHP_SELF"]); ?>" method="post">
                    <br>
                    <div>
                        <h2>Invoice No Generating</h2>
                    </div>
                    <div>
                        <label>Invoice No</label>
                        <input type="text" class="form-control" name="invoiceid" id="invoiceid" style="color: blue; font-size: 16px; font-weight: bold;" value="<?php echo $number; ?>" readonly>
                    </div>
                    <div>
                        <label>Product Name</label>
                        <input type="text" class="form-control" name="productname" id="productname">
                    </div>
                    <div>
                        <label>Price</label>
                        <input type="text" class="form-control" name="price" id="price">
                    </div>
                    <br>
                    <div>
                        <input type="submit" class="btn btn-success">
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
