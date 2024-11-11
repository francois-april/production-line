<div class="container-fluid">
    <label for="product-type-select" class="form-label">Product Type</label>
    <select class="form-select product-type-select" name="productType" wire:model.change="productType">
        <option hidden selected>Choose a product type</option>
        @foreach ($productTypes as $productType)
            <option value="{{ $productType->id }}">{{ $productType->name }}</option>
        @endforeach
    </select>
    @error('productType')
        <div class="text-danger small">
            {{ $message }}
        </div>
    @enderror
    <br>
    @for ($i = 0; $i < $orderItemCount; $i++)
        <div class="border rounded">
            <label for="product-select" class="form-label">Product</label>
            <select class="form-select product-select" name="items[{{ $i }}][product]">
                <option hidden selected>Choose a product</option>
                @foreach ($products as $product)
                    <option value="{{ $product->id }}">{{ $product->name }}</option>
                @endforeach
            </select>
            <br>
            <label for="quantity" class="form-label">Quantity</label>
            <input value="{{ old('name') }}" 
                type="number" 
                class="form-control"
                name="items[{{ $i }}][quantity]"
                placeholder="Quantity" required>
            @error('items.*')
                <div class="text-danger small">
                    {{ $message }}
                </div>
            @enderror
        </div>
        <br>
    @endfor

    
    <button type="button" wire:click="increment" class="btn btn-{{ $canAddItem ? 'success' : 'secondary disabled' }} " id="add-item">Add item to order</button>
</div>