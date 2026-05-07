@php
    $isEdit = isset($product) && $product->exists;
@endphp

<div class="grid gap-6 md:grid-cols-2">
    <div class="md:col-span-2">
        <label for="name" class="block text-sm font-medium text-syna-text mb-2">Nombre</label>
        <input id="name" name="name" type="text" value="{{ old('name', $product->name) }}" required style="width: 100%; background: var(--syna-bg); border: 1px solid var(--syna-line); border-radius: 6px; padding: 0.75rem; color: var(--syna-text); font-size: 0.875rem;">
    </div>

    <div>
        <label for="price" class="block text-sm font-medium text-syna-text mb-2">Precio</label>
        <input id="price" name="price" type="number" step="0.01" min="0" value="{{ old('price', $product->price) }}" required style="width: 100%; background: var(--syna-bg); border: 1px solid var(--syna-line); border-radius: 6px; padding: 0.75rem; color: var(--syna-text); font-size: 0.875rem;">
    </div>

    <div>
        <label for="stock" class="block text-sm font-medium text-syna-text mb-2">Stock</label>
        <input id="stock" name="stock" type="number" min="0" value="{{ old('stock', $product->stock ?? 0) }}" required style="width: 100%; background: var(--syna-bg); border: 1px solid var(--syna-line); border-radius: 6px; padding: 0.75rem; color: var(--syna-text); font-size: 0.875rem;">
    </div>

    <div>
        <label for="sku" class="block text-sm font-medium text-syna-text mb-2">SKU</label>
        <input id="sku" name="sku" type="text" value="{{ old('sku', $product->sku) }}" style="width: 100%; background: var(--syna-bg); border: 1px solid var(--syna-line); border-radius: 6px; padding: 0.75rem; color: var(--syna-text); font-size: 0.875rem;">
    </div>

    <div>
        <label for="category_id" class="block text-sm font-medium text-syna-text mb-2">Categoria</label>
        <select id="category_id" name="category_id" style="width: 100%; background: var(--syna-bg); border: 1px solid var(--syna-line); border-radius: 6px; padding: 0.75rem; color: var(--syna-text); font-size: 0.875rem;">
            <option value="">Sin categoria</option>
            @foreach($categories as $category)
                <option value="{{ $category->id }}" @selected((string) old('category_id', $product->category_id) === (string) $category->id)>
                    {{ $category->name }}
                </option>
            @endforeach
        </select>
    </div>

    <div>
        <label for="collection_id" class="block text-sm font-medium text-syna-text mb-2">Coleccion</label>
        <select id="collection_id" name="collection_id" style="width: 100%; background: var(--syna-bg); border: 1px solid var(--syna-line); border-radius: 6px; padding: 0.75rem; color: var(--syna-text); font-size: 0.875rem;">
            <option value="">Sin coleccion</option>
            @foreach($collections as $collection)
                <option value="{{ $collection->id }}" @selected((string) old('collection_id', $product->collection_id) === (string) $collection->id)>
                    {{ $collection->name }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="md:col-span-2">
        <label for="short_description" class="block text-sm font-medium text-syna-text mb-2">Descripcion corta</label>
        <textarea id="short_description" name="short_description" rows="3" style="width: 100%; background: var(--syna-bg); border: 1px solid var(--syna-line); border-radius: 6px; padding: 0.75rem; color: var(--syna-text); font-size: 0.875rem;">{{ old('short_description', $product->short_description) }}</textarea>
    </div>

    <div class="md:col-span-2">
        <label for="description" class="block text-sm font-medium text-syna-text mb-2">Descripcion</label>
        <textarea id="description" name="description" rows="6" style="width: 100%; background: var(--syna-bg); border: 1px solid var(--syna-line); border-radius: 6px; padding: 0.75rem; color: var(--syna-text); font-size: 0.875rem;">{{ old('description', $product->description) }}</textarea>
    </div>

    <div class="md:col-span-2 flex flex-wrap items-center gap-6">
        <label class="inline-flex items-center gap-2 text-sm text-syna-text cursor-pointer">
            <input type="checkbox" name="is_visible" value="1" @checked(old('is_visible', $isEdit ? $product->is_visible : true)) style="cursor: pointer;">
            Visible en tienda
        </label>

        <label class="inline-flex items-center gap-2 text-sm text-syna-text cursor-pointer">
            <input type="checkbox" name="is_featured" value="1" @checked(old('is_featured', $isEdit ? $product->is_featured : false)) style="cursor: pointer;">
            Destacado
        </label>
    </div>
</div>
