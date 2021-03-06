<?php require_once($_SERVER['DOCUMENT_ROOT'] . "/COMP0034_GroupK/private/initialize.php"); ?>

<?php
if (!isset($_SESSION["staff_credential"])) {
    redirect_to(url_for('/staff/index.php'));
}else {
    require_once('../staff_header.php');

    $item_id = $_GET['item_id'] ?? '1';
    $item_data = get_data($db, $item_id, 'item', 'item_id');

    if($_SERVER["REQUEST_METHOD"] == "POST") {
        $new_item_name = NULL;
        $new_item_price = NULL;
        $new_item_desc = NULL;
        $message = "";


        if(isset($_POST['item_name']) && $_POST['item_name'] != $item_data['item_name']) {
            $new_item_name = $_POST['item_name'];
            $query = "UPDATE item SET item_name = '$new_item_name' WHERE item_id = '$item_id'";

            submit_query($db,$query);
        }
        if(isset($_POST['item_price']) && $_POST['item_price'] != $item_data['item_price']) {
            $new_item_price = $_POST['item_price'];
            $query = "UPDATE item SET item_price = '$new_item_price' WHERE item_id = '$item_id'";
            submit_query($db,$query);
        }
        if(isset($_POST['item_description']) && $_POST['item_description'] != $item_data['item_description']) {
            $new_item_desc = $_POST['item_description'];
            $query = "UPDATE item SET item_description = '$new_item_desc' WHERE item_id = '$item_id'";
            submit_query($db,$query);
        }

        if(isset($_FILES["item_image"]["name"]) ) {
            echo "upload image <br>";
            echo basename($_FILES["item_image"]["name"]);
            echo "<br>";
            $uploadOk = true;
            $target_dir = $_SERVER['DOCUMENT_ROOT'] . "/COMP0034_GroupK/public/img/menu_image/";
            $saved_src = "../../img/menu_image/" . basename($_FILES["item_image"]["name"]);
            echo $target_dir;
            $target_file = $target_dir . basename($_FILES["item_image"]["name"]);
            $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
            if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
                $message .=  "Sorry, only JPG, JPEG, PNG & GIF files are allowed. <br>";
                $uploadOk = false;
            }
            if (file_exists($target_file)) {
                $message .=  "Sorry, file already exists.<br>";
                $uploadOk = false;
            }
            if ($_FILES["item_image"]["size"] > 500000) {
                $message .=  "Sorry, your file is too large.<br>";
                $uploadOk = false;
            }

            if (!$uploadOk) {
                echo "Sorry, your file was not uploaded.";
                // if everything is ok, try to upload file
            } else {
                if (move_uploaded_file($_FILES["item_image"]["tmp_name"], $target_file)) {
                    echo "The file ". basename( $_FILES["item_image"]["name"]). " has been uploaded.";
                    $query = "UPDATE item SET item_image = '$saved_src' WHERE item_id = '$item_id'";
                    if (submit_query($db,$query)) {
                        $uploadOk = true;
                    }else {
                        $uploadOk = false;
                    }
                } else {
                    echo "Sorry, there was an error uploading your file.";
                }
            }

        }

        if ($new_item_name != NULL || $new_item_price != NULL || $new_item_desc != NULL || $uploadOk) {
            $_SESSION['message'] = "Edit item successfully!";
            redirect_to(url_for('/staff/item/index.php'));
        }else {
            $message .=  "There is no change was made!!!";
        }
    }

    ?>

    <div class="container">
        <header><strong>Edit Item</strong> with ID: <?php echo $item_id;?></header>
        <div class="text-danger">
            <p><?php echo $message ?? '';?></p>
        </div>
        <form id="edit_item_form" method="post" action="" enctype="multipart/form-data">
            <div class="form-row">
                <label class="col-md-3">Item name: </label>
                <input name="item_name" type="text" class="form-control col-md-8" value="<?php echo $item_data['item_name'];?>">

            </div>
            <br>
            <div class="form-row">
                <label class="col-md-3">Item price: </label>
                <input name="item_price" type="number" step="0.01" class="form-control col-md-8" value="<?php echo $item_data['item_price'];?>">

            </div>
            <br>
            <div class="form-row">
                <label class="col-md-3">Item Description: </label>
                <input name="item_description" class="form-control col-md-8" value="<?php echo $item_data['item_description'];?>">

            </div>
            <br>
            <div class="form-row">
                <label class="col-md-3">Item image: </label>
                <input class="form-control col-md-8" name="item_image" type="file">

            </div>
            <br>
            <div class="form-row">
                <button class="btn btn-primary btn-block" name="submit" type="submit">Edit this item</button>

            </div>
        </form>

    </div>


    <?php require_once('../staff_footer.php'); }?>
