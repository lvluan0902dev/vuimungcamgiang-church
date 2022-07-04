<?php
namespace App\Traits;

trait CheckStatusTrait {
    public function checkStatus($status)
    {
        if (isset($status) && !empty($status) && $status == 'on')
        {
            return 1;
        }
        else {
            return 0;
        }
    }
}
