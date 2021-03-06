var today = new Date();
var dd = today.getDate();
var mm = today.getMonth() + 1; //Jan is 0
var yyyy = today.getFullYear();
var mm1; // next month
if (mm === 11) {  //if today is nov
    yyyy += 1;
    mm1 = 1;  //jan in 2 months time
} else if (mm === 12) {  //if today is dec
    yyyy += 1;
    mm1 = 2;  //feb in 2 months time
} else {
    mm1 = mm + 2;
}

if (dd < 10) {
    dd = '0' + dd
}
if (mm < 10) {
    mm = '0' + mm
}
if (mm1 < 10) {
    mm1 = '0' + mm1
}

today = yyyy + '-' + mm + '-' + dd;
var maxDate = yyyy + '-' + mm1 + '-' + dd;

var selected_date = document.getElementById("select_date");
if (selected_date) { //check if the select_date form is presented.
    selected_date.setAttribute("min", today);
    selected_date.setAttribute("max", maxDate);
}

var basket = []; //Create a basket to be accessible by different functions
var GrandTotal;

//Add the product to a basket base on the data in the modal.
function addProduct(modalId) {
    let image_src = document.getElementsByName("item_image")[modalId].src;
    let name = document.getElementsByName("item")[modalId].getAttribute("value");
    let price = document.getElementsByName("price")[modalId].getAttribute("value");
    let quantity = document.getElementsByName("quantity")[modalId].value;
    let image = "<img src='" + image_src + "' style='width:100px; height:100px; border-radius: 50%'>";

    var newItem = {
        item_image: null,
        item_name: null,
        item_price: 0.00,
        item_quantity: 0,
    };

    newItem.item_image = image;
    newItem.item_name = name;
    newItem.item_quantity = quantity;
    newItem.item_price = price;

    if (basket === undefined || basket.length === 0) { //if basket is empty, just add item in
        basket.push(newItem);
    }else { //basket is not empty, check if the item exists or not
        let new_or_not = false;
        for (let i = 0; i < basket.length; i++) {
            if (basket[i].item_name === newItem.item_name) {
                new_or_not = true;
                let temporary_Q = parseInt(basket[i].item_quantity);
                temporary_Q += parseInt(newItem.item_quantity);
                basket[i].item_quantity = temporary_Q;
                break;
            }
        }
        if (!new_or_not) {
            basket.push(newItem);
        }
    }
    render_basket(); // call a function to create a basket base on basket object

}

//Take the basket object, create a basket table with it
function render_basket(){
    add_proceed_button(); //show the proceed button before rendering the basket

    let basket_table = '';
    let shopping_basket = document.getElementById('shopping_basket');
    shopping_basket.innerHTML = '';

    basket_table += "<table class='table table-striped text-center'>";
    basket_table += "<tr><td class='font-weight-bolder'>Item Image</td>";
    basket_table += "<td class='font-weight-bolder'>Item Name</td>";
    basket_table += "<td class='font-weight-bolder'>Quantity</td>";
    basket_table += "<td class='font-weight-bolder'>Item Price (£)</td>";
    basket_table += "<td class='font-weight-bolder'>Total Price (£)</td>";
    basket_table += "<td class='font-weight-bolder'>Action</td></tr>";

    GrandTotal = 0;
    var name_session = [];

    for (let i = 0; i < basket.length; i++) {
        let total = parseFloat(basket[i].item_price) * parseInt(basket[i].item_quantity);
        basket_table += "<tr>";

        basket_table += "<td>" + basket[i].item_image + "</td>";
        basket_table += "<td>" + basket[i].item_name + "</td>";
        basket_table += "<td>" + basket[i].item_quantity + "</td>";
        basket_table += "<td>" + basket[i].item_price + "</td>";
        basket_table += "<td>" + total.toFixed(2) + "</td>";
        basket_table += "<td><button type='submit' class='btn btn-secondary orderbtn'  onClick='deductQuantity(\"" + basket[i].item_name + "\", this);'/>Deduct Quantity</button>" +
            " &nbsp<button type='submit' class='btn btn-secondary orderbtn' onClick='addQuantity(\"" + basket[i].item_name + "\", this);'/>Add Quantity</button>" +
            " &nbsp<button type='submit' class='btn btn-secondary orderbtn' onClick='deleteItem(\"" + basket[i].item_name + "\", this);'/>Delete Item</button></td>";
        basket_table += "</tr>";
        name_session[i] = basket[i].item_name;
        GrandTotal += parseFloat(basket[i].item_price) * parseInt(basket[i].item_quantity);
    }
    document.getElementById('grand_total').innerHTML = "The Grand Total is: £ "+ GrandTotal.toFixed(2);


    show_delivery_date();
    basket_table += "</table>";
    if (basket.length === 0) { //if there is nothing in the basket, hide everything
        hide_proceed_button();
        basket_table = "";
        document.getElementById('grand_total').innerHTML = "";
        document.getElementById('delivery_date_message').innerHTML = "";
    }
    shopping_basket.innerHTML = basket_table;
}

