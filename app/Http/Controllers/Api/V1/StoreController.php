<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\StoreRequest;
use App\Http\Controllers\Controller;
use GhoniJee\DxAdapter\QueryAdapter;
use Illuminate\Support\Facades\Auth;

class StoreController extends Controller
{
    public function index(Request $request)
    {
        $data = QueryAdapter::for(Store::class, $request);

        return $this->responseData($data->paginate($request->take))
            ->success();
    }

    /**
     * @var \App\Models\User $user
     */
    public function store(StoreRequest $request)
    {
        try {
            DB::beginTransaction();

            $data = $request->validated();

            // @var \App\Models\User $user
            $user = Auth::user();

            $store = $user->stores()->create($data);

            DB::commit();

            return $this->responseMessage("Store data success")
                ->responseData($store)
                ->success();
        } catch (\Throwable $th) {
            DB::rollBack();

            return $this->responseMessage($th->getMessage())->failed();
        }
    }

    public function userMainStore(Request $request)
    {
        try {
            $user = Auth::user();

            $store = $user->mainStore;

            return $this->responseMessage("Main store")
                ->responseData($store)
                ->success();
        } catch (\Throwable $th) {
            return $this->responseMessage($th->getMessage())->failed();
        }
    }

    public function show(Request $request, $id)
    {
        try {
            $store = Store::find($id);

            return $this->responseMessage("show store")
                ->responseData($store)
                ->success();
        } catch (\Throwable $th) {
            return $this->responseMessage($th->getMessage())->failed();
        }
    }

    public function update(StoreRequest $request, $id)
    {
        try {
            DB::beginTransaction();

            $data = $request->validated();
            $query = Store::where('id', $id);
            $update = $query->update($data);

            DB::commit();

            return $this->responseMessage("Store data success")
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
            $query = Store::where('id', $id);
            $query->delete();
            return $this->responseMessage("Destroy data success")
                ->success();
        } catch (\Throwable $th) {
            return $this->responseMessage($th->getMessage())->failed();
        }
    }
}
