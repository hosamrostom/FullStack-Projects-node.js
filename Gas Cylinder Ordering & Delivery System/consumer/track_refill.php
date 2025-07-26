<?php
session_start();
include_once('../includes/db_con.php');

if (isset($_SESSION['cid'])) {
    $cid = $_SESSION['cid'];
    $title = 'Track your refill';
    $status = 'Pending';
    $date = date("Y-m-d");
    $time = date("H:i:s");

    // التحقق من وجود POST
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;
        $price = isset($_POST['price']) ? (float)$_POST['price'] : 0;
        $amt = isset($_POST['amt']) ? (float)$_POST['amt'] : ($quantity * $price);

        $query = "INSERT INTO order_detail (cid, did, date, time, amt, status)
                  VALUES (?, NULL, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("issds", $cid, $date, $time, $amt, $status);
        if (!$stmt->execute()) {
            die("فشل في إضافة الطلب: " . $stmt->error);
        }
    }

    // جلب اسم المستهلك
    $stmt_name = $conn->prepare("SELECT name FROM consumer_detail WHERE cid = ?");
    $stmt_name->bind_param("i", $cid);
    $stmt_name->execute();
    $stmt_name->bind_result($con_name);
    $stmt_name->fetch();
    $stmt_name->close();

    $path = 'design/';
    include_once('design/header.php');

    echo "
    <script>
    $(document).ready(function(){
        $('#2').addClass('active');
    });
    </script>
    ";

    // جلب آخر طلب غير مكتمل
    $stmt_order = $conn->prepare("SELECT * FROM order_detail WHERE cid = ? AND status != 'Delivered' ORDER BY date DESC, time DESC LIMIT 1");
    $stmt_order->bind_param("i", $cid);
    $stmt_order->execute();
    $result = $stmt_order->get_result();

    if ($result->num_rows > 0) {
        $r = $result->fetch_assoc();
        echo '
        <div class="container">
            <br>
            <div class="panel panel-default">
                <div class="panel-heading"><h2>' . $title . '</h2></div>
                <div class="panel-body">
                    <table class="table table-striped" style="font-size:95%">
                        <thead>
                          <tr>
                            <th>Order Id</th>
                            <th>Date of order place</th>
                            <th>Time of order place</th>
                            <th>Payable amount</th>
                            <th>Order Status</th>
                          </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>' . $r['oid'] . '</td>
                                <td>' . $r['date'] . '</td>
                                <td>' . $r['time'] . '</td>
                                <td>' . $r['amt'] . '</td>
                                <td>' . $r['status'] . '</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        ';
    } else {
        echo "<div class='container'><div class='alert alert-info'>لا توجد طلبات حالياً.</div></div>";
    }

    include_once('design/footer.php');
} else {
    header('Location:../index.php');
    exit();
}
?>
