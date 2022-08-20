<?php

namespace App\Http\Controllers\Api\V1\User;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use Exception;
use GhoniJee\DxAdapter\QueryAdapter;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    public function index(Request $request)
    {
        try {
            $data = QueryAdapter::for(Permission::class, $request)->get();

            return $this->responseData($data)->success(200);
        } catch (QueryException $th) {
            return $this->responseMessage($th->getMessage())->success($th->getCode());
        } catch (Exception $th) {
            return $this->responseMessage($th->getMessage())->success($th->getCode());
        }
    }
}
