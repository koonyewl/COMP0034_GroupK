<?php include($_SERVER['DOCUMENT_ROOT'] . "/COMP0034_GroupK/private/initialize.php"); ?>

<?php
if(!isset($_SESSION['customer_basket'])){
    redirect_to(url_for('/pages/order.php'));
}else {
    $shipping_fee = 1;
    $basket = $_SESSION['customer_basket'];
    $grand_total = $_SESSION['grand_total'] + $shipping_fee;
    $delivery_date = $_SESSION['delivery_date'];
    $query = "INSERT INTO order_detail (total_price, delivery_date) "
        ."VALUES ('$grand_total', '$delivery_date')";
    submit_query($db, $query);
    $order_id = mysqli_insert_id($db);
    if (save_order_id($db,$order_id, $_SESSION['id_field'],$_SESSION['user_id'])) {
        for ($i = 0; $i < count($basket); $i++) {
            $item_name = $basket[$i]['item_name'];
            $item_quantity = $basket[$i]['item_quantity'];
            $item = get_specific_data($db, 'item_id', $item_name, 'item', 'item_name');
            if ($item) {
                $item_id = $item['item_id'];
                $query = "INSERT INTO order_detail_item (order_id, item_id, quantity) "
                    ."VALUES ('$order_id', '$item_id', '$item_quantity')";
                submit_query($db, $query);
            }else {
                error_404();
            }
        }
        unset($_SESSION['customer_basket']);
        unset($_SESSION['grand_total']);
    }else {
        error_404();
    }
    require_once('../../private/shared/pages_header.php');
    ?>

    <h1>Confirmation</h1>
    <p>Your order is saved in the database</p>

<?php }?>
<?php require_once('../../private/shared/pages_footer.php');?>