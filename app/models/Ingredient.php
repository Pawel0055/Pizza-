<?php
require_once 'Database.php';

class Ingredient extends Database
{
    public function getTable()
    {
        $query = $this->conn->prepare('SELECT * FROM ingredient ORDER BY `name`;');
        $query->execute();
        $result = $query->fetchAll();
        return $result;
    }

    public function checkIngredient($name)
    {
        $query = $this->conn->prepare('SELECT id FROM ingredient WHERE LOWER(?) = LOWER(`name`);');
        $query->execute(array($name));
        $result = $query->fetch(PDO::FETCH_ASSOC);
        if (!empty($result)) {
            return true;
        } else {
            return false;
        }
    }

    public function addIngredient($name)
    {
        $query = $this->conn->prepare('INSERT INTO ingredient (name) VALUES (?);');
        $query->execute(array($name));
    }

    public function findIds($ingredientNames)
    {
        // Create string with ingredient names separated with commas
        $param = '';
        foreach ($ingredientNames as $item) {
            $param .= '\'' . $item . '\', ';
        }
        $param = rtrim($param, ', ');

        $query = $this->conn->prepare('SELECT id FROM ingredient WHERE `name` IN (' . $param . ');');
        $query->execute();
        $result = $query->fetchAll();

        // Rewrite IDs to a new array
        $found = array();
        foreach ($result as $row) {
            array_push($found, $row['id']);
        }
        return $found;
    }
}