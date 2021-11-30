<?php

class EditOrder extends Controller
{
    private $orderId;

    public function __construct()
    {
        session_start();
        $this->orderId = $_SESSION['orderId'];
    }

    public function index()
    {
        $this->view('editOrder/index', array('orderId' => $this->orderId));
    }

    public function addPizza()
    {
        $sliceCount = $_POST['sliceCount'];
        $slices = $_POST['slices'];
        $pizza = $this->model('Pizza');
        $order = $this->model('Order');
        if ($order->notExpired($this->orderId)) {
            $pizza->addNew($this->orderId, $sliceCount, $slices);
        } else {
            echo json_encode('error');
        }
    }

    public function edit($id)
    {
        $slice = $this->model('Slice');
        $sliceId = $id;
        if ($slice->isFromOrder($sliceId, $this->orderId)) {
            $_SESSION['sliceId'] = $sliceId;
            header('Location: ../../editSlice/');
        }
    }

    public function deletePizzaJS()
    {
        $pizza = $this->model('Pizza');
        $id = $_POST['id'];
        if ($pizza->isFromOrder($id, $this->orderId)) {
            $pizza->delete($id);
        }
    }

    public function refreshAjax(){
        $order = $this->model('Order');
        $result = $order->getDataForTable($this->orderId);
        echo json_encode($result);
    }
}