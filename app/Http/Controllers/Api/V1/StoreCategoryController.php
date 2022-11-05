<?php

namespace App\Http\Controllers\Api\V1;

use App\Actions\Users\SetupRolePermission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\StoreRequest;
use App\Http\Controllers\Controller;
use App\Models\StoreCategory;
use GhoniJee\DxAdapter\QueryAdapter;
use Illuminate\Support\Facades\Auth;

class StoreCategoryController extends Controller
{
    public function index(Request $request)
    {
        $data = QueryAdapter::for(StoreCategory::class, $request)->paginate($request->take ?? 10);
        return $this->responseData($data->items())
            ->success();
    }
}
