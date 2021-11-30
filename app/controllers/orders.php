<?php

class Orders extends Controller
{
    public function index()
    {
        $order = $this->model('Order');
        $orderList = $order->getTable();
        $this->view('orders/index', array('orderList' => $orderList));
    }

    public function addNewAjax()
    {
        $order = $this->model('Order');
        $newId = $order->addNew($_POST['hours']);

        $newOrder = $order->getOrderFromId($newId);

        echo json_encode($newOrder);
    }

    public function editJS($id)
    {
        $order = $this->model('Order');
        if ($order->notExpired($id)) {
            session_start();
            $_SESSION['orderId'] = $id;
            header('Location: ../../editOrder/');
        } else {
            $this->index('Invalid order ID');
        }
    }

    public function deleteAjax()
    {
        $id = $_POST['id'];
        $order = $this->model('Order');
        if ($order->notExpired($id)) {
            $order->delete($id);
        }
    }

    public function refreshAjax()
    {
        $order = $this->model('Order');
        $orderList = $order->getTable();
        echo json_encode($orderList);
    }
}