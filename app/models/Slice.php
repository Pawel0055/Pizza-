<?php
require_once 'Database.php';

class Slice extends Database
{
    public function __construct()
    {
        parent::__construct();
    }

    public function addNew($pizzaId)
    {
        $query = $this->conn->prepare('INSERT INTO slice (pizzaId) VALUES (?);');
        $query->execute(array($pizzaId));
    }

    // Check if slice if from order
    public function isFromOrder($sliceId, $orderId)
    {
        $query = $this->conn->prepare('SELECT orderId FROM pizza
            WHERE id = (SELECT pizzaId FROM slice WHERE id = ?);');
        $query->execute(array($sliceId));
        $result = $query->fetch(PDO::FETCH_ASSOC);
        
        if ($result['orderId'] == $orderId) {
            $isValid = TRUE;
        } else {
            $isValid = FALSE;
        }
        return $isValid;
    }

    // Delete all slices from given pizza
    public function deleteFromPizza($pizzaId)
    {
        $query = $this->conn->prepare('DELETE FROM slice WHERE pizzaId = ?;');
        $query->execute(array($pizzaId));
    }

    // Update slice details
    public function update($sliceId, $toppingId, $nickname)
    {
        $query = $this->conn->prepare('UPDATE slice SET toppingId = ?, nickname = ? WHERE id = ?;');
        $query->execute(array($toppingId, $nickname, $sliceId));
    }

    public function getToppingNickname($sliceId)
    {
        $query = $this->conn->prepare('SELECT toppingId, nickname FROM slice WHERE id = ?;');
        $query->execute(array($sliceId));
        $result = $query->fetch(PDO::FETCH_ASSOC);
        return $result;
    }
}