<?php

class ExpiredOrders extends Controller
{
    private $orderId = 0;

    public function index()
    {
        $order = $this->model('Order');
        $orderList = $order->getExpiredTable();

        $this->view('expired/index', array(
            'orderId' => $this->orderId,
            'orderList' => $orderList));
    }

    public function viewOrderAjax()
    {
        $id = $_POST['id'];
        $order = $this->model('Order');
        $result = $order->getDataForTable($id);
        echo json_encode($result);
    }
}