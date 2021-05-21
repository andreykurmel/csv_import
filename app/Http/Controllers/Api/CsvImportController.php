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

class CsvImportController extends Controller
{
    /**
     * @param PrepareCsvRequest $request
     * @return array
     */
    public function prepareCsv(PrepareCsvRequest $request)
    {
        $csvImport = new CsvImport($request->uploaded_file, !!$request->first_is_header);
        $headers = $csvImport->getColumns();
        $columns = RepositoryFactory::ProductFields()->all(['field','name','type']);

        return [
            'csv_headers' => $headers,
            'product_columns' => $csvImport->setInitialMap($columns),
            'file_link' => $csvImport->getFileLink(),
            'first_is_header' => $request->first_is_header,
        ];
    }

    /**
     * @param StoreCsvRequest $request
     * @return int
     */
    public function storeCsv(StoreCsvRequest $request)
    {
        $fieldService = new ProductFieldService();
        $fieldService->addAndUpdateColumns( $request->product_mapping );
        $completed_mapping = $fieldService->filterMapping( $request->product_mapping );

        $job = new ImportData($completed_mapping, $request->file_link, !!$request->first_is_header);
        $job_id = $this->dispatch($job);
        return config('broadcasting.default') == 'pusher' ? $job_id : 0;
    }
}
