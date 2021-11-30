<?php

class EditSlice extends Controller
{
    private $sliceId;

    public function __construct()
    {
        session_start();
        $this->sliceId = $_SESSION['sliceId'];
    }

    public function index($message = '')
    {
        $topping = $this->model('Topping');
        $toppingTable = $topping->getTable();
        $ingredientTable = $topping->getIngredientTable();

        $ingredient = $this->model('Ingredient');
        $ingredients = $ingredient->getTable();

        $slice = $this->model('Slice');
        $sliceInfo = $slice->getToppingNickname($this->sliceId);

        $this->view('editSlice/index', array(
            'sliceId' => $this->sliceId,
            'toppingId' => $sliceInfo['toppingId'],
            'nickname' => $sliceInfo['nickname'],
            'toppingTable' => $toppingTable,
            'ingredientTable' => $ingredientTable,
            'message' => $message,
            'ingredients' => $ingredients));
    }

    public function edit()
    {
        $toppingId = $_POST['toppingId'];
        $nickname = preg_replace('/[^A-Za-z0-9 ,]+/', '', $_POST['nickname']);
        $slice = $this->model('Slice');

        $slice->update($this->sliceId, $toppingId, $nickname);
    }

    public function checkIngredientsAjax()
    {
        $topping = $this->model('Topping');
        $ingredientTable = $topping->getIngredientTable();
        if (!empty($_POST['ingredientList'])) {
            $found = $topping->findTopping($_POST['ingredientList']);
            if (!empty($found)) {
                $foundTable = $topping->getTableFromId($found);
                $result = array('foundTable' => $foundTable,
                    'ingredientTable' => $ingredientTable);
                echo json_encode($result);
            } else {
                echo json_encode('');
            }
        } else {
            $foundTable = $topping->getTable();
            $result = array('foundTable' => $foundTable,
                'ingredientTable' => $ingredientTable);
            echo json_encode($result);
        }
    }
}