function deleteItem(item_name, e) {
    e.parentNode.parentNode.parentNode.removeChild(e.parentNode.parentNode);
    for (let i = 0; i < basket.length; i++) {
        if (basket[i].item_name === item_name) {
            basket.splice(i, 1);
        }
    }
    render_basket();
}

function deductQuantity(item_name) {
    for (let i = 0; i < basket.length; i++) {
        if (basket[i].item_name === item_name) {
            basket[i].item_quantity--;
        }

        if (basket[i].item_quantity === 0) {
            basket.splice(i, 1);
        }
    }
    render_basket();
}

function addQuantity(item_name) {
    for (let i = 0; i < basket.length; i++) {
        if (basket[i].item_name === item_name) {
            basket[i].item_quantity++;
        }
    }
    render_basket();
}

function date_validation() {
    let checked = false;
    var date_input = document.getElementById('select_date').value;
    if (Date.parse(date_input)) {
        checked = true;
    }else {
        alert("You need to input the date between " + today + " and " + maxDate);
    }
    if (checked) {
        var order_date = document.getElementById("menu_item");
        order_date.className = "display_block";
        location.href='#step2';
    }
    return checked;
}

function add_proceed_button() {
    document.getElementById('proceed_button').className = "display_block";
}

function hide_proceed_button() {
    document.getElementById('proceed_button').className = "display_none";
}

function show_delivery_date() {
    document.getElementById('delivery_date_message').innerHTML = "<p>Your order will be delivered on " + selected_date.value + "</p>";
}


//Send the basket object to after_order.php to be checked and saved in a $_SESSION variable.
function post_data_xhr() {
    let ajax = new XMLHttpRequest();
    ajax.open("POST", "after_order.php", true);
    ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    let delivery_date = selected_date.value;
    let data_to_post = "";
    let and = "&";
    data_to_post += "basket_length=" + basket.length;
    data_to_post += and + "grand_total=" + GrandTotal;
    data_to_post += and + "delivery_date=" + delivery_date;

    for (let i = 0; i < basket.length; i++) {

        data_to_post += and + "item_name_" + i + "=" + basket[i].item_name;
        data_to_post += and + "item_quantity_" + i + "=" + basket[i].item_quantity;
        data_to_post += and + "item_price_" + i + "=" + basket[i].item_price;
    }

    ajax.send(data_to_post);

    ajax.onreadystatechange = function() {
        if (ajax.readyState === 4 && ajax.status === 200) {
            window.location.href = "order_summary.php";
        }
    }
}

function date_selection(admin_id) {
    let date_selected = document.getElementById('choose_date').value;
    if (!Date.parse(date_selected)) {
        alert("You need to select a date.");
    }else {
        let ajax = new XMLHttpRequest();
        ajax.open("POST", "get_orders.php", true);
        ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        let data_to_post = "";
        let and = "&";
        data_to_post += "admin_id=" + admin_id;
        data_to_post += and + "date_selected=" + date_selected;

        ajax.send(data_to_post);
        document.getElementById('list_of_orders').innerHTML = "Loading...";
        ajax.onreadystatechange = function() {
            if (ajax.readyState === 4 && ajax.status === 200) {
                let list_of_orders = JSON.parse(ajax.responseText);
                if(list_of_orders[0]) {
                    document.getElementById('list_of_orders').innerHTML = create_summary_table (list_of_orders);
                }else {
                    let message = "There is no student that order to delivery for ";
                    message += "<u>" + date_selected + "</u>.";
                    document.getElementById('list_of_orders').innerHTML = message;
                }

            }
        }
    }

}

function create_summary_table (list_of_orders) {
    let summary_table = "";
    summary_table += "<table class='table table-striped text-center'>";
    summary_table += "<tr>";
    summary_table += "<th class='font-weight-bolder'>Order ID</th>";
    summary_table += "<th class='font-weight-bolder'>Student ID</th>";
    summary_table += "<th class='font-weight-bolder'>Student Name</th>";
    summary_table += "<th class='font-weight-bolder'>Items</th>";
    summary_table += "<th class='font-weight-bolder'>Total Price (£)</th>";
    summary_table += "</tr>";

    for (let i = 0; i < list_of_orders.length; i++) {
        summary_table += "<tr>";

        summary_table += "<td>" + list_of_orders[i].order_id + "</td>";
        summary_table += "<td>" + list_of_orders[i].student_id + "</td>";
        summary_table += "<td>" + list_of_orders[i].student_name + "</td>";
        summary_table += "<td>" + list_of_orders[i].item_list + "</td>";
        summary_table += "<td>" + list_of_orders[i].total_price + "</td>";
        summary_table += "</tr>";
    }

    return summary_table;
}

//Get a menu from a php file and show it on the order pages
function generate_menu(menu_id) {
    let ajax = new XMLHttpRequest();
    ajax.open("POST", "menu_for_order.php", true);
    ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    let data_to_post = "";
    // let and = "&";
    data_to_post += "menu_id=" + menu_id;

    ajax.send(data_to_post);

    ajax.onreadystatechange = function() {
        if (ajax.readyState === 4 && ajax.status === 200) {
            document.getElementById('menu_set_la').innerHTML = ajax.responseText;
        }
    }
}
