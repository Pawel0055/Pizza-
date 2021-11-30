<?php
require_once 'Database.php';
require_once 'Slice.php';

class Pizza extends Database
{
    public function __construct()
    {
        parent::__construct();
    }

    // Insert new pizza into database
    public function addNew($orderId, $slicesCount, $slices)
    {
        $query = $this->conn->prepare('INSERT INTO pizza (orderId, sliceCount, toppingCount)
            VALUES (?, ?, ?);');
        $query->execute(array($orderId, $slices, $slicesCount));
        $pizzaId = $this->conn->lastInsertId();
        $slice = new Slice();
        for ($i = 0; $i < $slicesCount; $i++) {
            $slice->addNew($pizzaId);
        }
    }

    // Check if pizza is from given order
    public function isFromOrder($pizzaId, $orderId)
    {
        $query = $this->conn->prepare('SELECT orderId FROM pizza WHERE id = ?;');
        $query->execute(array($pizzaId));
        $result = $query->fetch(PDO::FETCH_ASSOC);
        if ($result['orderId'] == $orderId) {
            $isValid = TRUE;
        } else {
            $isValid = FALSE;
        }
        return $isValid;
    }

    // Get table with slice count for each pizza
    public function getSliceCountTable()
    {
        $query = $this->conn->prepare('SELECT pizzaId, COUNT(*) AS `count` FROM slice GROUP BY pizzaId;');
        $query->execute();
        $result = $query->fetchAll();
        return $result;
    }

    // Delete pizza form database
    public function delete($id)
    {
        // Delete slices from slices table
        $slice = new Slice();
        $slice->deleteFromPizza($id);

        // Delete pizza from pizzas table
        $query = $this->conn->prepare('DELETE FROM pizza WHERE id=?');
        $query->execute(array($id));
    }
}