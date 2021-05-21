<?php


namespace App\Services;


use App\Events\ImportCurrentStatus;
use App\Repositories\RepositoryFactory;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CsvImport
{
    /**
     * @var bool
     */
    protected $first_is_header;
    /**
     * @var string
     */
    protected $file_link;
    /**
     * @var array
     */
    protected $file_columns;

    /**
     * CsvImport constructor.
     * @param UploadedFile|null $file
     * @param bool $first_is_header
     */
    public function __construct(UploadedFile $file = null, bool $first_is_header = false)
    {
        $this->first_is_header = $first_is_header;
        if ($file) {
            $this->setLink(date('Ymd_His') . '_' . Str::random(12) . '.csv');
            $file->storeAs('temp_csv', $this->file_link);
        }
    }

    /**
     * @param string $link
     */
    public function setLink(string $link)
    {
        $this->file_link = $link;
    }

    /**
     * @param Collection $columns
     * @return Collection
     */
    public function setInitialMap(Collection $columns)
    {
        $file_columns = $this->getColumns();
        foreach ($columns as $i => $col) {
            $col['map_index'] = isset($file_columns[$i]) ? $i : -1;
        }
        return $columns;
    }

    /**
     * @return array
     */
    public function getColumns()
    {
        if (!$this->file_columns) {
            $this->buildColumns();
        }
        return $this->file_columns;
    }

    /**
     *
     */
    protected function buildColumns()
    {

        $this->file_columns = [];
        $csv = new \SplFileObject(storage_path("app/temp_csv/" . $this->file_link));

        while (!$csv->eof()) {
            $row = $csv->fgetcsv();
            if ($row) {
                $this->file_columns = $this->setFileColumns($this->file_columns, $row, $this->first_is_header);
            }
        }
    }

    /**
     * @param array $file_columns
     * @param array $row
     * @param bool $first_is_header
     * @return array
     */
    protected function setFileColumns(array $file_columns, array $row, bool $first_is_header = false)
    {
        $len = count($file_columns);
        $new_columns = $file_columns;
        foreach ($row as $idx => $item) {
            if ($idx >= $len) {
                $len++;
                $new_columns[] = $first_is_header && $item
                    ? substr($item, 0, 128)
                    : 'Column ' . $len;
            }
        }
        return $new_columns;
    }

    /**
     * @param array $product_mapping
     * @param int|null $job_id
     */
    public function storeProducts(array $product_mapping, int $job_id = null)
    {
        $repo = RepositoryFactory::Products();

        $csv = new \SplFileObject(storage_path("app/temp_csv/" . $this->file_link));
        $csv->seek(PHP_INT_MAX);
        $lines = $csv->key() + 1;
        $csv->rewind();

        $bulk_length = 100;
        for ($i = 0; ($i * $bulk_length) < $lines; $i++) {

            sleep(1);//Just for viewing the progress bar on front-end

            $pack = [];
            for ($j = 0; $j < $bulk_length; $j++) {
                $row = $csv->fgetcsv();
                if ($csv->eof()) {
                    break;
                }

                if ($i == 0 && $j == 0 && $this->first_is_header) {
                    continue;
                }

                $pack[] = $this->getProductRow($row, $product_mapping);
            }

            $repo->insertMass($pack);
            if ($job_id) {
                $percent = ( ( ($i+1) * $bulk_length) / $lines) * 100;
                event(new ImportCurrentStatus($job_id, $percent));
            }
        }

        unset($csv);
        Storage::delete("temp_csv/" . $this->file_link);
    }

    /**
     * @param array $row
     * @param array $mapping
     * @return array
     */
    protected function getProductRow(array $row, array $mapping)
    {
        $product = [];
        foreach ($mapping as $map) {
            $value = $row[$map['map_index']] ?? '';
            switch ($map['type'] ?? 'string') {
                case 'integer':
                    $value = intval($value);
                    break;
                case 'float':
                    $value = floatval($value);
                    break;
                case 'string':
                    $value = substr($value, 0, 255);
                    break;
            }
            $product[$map['field']] = $value;
        }
        return $product;
    }

    /**
     * @return string
     */
    public function getFileLink()
    {
        return $this->file_link;
    }
}