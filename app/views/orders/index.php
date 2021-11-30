<html>
<head>
    <link rel="stylesheet" href="/pw/pizza/mvc/public/bootstrap.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="/pw/pizza/mvc/app/views/orders/orders.js"></script>
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
                    <div class="col-xs-5 col-xs-offset-4">
                        <h2 class="table-caption">Current orders</h2>
                    </div>
                </div>
                <div class="container test">
                    <div class="row table-head">
                        <div class="col-xs-2">ID</div>
                        <div class="col-xs-4">Date</div>
                        <div class="col-xs-3">Deadline</div>
                        <div class="col-xs-3">Pizzas</div>
                    </div>
                    <div id="table"></div>
                </div>
                <div class="row form">
                    <div class="col-xs-12">
                        <div class="form-group">
                            Deadline in <input id="hours" type="number" name="hours" min="1" value="1">
                            hour(s)
                            <button id="newOrder" class="btn btn-primary">New order</button>
                        </div>
                    </div>
                </div>
                <div class="row form">
                    <div class="col-md-12">
                        <form action="/pw/pizza/mvc/public/expiredOrders" method="post">
                            <input type="submit" class="btn btn-primary" value="Expired orders">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>