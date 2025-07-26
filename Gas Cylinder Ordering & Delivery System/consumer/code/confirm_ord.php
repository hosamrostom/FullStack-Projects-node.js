<?php
session_start();
include_once('../../includes/db_con.php');
include_once('../../apis/Way2SMS/way2sms-api.php');
include_once('../../apis/mail_cfg.php');

if (($_SERVER['REQUEST_METHOD'] === 'POST') && isset($_SESSION['cid'])) {
    $cid = $_SESSION['cid'];
    $amt = isset($_POST['amt']) ? floatval($_POST['amt']) : 475.00;

    // Check latest order status
    $result = mysqli_query($conn, "SELECT status FROM order_detail WHERE cid = $cid ORDER BY date DESC, time DESC") or die(mysqli_error($conn));
    $r = mysqli_fetch_row($result);

    if (($r[0] == 'Delivered') || (!isset($r[0]))) {
        // Insert order with amount
        mysqli_query($conn, "
            INSERT INTO order_detail (cid, did, date, time, amt)
            VALUES (
                $cid,
                (SELECT did FROM consumer_detail WHERE cid = $cid),
                CURRENT_DATE,
                CURRENT_TIME,
                $amt
            )
        ") or die(mysqli_error($conn));

        // Fetch user contact info
        $result = mysqli_query($conn, "SELECT * FROM consumer_detail WHERE cid = $cid") or die(mysqli_error($conn));
        $r = mysqli_fetch_assoc($result);
        $mno = $r['m_no'];
        $eid = $r['e_id'];

        // Get order details (latest)
        $result = mysqli_query($conn, "SELECT * FROM order_detail WHERE cid = $cid ORDER BY date DESC, time DESC") or die(mysqli_error($conn));
        $r = mysqli_fetch_assoc($result);

        // SMS
        sendWay2SMS("9904436106", "MJENISH8", $mno, "Your order id - " . $r['oid'] . ", Payable Amount - ₹" . $r['amt']);

        // Email
        $mail->addAddress($eid);
        $mail->Subject = 'Order Placed';
        $mail->isHTML(true);
        $mailContent = "
            <h2>Order Confirmation</h2>
            <p><strong>Order ID:</strong> {$r['oid']}</p>
            <p><strong>Payable Amount:</strong> ₹{$r['amt']}</p>
            <p>We will definitely deliver it within a week.</p>
        ";
        $mail->Body = $mailContent;
        $mail->send();

        echo "<script>
            alert('Order successfully placed.');
            location.reload();
        </script>";
    } else {
        echo 'Your current <a href="track_refill.php" class="text-danger" style="text-decoration:underline">order</a> is still in progress.';
    }
} else {
    header('Location:../index.php');
}
?>
