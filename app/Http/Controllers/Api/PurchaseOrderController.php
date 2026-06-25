<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiBaseController;
use App\Http\Requests\Api\PurchaseOrder\DeleteRequest;
use App\Http\Requests\Api\PurchaseOrder\IndexRequest;
use App\Http\Requests\Api\PurchaseOrder\StoreRequest;
use App\Http\Requests\Api\PurchaseOrder\UpdateRequest;
use App\Models\Order;
use App\Traits\OrderTraits;

class PurchaseOrderController extends ApiBaseController
{
    use OrderTraits;

    protected $model = Order::class;

    protected $indexRequest = IndexRequest::class;
    protected $storeRequest = StoreRequest::class;
    protected $updateRequest = UpdateRequest::class;
    protected $deleteRequest = DeleteRequest::class;

    public function __construct()
    {
        parent::__construct();

        $this->orderType = "purchase-orders";
    }
}
