<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\ProductResource;
use App\Http\Requests\BulkUpdateProductRequest;
use App\Models\Category;
use Inertia\Inertia;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
         $products = auth()->user()
            ->products()
            ->with('category')
            ->where(function ($query) {
                if ($search = request()->search) {
                    $query->where('name', 'like', '%' . $search . '%')
                    ->orWhereHas('category', function ($query) use ($search) {
                    $query->where('name', 'like', '%' . $search . '%');
                    });
                }
            })
            ->when(!request()->query('sort_by'), function ($query) {
                $query->latest();
            })
            ->when(in_array(request()->query('sort_by'), ['name', 'price', 'weight']), function ($query) {
                $sortBy = request()->query('sort_by');
                $field = ltrim($sortBy, '-');
                $direction = substr($sortBy, 0, 1) === '-' ? 'des' : 'asc';
                $query->orderBy($field, $direction);
            })
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('Product/Index', [
            'products' => ProductResource::collection($products),
            'categories' => CategoryResource::collection(Category::orderBy('name')->get()),
            'query' => (object) request()->query()
        ]);
    }

    public function create()
    {
        return inertia('Product/Create', [
            'categories' => CategoryResource::collection(Category::orderBy('name')->get())
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request)
    {
        $request->user()->products()->create($request->all());

        return redirect()
            ->route('products.index')
            ->with('title', 'Sucesso')
            ->with('message', 'Produto cadastrado com sucesso');
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        return inertia('Product/Show', [
            'product' => ProductResource::make($product)
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        return inertia('Product/Edit', [
            'categories' => CategoryResource::collection(Category::orderBy('name')->get()),
            'product' => ProductResource::make($product)
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, Product $product)
    {
        $product->update($request->validated());

        return redirect()
            ->route('products.index')
            ->with('title', 'Sucesso')
            ->with('message', 'Produto atualizado com sucesso');
    }

    /**
     * Update the specified resource in storage.
     */
    public function bulkUpdate(BulkUpdateProductRequest $request)
    {
        Product::whereIn('id', $request->product_ids)
            ->update([
                'category_id' => $request->category_id
            ]);

        return redirect()
            ->route('products.index')
            ->with('title', 'Sucesso')
            ->with('message', 'Produtos selecionados atualizados com sucesso');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()
            ->route('products.index')
            ->with('title', 'Sucesso')
            ->with('message', 'Produto deletado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function bulkDestroy(string $ids)
    {
        $ids = explode(',', $ids);
        Product::destroy($ids);

        return redirect()
            ->route('products.index')
            ->with('title', 'Sucesso')
            ->with('message', 'Produtos selecionados deletados com sucesso');
    }
}
