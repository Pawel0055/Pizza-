<html>
<head>
    <link rel="stylesheet" href="/pw/pizza/mvc/public/bootstrap.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="/pw/pizza/mvc/app/views/editSlice/editSlice.js"></script>
</head>
<body>
<?php require_once 'script.php'; ?>
<div class="container background">
    <div class="row">
        <div class="col-xs-10 col-xs-offset-1">
            <div class="container content">
                <div class="page-header">
                    <h1>Pizza</h1>
                </div>
                <div class="row">
                    <div class="col-xs-12">
                        <h2 id="toppingId" title="<?= $data['toppingId'] ?>">Edit topping:</h2>
                    </div>
                </div>
                <div class="container test">
                    <div class="row table-head">
                        <div class="col-xs-1">ID</div>
                        <div class="col-xs-3">Name</div>
                        <div class="col-xs-2">Cost</div>
                        <div class="col-xs-2">Pizzeria</div>
                        <div class="col-xs-4">Ingredients</div>
                    </div>
                    <div id="table-items">
                        <?php foreach ($toppings as $row): ?>
                            <div class="row hover click">
                                <div class="id col-xs-1" title="<?= $row['id'] ?>"><?= $row['id'] ?></div>
                                <div class="col-xs-3"><?= $row['name'] ?></div>
                                <div class="col-xs-2"><?= $row['cost'] ?> zl</div>
                                <div class="col-xs-2"><?= $row['pizzeria'] ?></div>
                                <div class="col-xs-4">
                                    <?php foreach ($ingredients as $item) {
                                        if ($item['toppingId'] == $row['id']) {
                                            echo $item['name'] . ', ';
                                        }
                                    } ?></div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="row form">
                    <div class="form-group">
                        <div class="col-xs-12">
                            <label class="label-inline">Nickname:
                                <input id="nickname" type="text" class="form-inline" name="nickname"
                                       value="<?= $data['nickname'] ?>"></label>
                            <button id="submit" class="submit-inline btn btn-primary">Submit</button>
                        </div>
                    </div>
                </div>
                <div class="row form">
                    <div class="col-xs-3">
                        <div class="form-group checkbox">
                            <?php
                            $i = 0;
                            foreach ($data['ingredients'] as $row):
                            if ($i == 10): ?>
                        </div>
                    </div>
                    <div class="col-xs-3">
                        <div class="form-group checkbox">
                            <?php $i = 0;
                            endif; ?>
                            <label>
                                <input class="checkbox-click" type="checkbox" name="ingredientList[]"
                                       value="<?= $row['id'] ?>">
                                <?= $row['name'] ?>
                            </label><br>
                            <?php $i++;
                            endforeach; ?>
                        </div>
                    </div>
                </div>
                <div class="row form">
                    <div class="col-xs-12">
                        <form action="/pw/pizza/mvc/public/editOrder" method="post">
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