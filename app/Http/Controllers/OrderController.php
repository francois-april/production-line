<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOrderRequest;
use App\Models\Client;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class OrderController extends Controller
{
    /**
     * @return View
     */
    public function index(): View
    {
        $orders = Order::all();
        return view('orders.index', ['orders' => $orders]);
    }

    /**
     * @return View
     */
    public function create(): View
    {
        $clients = Client::all();
        return view('orders.create', ['order' => new Order, 'clients' => $clients]);
    }

    /**
     * @return View
     */
    public function store(StoreOrderRequest $request): RedirectResponse
    {

        $validated = $request->validated();

        if ($request->validated()) {
            $order = new Order();
            $order->client_id = $request->client;
            $order->need_by_date = $request->needByDate;
            $order->product_type_id = $request->productType;
            $order->save();

            foreach ($request->items as $itemValues) {
                $orderItem = new OrderItem();
                $orderItem->order_id = $order->id;
                $orderItem->product_id = $itemValues['product'];
                $orderItem->quantity = $itemValues['quantity'];
                $orderItem->save();
            }
        }
        return redirect(action([self::class, 'create']))->with('message',"success");
    }
}
