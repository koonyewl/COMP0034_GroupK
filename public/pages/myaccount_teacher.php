<?php include($_SERVER['DOCUMENT_ROOT'] . "/COMP0034_GroupK/private/initialize.php"); ?>

<?php require_once('../../private/shared/pages_header.php');
if (!isset($_SESSION['credential'])) {
    redirect_to(url_for('/pages/myaccount.php'));
}else {
    $user_email = $_SESSION['credential'];
    $data = get_data($db, $user_email, "administrator", "email_address");

    $result = get_admin($db, $user_email, 'email_address');
//print_r($result);

    $student_a = [];
    $school_a = [];
    $parent_a = [];
    for ($i = 0; $i < count($result); $i++) {
        $student_a[$i] = get_data($db, $result[$i]['student_id'], 'student', 'student_id');
        $school_a[$i] = get_data($db, $result[$i]['school_id'], 'school', 'school_id');
    }

    $admin_orders = get_order_of_admin($db, $data['admin_id']);

//print_r($student_a);
//echo "<br>";
//print_r($school_a);
//echo "<br>";
}
?>

<header class="card-header text-center">
    <h1>Welcome to your Teacher's Account!</h1>
</header>


<div class="container">
    <br><br>
    <div class="row">
        <div class="col-md-1"></div>
        <div class="col-md-4">

            <img class="img-profile rounded-circle" src="../img/kien.jpg" style="width:200px;height:200px;"><br>
            <h2><?php echo $data['first_name'] . " " . $data['last_name'];?> </h2>
            <!--            <div class="name"><small>Trung Kien Nguyen</small></div><br><br>-->
        </div>
        <div class="col-md-7">
            <br><br>
            <h3 >Xin Chao <?php echo $data['first_name'];?>. </h3>
            <h5>From your account dashboard, you can view your student's details, approve students, edit your password and account details. </h5>
        </div>
    </div>


    <br><hr><br><br>

    <div class="row">
        <div class="col-lg-4">

            <div class="nav list-group text-center text-uppercase">

                <button class="nav-link list-group-item" onclick="show_selected_tab(0)">Profile</button>
                <button class="nav-link list-group-item" onclick="show_selected_tab(1)">Approve Students</button>
                <button class="nav-link list-group-item" onclick="show_selected_tab(2)">View Orders</button>
                <button class="nav-link list-group-item" onclick="show_selected_tab(3)">View Students' Orders</button>
                <button class="nav-link list-group-item" onclick="show_selected_tab(4)">Edit Account</button>
                <a class="nav-link list-group-item" href="log_out.php">Logout</a>
            </div>

        </div>
        <hr class="sidebar-divider d-none d-md-block">
        <div class="col-lg-8">
            <h3 class="text-danger">
                <strong>
                    <?php if(isset($_SESSION['message'])) {
                        echo $_SESSION['message'];
                        unset($_SESSION['message']);
                    }?>
                </strong>
            </h3>

            <div name="my_account_tab" class="display_none">
                <div class="tab-content">
                    <h1>Teacher Profile</h1>
                    <p>
                        School: <?php echo $school_a[0]['school_name'];?><br>
                        Address: <?php echo $school_a[0]['school_address'];?><br><br>
                    <h3 class="text-center">Student's Information</h3>

                    </p>
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th scope="col">No.</th>
                            <th scope="col">First Name</th>
                            <th scope="col">Last Name</th>
                            <th scope="col">Email Address</th>
                            <th scope="col">Approve Status</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        for ($i = 0; $i < count($result); $i++) {?>
                        <tr>
                            <th scope="row"><?php echo $i +1;?></th>
                            <td><?php echo $student_a[$i]['first_name'];?></td>
                            <td><?php echo $student_a[$i]['last_name'];?></td>
                            <td><?php echo $student_a[$i]['email_address'];?></td>
                            <td><?php if($student_a[$i]['status'] == 0) {
                                    echo "Not verified";
                                }else {
                                    echo "Verified";
                                }?></td>

                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div name="my_account_tab" class="display_none">
                <div class="tab-content">
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th scope="col">No.</th>
                            <th scope="col">First Name</th>
                            <th scope="col">Last Name</th>
                            <th scope="col">Email Address</th>
                            <th scope="col"></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $k = 0;
                        for ($j = 0; $j < count($result); $j++) {
                            if($student_a[$j]['status'] == 0) {
                                $k++?>
                                <tr>
                                    <form method="post" action="verify_student.php">
                                        <th scope="row"><?php echo $k;?></th>
                                        <td><?php echo $student_a[$j]['first_name'];?></td>
                                        <td><?php echo $student_a[$j]['last_name'];?></td>
                                        <td><?php echo $student_a[$j]['email_address'];?></td>
                                        <td><button class="btn btn-primary btn-block" name="submit" type="submit" value="<?php echo $student_a[$j]['student_id']?>">Approve</button></td>
                                    </form>
                                </tr>
                            <?php }}?>
                        </tbody>
                    </table>

                </div>
            </div>

            <div name="my_account_tab" class="display_none">
                <div class="tab-content">
                    <h1>View Order</h1>
                    <div class="tab-content">
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th scope="col">Order ID</th>
                                <th scope="col">Delivery Date</th>
                                <th scope="col">Total Price</th>
                                <th scope="col">View</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php for ($i = 0; $i < count($admin_orders); $i++) {?>

                            <tr>
                                <th scope="row"><?php echo $admin_orders[$i]['order_id'];?></th>
                                <td><?php echo $admin_orders[$i]['delivery_date'];?></td>
                                <td><?php echo $admin_orders[$i]['total_price'];?></td>
                                <td><button data-toggle="modal" data-target="#Modal<?php echo $i;?>">View</button></td>
                            </tr>

                            <?php }?>
                            </tbody>
                        </table>

                        <?php for ($k = 0; $k < count($admin_orders); $k++) {
                            $modal_id = $k;
                            $this_order_id = $admin_orders[$k]['order_id'];
                            $this_delivery_date = $admin_orders[$k]['delivery_date'];
                            $this_total_price = $admin_orders[$k]['total_price'];
                            include("modal_view_item.php");
                        }
                        ?>

                    </div>
                </div>
            </div>

            <div name="my_account_tab" class="display_none">
                <div class="tab-content">
                    <div class="align-items-center text-center">
                        <form action="" method="get" onsubmit="">
                            <h2>STEP 1: Choose date</h2><br>
                            <input type="date" id="choose_date" required><br><br>
                            <button class="btn-secondary rounded btn-sm" onclick="date_selection(<?php echo $data['admin_id'];?>);" type="button">OK</button>
                        </form>
                        <br>
                    </div>

                    <p id="list_of_orders"></p>


                </div>
            </div>

            <div name="my_account_tab" class="display_none">
                <div class="tab-content">
                    <form name="change_password_form" method="post" action="change_password.php" onsubmit="return true;">
                        <label>Email Address:</label>
                        <input name="user_email" type="email" class="form-control" placeholder="Enter your email"><br>
                        <label>Current Password:</label>
                        <input name="old_password" type="password" class="form-control" placeholder="Enter your current password"><br>
                        <label>New Password:</label>
                        <input name="new_password" type="password" class="form-control" placeholder="Enter your new password">
                        <br>
                        <button type="submit" class="btn btn-primary btn-block">Submit</button>
                    </form>
                </div>
            </div>

        </div>
    </div>

</div>

<?php require_once('../../private/shared/pages_footer.php');?>

