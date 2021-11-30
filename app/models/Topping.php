<?php
require_once 'Database.php';
require_once 'Ingredient.php';

class Topping extends Database
{
    // Get table with details of toppings
    public function getTable()
    {
        $query = $this->conn->prepare('SELECT t.id, t.name, cost, p.name AS pizzeria
            FROM topping t JOIN pizzeria p ON p.id=pizzeriaId ORDER BY id;');
        $query->execute();
        $result = $query->fetchAll();
        return $result;
    }

    // Get table with details of given toppings
    public function getTableFromId($id)
    {
        // Create string with ingredient IDs separated with commas
        $param = '';
        foreach ($id as $item) {
            $param .= $item . ', ';
        }
        $param = rtrim($param, ', ');

        $query = $this->conn->prepare('SELECT t.id, t.name, cost, p.name AS pizzeria
            FROM topping t JOIN pizzeria p ON p.id=pizzeriaId WHERE t.id IN (' . $param . ')');
        $query->execute();
        $result = $query->fetchAll();
        return $result;
    }

    // Get table with topping ingredients
    public function getIngredientTable()
    {
        $query = $this->conn->prepare('SELECT t.id, toppingId, ingredientId, `name`
            FROM toppingIngredient t JOIN ingredient i ON ingredientId = i.id ORDER BY id;');
        $query->execute();
        $result = $query->fetchAll();
        return $result;
    }

    // Find ID of a topping with given ingredients
    public function findTopping($ingredients)
    {
        // Create string with ingredient IDs separated with commas
        $param = '';
        foreach ($ingredients as $ingredient) {
            $param .= $ingredient . ', ';
        }
        $param = rtrim($param, ', ');
        $count = count($ingredients);

        $query = $this->conn->prepare('SELECT toppingId, COUNT(*) AS `count`
            FROM toppingIngredient WHERE ingredientId IN (' . $param . ') GROUP BY toppingId
            HAVING `count` = ?;');
        $query->execute(array($count));
        $result = $query->fetchAll();

        // Rewrite found topping IDs to a new array
        $found = array();
        foreach ($result as $row) {
            array_push($found, $row['toppingId']);
        }
        return $found;
    }

    // Check if topping exists in database
    public function checkTopping($name)
    {
        $query = $this->conn->prepare('SELECT id FROM topping WHERE LOWER(?) = LOWER(`name`);');
        $query->execute(array($name));
        $result = $query->fetch(PDO::FETCH_ASSOC);
        if (!empty($result)) {
            return true;
        } else {
            return false;
        }
    }

    // Adding topping into database
    public function addTopping($topping)
    {
        if (!$this->checkTopping($topping['name'])) {
            $query = $this->conn->prepare('INSERT INTO topping (`name`, cost, pizzeriaId) VALUES (?, ?, ?)');
            $query->execute(array($topping['name'], $topping['cost'], $topping['pizzeriaId']));

            $toppingId = $this->conn->lastInsertId();

            $ingredient = new Ingredient();

            // Add ingredients which are not in the database
            foreach ($topping['ingredients'] as $row) {
                if (!$ingredient->checkIngredient($row) && $row != '') {
                    $ingredient->addIngredient($row);
                }
            }

            // Find IDs of all topping ingredients
            $found = $ingredient->findIds($topping['ingredients']);

            foreach ($found as $item) {
                $query = $this->conn->prepare('INSERT INTO toppingIngredient (toppingId, ingredientId) VALUES (?, ?);');
                $query->execute(array($toppingId, $item));
            }

            echo 'Added: ' . $topping['name'] . ', ' . $topping['cost'] . ': ';
            foreach ($topping['ingredients'] as $item) {
                echo $item . ', ';
            }
            echo '<br>';
        }
    }
}