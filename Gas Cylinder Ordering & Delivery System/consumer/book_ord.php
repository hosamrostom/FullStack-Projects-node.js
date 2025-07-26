<?php
session_start();
include_once('../includes/db_con.php');

if(isset($_SESSION['cid']))
{


	
    $title = 'Book Order';
    $cid = $_SESSION['cid'];

    $result = mysqli_query($conn, "SELECT name FROM consumer_detail WHERE cid = $cid");
    $r = mysqli_fetch_row($result);

    $con_name = ' ' . $r[0];
    $path = 'design/';

    include_once('design/header.php');

    // Add active class for navigation bar
    echo "
    <script>
    $(document).ready(function(){
        $('#1').addClass('active');
    });
    </script>
    ";

    echo '
    <div class="container">
        <br>
        <div class="panel panel-default">
            <div class="panel-heading"><h2>' . $title . '</h2></div>
            <div class="panel-body">

                <form onSubmit="return t_sub();" method="post">
                    <div class="form-group">
                        <label><b>Terms & Conditions *</b></label>
                        <div style="border: 1px solid #e5e5e5; height: 300px; overflow: auto; padding: 10px;">
                            <p><i class="glyphicon glyphicon-chevron-right"></i> Order cannot be canceled once you place order.</p>
                            <p><i class="glyphicon glyphicon-chevron-right"></i> Prices may change as per government rules.</p>
                            <p><i class="glyphicon glyphicon-chevron-right"></i> You cannot place order for more than one gas cylinder.</p>
                            <p><i class="glyphicon glyphicon-chevron-right"></i> Once your order will be delivered, you can place an order for another refill.</p>
                            <p><i class="glyphicon glyphicon-chevron-right"></i> Order will be delivered to the registered address.</p>
                            <p><i class="glyphicon glyphicon-chevron-right"></i> Order status update will be sent via SMS (Linked mobile no.) & Email (Linked email address).</p>
                            <p><i class="glyphicon glyphicon-chevron-right"></i> Order will be delivered within a week. If not, you can file a complaint.</p>
                            <p><i class="glyphicon glyphicon-chevron-right"></i> Product delivery is strictly on a first-come, first-served basis.</p>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="quantity">عدد الأسطوانات:</label>
                        <input type="number" name="quantity" id="quantity" value="1" min="1" onchange="calculateAmount()" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="price">السعر لكل أسطوانة:</label>
                        <input type="number" name="price" id="price" value="475" onchange="calculateAmount()" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label>إجمالي السعر:</label>
                        <input type="text" name="amt" id="amt" value="475" readonly class="form-control">
                    </div>

                    <div class="form-group">
                        <div class="checkbox">
                            <label>
                                <input id="co_cb" onChange="return chk(this);" type="checkbox" name="agree" value="agree"/> Agree with the terms and conditions
                            </label>
                        </div>
                    </div>

                    <div class="form-group">
                        <button type="submit" id="submit" class="btn btn-warning" disabled>Confirm Order</button>

                        <!-- Loading bar -->
                        <div id="preload">
                            <img src="../process.gif" width="35%" style="margin-top:40px; filter: invert(100%) brightness(130%);">
                        </div>
                        <style>
                            #preload {
                                display: none;
                                width: 100%;
                                height: 100%;
                                top: 0;
                                left: 0;
                                position: fixed;
                                text-align: center;
                                background: rgba(0, 0, 0, 0.5);
                            }
                        </style>
                    </div>

                </form>
                <p id="oc_msg" class="text-danger"></p>
                <script>
                    function chk(co_cb) {
                        if(co_cb.checked == false) {
                            document.getElementById("submit").disabled = true;
                        } else if(co_cb.checked == true) {
                            document.getElementById("submit").disabled = false;
                        }
                        return false;
                    }

                    function calculateAmount() {
                        var quantity = document.getElementById("quantity").value;
                        var price = document.getElementById("price").value;
                        var total = quantity * price;
                        document.getElementById("amt").value = total;
                    }

                    function t_sub() {
                        $.ajax({
                            type: "POST",
                            url: "code/confirm_ord.php",
                            data: {
                                quantity: document.getElementById("quantity").value,
                                price: document.getElementById("price").value,
                                amt: document.getElementById("amt").value
                            },
                            beforeSend: function() {
                                document.getElementById("preload").style = "display:inline;";
                            },
                            success: function(html) {
                                document.getElementById("preload").style = "display:none;";
                                $("#oc_msg").html(html);
                            }
                        });
                        return false;
                    }
                </script>
            </div>
        </div>
    </div>
    ';

    include_once('design/footer.php');
}
else
{
    header('Location:../index.php');
}
?>

