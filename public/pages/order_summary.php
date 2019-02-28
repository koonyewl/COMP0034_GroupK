<?php include($_SERVER['DOCUMENT_ROOT'] . "/COMP0034_GroupK/private/initialize.php"); ?>

<?php require_once('../../private/shared/pages_header.php');
//if (isset($_SESSION['credential'])) {
//    $user_email = $_SESSION['credential'];
//    $accType = $_SESSION['accType'];
//    $data = get_data($db, $user_email, $accType,"email_address");
//    $welcome_message = "Welcome " . $data['first_name'] . " " . $data['last_name'] ."<br>Place your order";
//}else {
//    $welcome_message = "Before placing an order, Please log in <a href=\"login.php\">here.</a>";
//}
?>

<body onload="orderSummary()">

<!--<script>orderSummary();</script>-->

    <div class="card-header text-center" >
        <h1>Order Summary</h1>
    </div>


    <div class="container-fluid">
        <br>
        Review your order and select "Place order" button at the end of the page.
        <br><hr>
<!--        <b><h5>Order Id: </h5></b>-->

<!--        <br>-->
        <b>Delivery Details: </b>
        <br><br><br><br><br>

        <h4>Items: </h4><hr>
        <br>
        <p id="summaryTable"></p>
<!--        <p id="shopping_basket"></p>-->
        <hr>

        <div class="row">
            <div class="float-right">
                <!--            <table>-->
                <!--                <tr>Order Subtotal:</tr>-->
                <!--                <tr>Tax:</tr>-->
                <!--                <tr>Shipping:</tr>-->
                <!--                <tr>Order Total:</tr>-->
                <!--            </table>-->
                Order Subtotal: £<label id="subTotal"></label><br>
                Tax: £<label id="tax"></label><br>
                Shipping: £1.00<br><br>
                <b>Order Total: </b>£<label id="orderTotal"></label>
                <br><br>
            </div>
        </div>


        <div>
            <a href="order.php"><button type="submit">Back</button></a>
            <button type="submit">Place order</button>
        </div>

    </div>
</body>
<?php require_once('../../private/shared/pages_footer.php');?>