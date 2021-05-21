<?php

namespace App\Jobs;

use App\Services\CsvImport;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ImportData implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $first_is_header;
    protected $file_link;
    protected $mapping;

    /**
     * ImportData constructor.
     * @param array $mapping
     * @param string $file_link
     * @param bool $first_is_header
     */
    public function __construct(array $mapping, string $file_link, bool $first_is_header = false)
    {
        $this->first_is_header = $first_is_header;
        $this->file_link = $file_link;
        $this->mapping = $mapping;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $csvImport = new CsvImport(null, $this->first_is_header);
        $csvImport->setLink( $this->file_link );
        $csvImport->storeProducts( $this->mapping, $this->job->getJobId() );
    }
}
