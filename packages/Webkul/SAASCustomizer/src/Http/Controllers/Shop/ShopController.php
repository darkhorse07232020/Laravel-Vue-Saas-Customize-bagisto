<?php

namespace Webkul\SAASCustomizer\Http\Controllers\Shop;

use Illuminate\Http\Request;
use Webkul\SAASCustomizer\Repositories\ProductRepository;
use Webkul\SAASCustomizer\Http\Controllers\Controller;

class ShopController extends Controller
{
    /**
     * ProductRepository Object
     */
    protected $productRepository;

    public function __construct(
        ProductRepository $productRepository
    )   {
        $this->productRepository = $productRepository;
    }

    public function getCategoryProducts($categoryId)
    {
        $products = $this->productRepository->getAll($categoryId);

        $productItems = $products->items();
        $productsArray = $products->toArray();

        if ($productItems) {
            $formattedProducts = [];

            foreach ($productItems as $product) {
                array_push($formattedProducts, app('Webkul\Velocity\Helpers\Helper')->formatProduct($product));
            }

            $productsArray['data'] = $formattedProducts;
        }

        return response()->json($response ?? [
            'products'       => $productsArray,
            'paginationHTML' => $products->appends(request()->input())->links()->toHtml(),
        ]);
    }
}
