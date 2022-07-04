<?php
namespace App\Traits;

trait CheckOrderTrait {
    public function checkOrder($order) {
        if (!isset($order) || empty($order) || !is_numeric($order))
        {
            return 0;
        }
        else {
            return $order;
        }
    }
}
