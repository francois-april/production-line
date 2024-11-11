<?php

namespace App\Livewire;

use App\Models\Product;
use App\Models\ProductType;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class OrderItemForm extends Component
{

    public $orderItemCount = 0;
    public $productType = null;
    public $products = null;
    public $canAddItem = false;
 
    public function increment(): void
    {
        $this->orderItemCount++;
    }

    public function updatedProductType (): void {
        $this->products = Product::where('product_type_id', $this->productType)->get();
        $this->canAddItem = true;
    }

    /**
     * @return View
     */
    public function render(): View
    {   
        $productTypes = ProductType::all();
        return view('livewire.order-item-form', ['productTypes' => $productTypes, 'canAddItem' => $this->canAddItem]);
    }
}
