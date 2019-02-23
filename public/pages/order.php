<?php require_once('../../private/shared/pages_header.php');?>

    <div class="card-header text-center">
        <h1>Place your order</h1>
    </div>
    <div class="container">
        <div id="step1" align="center">
            <br><br>
            <form action="" method="get" onsubmit="">
                <h2>STEP 1: Choose date</h2><br>
                <input type="date" id="thisdate" required><br><br>
                    <button class="btn-secondary rounded btn-lg" onclick="window.location.href='#step2'" type="submit">OK</button></a>
            </form>
            <br><br><hr><br>
        </div>


        <div id="step2">
            <div align="center">
                <h2> STEP 2: Choose items</h2>
                <h4 style="color: darkred">Click items for more information and to choose quantity</h4><br>
            </div>
        <div class="row">
        <div class="col-md-4" align="center">
        <h3>MAIN</h3>
            <div>
                <img src="../img/burger.jpeg" class="orderOutline1" width="250" data-toggle="modal" data-target="#Modal1"/>
                <?php
                $title="Beef burger with chips";
                $price=3.50;
                $des="This is a description of this item.";
                $foodID="burgerQuantity";
                $modal_id = 1;
                include("modal.php"); ?>
            </div>
            <br>


            <div>
                <img src="../img/koleno.jpeg" style=" border:10px solid cornflowerblue; border-radius: 50%" width="250" data-toggle="modal" data-target="#Modal2"/>
                <?php
                $title="Koleno";
                $price=2.70;
                $des="This is a description of this item.";
                $foodID="kolenoQuantity";
                $modal_id = 2;
                include("modal.php"); ?>
            </div>
            <br>


            <div>
                <img src="../img/lamb.jpeg" style=" border:10px solid cornflowerblue; border-radius: 50%" width="250" data-toggle="modal" data-target="#Modal3"/>
                <?php
                $title="Lamb";
                $price=2.50;
                $des="This is a description of this item.";
                $foodID="lambQuantity";
                $modal_id = 3;
                include("modal.php"); ?>
            </div>
        </div>



        <div class="col-md-4" align="center">
            <h3>DESSERTS</h3>
            <div>
                <img src="../img/fruit.jpg" style="border:10px solid darkslateblue; border-radius: 50%" width="250" data-toggle="modal" data-target="#Modal4"/>
                <?php
                $title="Fruit Snack (80g)";
                $price=1.80;
                $des="This is a description of this item.";
                $foodID="fruitQuantity";
                $modal_id = 4;
                include("modal.php"); ?>
            </div>
            <br>


            <div>
                <img src="../img/rvCake.jpeg" style="border:10px solid darkslateblue; border-radius: 50%" width="250" data-toggle="modal" data-target="#Modal5"/>
                <?php
                $title="Red velvet Cake";
                $price=2.00;
                $des="This is a description of this item.";
                $foodID="rvQuantity";
                $modal_id = 5;
                include("modal.php"); ?>
            </div>
            <br>


            <div>
                <img src="../img/cake.jpeg" style="border:10px solid darkslateblue; border-radius: 50%" width="250" data-toggle="modal" data-target="#Modal6"/>
                <?php
                $title="Strawberry cake";
                $price=2.00;
                $des="This is a description of this item.";
                $foodID="cakeQuantity";
                $modal_id = 6;
                include("modal.php"); ?>
            </div>
        </div>



        <div class="col-md-4" align="center">
            <h3>DRINK</h3>
            <div>
                <img src="../img/water.jpg" style="border:10px solid slategrey; border-radius: 50%" width="250" data-toggle="modal" data-target="#Modal7"/>
                <?php
                $title="Water (100ml)";
                $price=0.70;
                $des="This is a description of this item.";
                $modal_id = 7;
                include("modal.php"); ?>
            </div>
            <br>


            <div>
                <img src="public/img/Apple%20juice.jpeg" style="border:10px solid slategrey; border-radius: 50%" width="250" data-toggle="modal" data-target="#Modal8"/>
                <?php
                $title="Innocent Apple juice (100ml)";
                $price=1.20;
                $des="This is a description of this item.";
                $modal_id = 8;
                include("modal.php"); ?>
            </div>
            <br>


            <div>
                <img src="../img/coke.jpg" style="border:10px solid slategrey; border-radius: 50%" width="250" data-toggle="modal" data-target="#Modal9"/>
                <?php
                $title="Coke (330ml)";
                $price=1.20;
                $des="This is a description of this item.";
                $modal_id = 9;
                include("modal.php"); ?>
            </div>
            <br>
        </div>
        </div>




<!--        <form action="" method="get">-->
<!--            <button class="btn-block btn-lg btn-secondary rounded" type="submit" data-toggle="modal" data-target="#secondModal">Add to basket</button>-->
            <!--                Modal -->
<!--            <div id="secondModal" class="modal fade" role="dialog">-->
<!--                <div class="modal-dialog modal-sm">-->
                    <!-- Modal content-->
<!--                    <div class="modal-content">-->
<!--                        <div class="modal-header">-->
<!--                            <h4 class="modal-title">Success!</h4>-->
<!--                            <button type="button" class="close" data-dismiss="modal">&times;</button>-->
<!--                        </div>-->
<!--                        <div class="modal-body">-->
<!--                            <p>Order on (date) has successfully added to basket!</p>-->
<!--                        </div>-->
<!--                        <div class="modal-footer">-->
<!--                            <button type="submit" class="btn btn-primary rounded" data-dismiss="modal" id="refresh">Order More</button>-->
<!--                            <button type="submit" class="btn btn-secondary rounded" data-dismiss="modal" id="toBasket">View Basket</button>-->
<!--                        </div>-->
<!--                    </div>-->
<!--                </div>-->
<!--            </div>-->
<!--        </form>-->
<!--</div>-->
            <br><br><hr><br>
        </div>


        <div id="step3" align="center">
            <div align="center">
                <form action="" method="get" onsubmit="">
                    <!--                FORM REQUIRED???-->
                    <h2>STEP 3: View Basket</h2><br><br>

                    <table style="width:100%">
                        <tr style="font-size:2.5rem; background-color: lightgrey">
                            <th style="padding: 15px; text-align: center">DATE</th>
                            <th style="padding: 15px; text-align: center">ITEMS</th>
                            <th style="padding: 15px; text-align: center">QUANTITY</th>
                        </tr>
                        <tr style="font-size:2.0rem; text-align: center">
                            <td style="padding: 15px; border-bottom: 1px solid darkgrey">aaa</td>
                            <td style="padding: 15px; border-bottom: 1px solid darkgrey">bbb</td>
                            <td style="padding: 15px; border-bottom: 1px solid darkgrey">ccc</td>
                        </tr>
                    </table>
                </form>
                <br><br><br>
            </div>

            <div align="right">
                <button type="submit" class="btn-secondary rounded btn-lg">Proceed payment</button>
                <br><br><br><br><br>
            </div>

        </div>

    </div>
<?php require_once('../../private/shared/pages_footer.php');?>