<?php

namespace App\Http\Controllers\Api;


use App\Http\Requests\CreateProductRequest;
use App\Http\Requests\CreateProductUploadRequest;
use App\Product;
use App\Services\IProductService;
use App\Transformers\ProductTransformer;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;

class ProductController extends BaseController
{
    private $productService;

    public function __construct(IProductService $productService)
    {
        $this->productService = $productService;
    }

    public function index($perPage = 10)
    {
        $filter = request()->get('filter');

        $products = Product::search($filter)->paginate($perPage);

        $response = fractal()->collection($products)
            ->transformWith(new ProductTransformer)
            ->paginateWith(new IlluminatePaginatorAdapter($products))
            ->toArray();

        return $this->successResponse('List of products successfully retrieved', 200, $response);
    }

    public function store(CreateProductRequest $request)
    {
        $data = $request->all();

        $product = Product::create($data);

        $response = fractal()->item($product)
            ->transformWith(new ProductTransformer)
            ->toArray()['data'];

        return $this->successResponse('Product created successfully', 201,  $response);
    }

    public function upload(CreateProductUploadRequest $request)
    {
        $file = $request->file('file');

        $this->productService->importProductFromFile($file);

        return $this->successResponse('Products imported to database successfully', 201);
    }
}
