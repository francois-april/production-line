<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Carbon\Carbon;
use Carbon\CarbonInterval;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;

class ScheduleController extends Controller
{
    /**
     * @return View
     */
    public function index(): View
    {

        $orders = Order::with('productType', 'orderItems', 'client')->orderBy('need_by_date', 'asc')->get();
        $schedule = $this->buildSchedule($orders);

        return view('schedule.index', ['schedule' => $schedule]);
    }

    /**
     * @return array<string, mixed>
     * This function builds an order schedule, prioritizing the need by date but trying to group orders with the same product type to avoid changeover delays when time allows.
     * If processing the next order with the earliest need by date would incur a changeover delay, without making it finish late, it tries to process as many orders that dont require a changeover delay as possible
     */
    private function buildSchedule(Collection $orders): Array{
        $schedule = [];
        $scheduledOrders = [];
        $currentDateTime = new Carbon('now');
        $currentTime = $currentDateTime->getTimestamp();
        //30 minutes
        $changeoverDelay = 1800;
        for ($i = 0; $i < $orders->count(); $i++) {
            if (!in_array($orders[$i]->id , $scheduledOrders)) {
                $scheduledOrders[] = $orders[$i]->id;
                $schedule = $this->addOrder($schedule, $orders[$i], $currentTime);
                $currentTime += $orders[$i]->getProductionTime();
                // if ($i == 1) {
                //     dd(in_array($orders[$i + 1]->id, $scheduledOrders));
                // }
                // $nextOrderIndex = $i + 1;
                // foreach () {
                    
                // }
                for ($j = $i + 1; $j < $orders->count(); $j++) {
                    if (isset($orders[$j]) && !in_array($orders[$j]->id, $scheduledOrders)) {
                        if ($orders[$i]->product_type_id == $orders[$j]->product_type_id) {
                            break;
                        }
                        else {
                            $earliestOrderNeedByDate = new Carbon($orders[$j]->need_by_date);
                            $earliestOrderMustEndBy = $earliestOrderNeedByDate->getTimestamp();
                            $earliestOrderMustStartBy = $earliestOrderMustEndBy - $orders[$j]->getProductionTime();
                            for ($k = $j + 1; $k < $orders->count(); $k++) {
                                if (isset($orders[$k]) && !in_array($orders[$k]->id, $scheduledOrders) && $orders[$i]->product_type_id == $orders[$k]->product_type_id) {
                                    $productionTime = $orders[$k]->getProductionTime();
                                    if ($currentTime + $productionTime + $changeoverDelay <= $earliestOrderMustStartBy) {
                                        $scheduledOrders[] = $orders[$k]->id;
                                        $schedule = $this->addOrder($schedule, $orders[$k], $currentTime);
                                        $currentTime += $productionTime;
                                    }
                                }
                            }
                            $schedule = $this->addChangeover($schedule, $currentTime);
                            $currentTime += $changeoverDelay;
                        }
                    }
                }
            }
        }

        return $schedule;
    }

    /**
     * @return array<string, mixed>
     */
    private function addOrder(array $schedule, Order $order, int $orderStartTime): array {
        $itemsArray = [];
        $itemStartTime = $orderStartTime;
        foreach ($order->orderItems as $orderItem) {
            $itemProductionTime = $orderItem->getProductionTime();
            $itemEndTime = $itemStartTime + $itemProductionTime;
            $itemsArray[] = [
                'item' => $orderItem,
                'startTime' => new Carbon($itemStartTime) ,
                'endTime' => new Carbon($itemEndTime),
                'productionTime' => CarbonInterval::create(seconds: $itemProductionTime)->cascade()
            ];
            $itemStartTime = $itemEndTime;
        }
        $orderProductionTime = $order->getProductionTime();
        $endTime = $orderStartTime + $orderProductionTime;
        $schedule[] = [
            'order' => $order,
            'items' => $itemsArray,
            'changeover' => false,
            'startTime' => new Carbon($orderStartTime),
            'endTime' => new Carbon($endTime),
            'productionTime' => CarbonInterval::create(seconds: $orderProductionTime)->cascade()
        ];

        return $schedule;
    }

    /**
     * @return array<string, mixed>
     */
    private function addChangeover(array $schedule, int $startTime, int $changeoverDelay = 1800): array {
        $endTime = $startTime + $changeoverDelay;
        $schedule[] = [
            'order' => null,
            'items' => null,
            'changeover' => true,
            'startTime' => new Carbon($startTime),
            'endTime' => new Carbon($endTime),
            'productionTime' => CarbonInterval::create(seconds: $changeoverDelay)->cascade()
        ];
        
        return $schedule;
    }
}
