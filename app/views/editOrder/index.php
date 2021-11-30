<html>
<head>
    <link rel="stylesheet" href="/pw/pizza/mvc/public/bootstrap.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="/pw/pizza/mvc/app/views/editOrder/editOrder.js"></script>
    <script src="/pw/pizza/mvc/public/sweetalert2.min.js"></script>
    <link rel="stylesheet" href="/pw/pizza/mvc/public/sweetalert2.min.css">
</head>
<body>
<div class="container background">
    <div class="row">
        <div class="col-xs-10 col-xs-offset-1">
            <div class="container content">
                <div class="page-header">
                    <h1>Pizza</h1>
                </div>
                <div class="row">
                    <div class="col-xs-12">
                        <h2>Edit order <?= $data['orderId'] ?></h2>
                    </div>
                </div>
                <div class="row form">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Toppings:
                                <select id="sliceCount">
                                    <option value="2">2</option>
                                    <option value="1">1</option>
                                </select></label>
                            <label class="label-inline">Slices:
                                <select id="slices">
                                    <option value="8">8</option>
                                    <option value="6">6</option>
                                </select>
                            </label>
                            <button id="addPizza" class="submit-inline btn btn-primary">Add pizza</button>
                        </div>
                    </div>
                </div>
                <div id="table" class="container"></div>
                <div class="row form">
                    <br>
                    <div class="col-md-12">
                        <form action="/pw/pizza/mvc/public/orders" method="post">
                            <input type="submit" class="btn btn-primary" name="goBack" value="Go back">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>