<?php


namespace App\Services;


use App\Models\DbProduct;
use App\Entities\ProductField;
use App\Repositories\RepositoryFactory;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use function PHPSTORM_META\map;

class ProductFieldService
{
    protected $dbTable;
    protected $fieldRepository;

    /**
     * ProductFieldService constructor.
     */
    public function __construct()
    {
        $this->dbTable = (new DbProduct())->getTable();
        $this->fieldRepository = RepositoryFactory::ProductFields();
    }

    /**
     * @param array $column_settings
     * @return array
     */
    public function filterMapping(array $column_settings)
    {
        return collect($column_settings)
            ->where('name', '!=', '')
            ->where('map_index', '>', -1)
            ->map(function ($el) {
                $el['field'] = $this->fieldRepository->makeDbField($el['name']);
                return $el;
            })
            ->toArray();
    }

    /**
     * @param array $column_settings
     */
    public function addAndUpdateColumns(array $column_settings)
    {
        $present_columns = $this->fieldRepository->all();
        foreach ($column_settings as $setting) {
            if (!empty($setting['name'])) {
                $db_field = $this->fieldRepository->makeDbField($setting['field'] ?? $setting['name']);
                $found = $present_columns->where('field', '=', $db_field)->first();
                if ($found) {
                    $this->checkAndChangeColumn($found, $setting);
                } else {
                    $this->createNewColumn($setting);
                }
            }
        }
    }

    /**
     * @param ProductField $field
     * @param array $new_settings
     */
    protected function checkAndChangeColumn(ProductField $field, array $new_settings)
    {
        $changed_name = $field->name != $new_settings['name'];
        $changed_type = $field->type != $new_settings['type'] ?? 'string';
        if ($changed_type) {
            $this->changeDbColumn($new_settings);
        }
        if ($changed_type || $changed_name) {
            $this->fieldRepository->update($field->id, $new_settings);
        }
    }

    /**
     * @param array $new_settings
     */
    protected function changeDbColumn(array $new_settings)
    {
        Schema::table($this->dbTable, function (Blueprint $table) use ($new_settings) {
            $db_field = $this->fieldRepository->makeDbField($new_settings['field']);
            $t = $this->defineColumnType($table, $db_field, $new_settings['type'] ?? 'string');
            $t->change();
        });
    }

    /**
     * @param array $new_settings
     */
    protected function createNewColumn(array $new_settings)
    {
        $this->fieldRepository->insert($new_settings);
        Schema::table($this->dbTable, function (Blueprint $table) use ($new_settings) {
            $db_field = $this->fieldRepository->makeDbField($new_settings['name']);
            $t = $this->defineColumnType($table, $db_field, $new_settings['type'] ?? 'string');
        });
    }

    /**
     * @param Blueprint $table
     * @param string $db_field
     * @param string $type
     * @return \Illuminate\Database\Schema\ColumnDefinition
     */
    protected function defineColumnType(Blueprint $table, string $db_field, string $type = 'string')
    {
        switch ($type) {
            case 'text':
                $t = $table->text($db_field);
                break;
            case 'integer':
                $t = $table->integer($db_field);
                break;
            case 'float':
                $t = $table->float($db_field);
                break;
            case 'string':
            default:
                $t = $table->string($db_field, 255);
        }

        $t->default(null);
        $t->nullable();

        return $t;
    }

}