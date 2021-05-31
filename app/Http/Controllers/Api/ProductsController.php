<?php

namespace App\Http\Controllers\Api;

use App\Events\ImportCurrentStatus;
use App\Jobs\ImportData;
use App\Services\CsvImport;
use App\Http\Controllers\Controller;
use App\Http\Requests\PrepareCsvRequest;
use App\Http\Requests\StoreCsvRequest;
use App\Services\ProductFieldService;
use Illuminate\Http\Request;
use App\Repositories\RepositoryFactory;

class ProductsController extends Controller
{
    /**
     * @param Request $request
     * @return \App\Entities\Product|\Illuminate\Support\Collection
     */
    public function get(Request $request)
    {
        $repo = RepositoryFactory::Products();
        if ($request->id) {
            return $repo->one($request->id);
        } else {
            return $repo->all(['*'], $request->limit ?: 10);
        }
    }
}
