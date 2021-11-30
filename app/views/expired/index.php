<html>
<head>
    <link rel="stylesheet" href="/pw/pizza/mvc/public/bootstrap.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="/pw/pizza/mvc/app/views/expired/expired.js"></script>
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
                        <h2 class="table-caption">Expired orders</h2>
                    </div>
                </div>
                <div class="container test">
                    <div class="row table-head">
                        <div class="col-xs-2">ID</div>
                        <div class="col-xs-4">Date</div>
                        <div class="col-xs-4">Deadline</div>
                        <div class="col-xs-2">Pizzas</div>
                    </div>
                    <?php foreach ($data['orderList'] as $row): ?>
                        <div class="row hover click table-row">
                            <div id="id" class="col-xs-2"><?= $row['id'] ?></div>
                            <div class="col-xs-4"><?= $row['date'] ?></div>
                            <div class="col-xs-4"><?= $row['deadline'] ?></div>
                            <div class="col-xs-2"><?= $row['count'] ?></div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div id="table" class="container test"></div>
                <div class="row form">
                    <br>
                    <form action="/pw/pizza/mvc/public/orders" method="post">
                        <input type="submit" class="btn btn-primary" name="goBack" value="Go back">
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>