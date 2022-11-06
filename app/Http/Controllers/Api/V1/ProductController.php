<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\StoreRequest;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use Exception;
use GhoniJee\DxAdapter\QueryAdapter;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $data = QueryAdapter::for(Product::class, $request)->paginate($request->take ?? 10);
        return $this->responseData($data->items())
            ->success();
    }

    public function count(Request $request)
    {
        $data = QueryAdapter::for(Product::class, $request)->paginate($request->take ?? 10);
        $currentCountData = QueryAdapter::for(Product::class, $request)->count();
        return $this->responseData("{$data->total()} Produk")
            ->success();
    }

    public function store(ProductRequest $request)
    {
        try {
            DB::beginTransaction();

            $data = $request->validated();

            $store = Product::create($data);

            DB::commit();

            return $this->responseMessage("Product data success")
                ->responseData($store)
                ->success();
        } catch (\Throwable $th) {
            DB::rollBack();

            return $this->responseMessage($th->getMessage())->failed();
        }
    }

    public function show(Request $request, $id)
    {
        try {
            $store = Product::find($id);

            return $this->responseMessage("show store")
                ->responseData($store)
                ->success();
        } catch (\Throwable $th) {
            return $this->responseMessage($th->getMessage())->failed();
        }
    }

    public function findCode(Request $request, $code)
    {
        try {
            $store = Product::where('code', $code)->first();

            if ($store == null) {
                throw new Exception("product not found");
            }

            return $this->responseMessage("show store")
                ->responseData($store)
                ->success();
        } catch (\Throwable $th) {
            return $this->responseMessage($th->getMessage())->failed();
        }
    }

    public function update(ProductRequest $request, $id)
    {
        try {
            DB::beginTransaction();

            $data = $request->validated();
            $query = Product::where('id', $id);
            $update = $query->update($data);

            DB::commit();

            return $this->responseMessage("Product data success")
                ->responseData($query->first())
                ->success();
        } catch (\Throwable $th) {
            DB::rollBack();

            return $this->responseMessage($th->getMessage())->failed();
        }
    }

    public function destroy(Request $request, $id)
    {
        try {
            $query = Product::where('id', $id);
            $query->delete();
            return $this->responseMessage("Destroy data success")
                ->success();
        } catch (\Throwable $th) {
            return $this->responseMessage($th->getMessage())->failed();
        }
    }
}
