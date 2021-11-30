<?php
require_once 'Database.php';

class Order extends Database
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getOrderFromId($id)
    {
        $query = $this->conn->prepare('SELECT *, COALESCE((SELECT COUNT(*) AS `count` FROM pizza 
            GROUP BY orderId HAVING orderId = o.id), 0) as count FROM `order` o WHERE id = ?;');
        $query->execute(array($id));
        $result = $query->fetch(PDO::FETCH_ASSOC);
        return $result;   
    }
    
    // Get table of current orders
    public function getTable()
    {
        $query = $this->conn->prepare('SELECT *, COALESCE((SELECT COUNT(*) AS `count` FROM pizza 
            GROUP BY orderId HAVING orderId = o.id), 0) as count FROM `order` o WHERE deadline > NOW()
            ORDER BY id;');
        $query->execute();
        $result = $query->fetchAll();
        return $result;
    }

    // Get table of expired orders
    public function getExpiredTable()
    {
        $query = $this->conn->prepare('SELECT *, COALESCE((SELECT COUNT(*) AS `count` FROM pizza
            GROUP BY orderId HAVING orderId = o.id), 0) as count FROM `order` o WHERE deadline <= NOW()
            ORDER BY id;');
        $query->execute();
        $result = $query->fetchAll();

        return $result;
    }

    // Insert new order into database
    // returns ID of the new order
    public function addNew($deadline)
    {
        $query = $this->conn->prepare('INSERT INTO `order` (date, deadline)
            VALUES (NOW(), DATE_ADD(NOW(), INTERVAL ? HOUR));');
        $query->execute(array($deadline));
        return $this->conn->lastInsertId();
    }

    // Delete order from database
    public function delete($id)
    {
        // Delete slices from slices table
        $query = $this->conn->prepare('DELETE FROM slice WHERE pizzaId IN 
            (SELECT id FROM pizza WHERE orderId = ?);');
        $query->execute(array($id));

        // Delete pizza from pizzas table
        $query = $this->conn->prepare('DELETE FROM pizza WHERE orderId = ?;');
        $query->execute(array($id));

        // Delete order from orders table
        $query = $this->conn->prepare('DELETE FROM `order` WHERE id=?');
        $query->execute(array($id));
    }

    // Check if order is not expired
    public function notExpired($id)
    {
        $query = $this->conn->prepare('SELECT id FROM `order` WHERE id=? AND deadline > NOW();');
        $query->execute(array($id));
        if ($query->fetch(PDO::FETCH_ASSOC)) {
            return TRUE;
        } else {
            return FALSE;
        }
    }
    
    // Get summary of an order
    public function getSummaryTable($orderId)
    {
        $query = $this->conn->prepare('SELECT pizzaId, s.id AS sliceId, t.name AS topping, nickname, t.cost,
            p.name AS pizzeria, phoneNumber, address, sliceCount, toppingCount FROM slice s JOIN topping t ON s.toppingId=t.id
            JOIN pizzeria p ON t.pizzeriaId=p.id JOIN pizza ON pizza.id = pizzaId WHERE orderId = ?
            ORDER BY pizzeria, pizzaId;');
        $query->execute(array($orderId));
        $table = $query->fetchAll();
        
        return $table;
    }

    // Returns data needed to print the summary table
    public function getDataForTable($orderId)
    {
        $summaryTable = $this->getSummaryTable($orderId);

        // Array of pizzas in the order
        $pizza = array();
        foreach ($summaryTable as $key => $row) {
            // Check if slices are from the same pizzeria
            if (!isset($pizza[$row['pizzaId']]['pizzeria'])) {
                $pizza[$row['pizzaId']]['pizzeria'] = $row['pizzeria'];
                $pizza[$row['pizzaId']]['address'] = $row['address'];
                $pizza[$row['pizzaId']]['phoneNumber'] = $row['phoneNumber'];
                $pizza[$row['pizzaId']]['sliceCount'] = $row['sliceCount'];
            } else {
                if ($pizza[$row['pizzaId']]['pizzeria'] != $row['pizzeria']) {
                    $pizza[$row['pizzaId']]['pizzeria'] = 'conflict';
                    $pizza[$row['pizzaId']]['address'] = '-';
                    $pizza[$row['pizzaId']]['phoneNumber'] = '-';
                }
            }

            // Calculate cost of each slice
            if (!isset($pizza[$row['pizzaId']]['cost'])) {
                $pizza[$row['pizzaId']]['cost'] = 0;
            }
            $summaryTable[$key]['cost'] /= $row['toppingCount'];
            $pizza[$row['pizzaId']]['cost'] += $summaryTable[$key]['cost'];
        }
        unset($row);
        unset($key);

        $pizzerias = array();
        foreach ($pizza as $row) {
            // Assign pizzeria properties
            if (!isset($pizzerias[$row['pizzeria']]/*['pizzeria']*/)) {
                $pizzerias[$row['pizzeria']]['address'] = $row['address'];
                $pizzerias[$row['pizzeria']]['phoneNumber'] = $row['phoneNumber'];
            }
            // Calculate cost for each pizzeria
            if (!isset($pizzerias[$row['pizzeria']]['cost'])) {
                $pizzerias[$row['pizzeria']]['cost'] = 0;
            }
            $pizzerias[$row['pizzeria']]['cost'] += $row['cost'];
        }
        unset($row);

        $result = array('pizzerias' => $pizzerias,
            'pizza' => $pizza,
            'summaryTable' => $summaryTable);
        return $result;
    }
}