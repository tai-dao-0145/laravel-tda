<?php

namespace Database\Seeders\AdminSeeder;

use Carbon\Carbon;
use Exception;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

/**
 * Class BaseImportCSVSeeder
 */
abstract class BaseImportCSVSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     *
     * @throws Exception
     */
    public function run(): void
    {
        try {
            $filePath = $this->getPathFileCSV();
            $table = $this->getTableName();
            $connection = $this->getConnectionName();

            $this->processInsert($filePath, $table, $connection);
        } catch (Exception $exception) {
            Log::error('File [' . $this->getTableName() . ']: ' . $exception->getMessage());

            if (config('app.env') === 'testing') {
                throw $exception;
            }
        }
    }

    /**
     * processInsert
     *
     * @param string $filePath   : filePath
     * @param string $table      : table
     * @param string $connection : connection
     * @return void
     * @throws Exception
     */
    private function processInsert(string $filePath, string $table, string $connection): void
    {
        if (!file_exists($filePath)) {
            return;
        }
        $columns = Schema::connection($connection)->getConnection()->getDoctrineSchemaManager()->listTableColumns($table);

        $file = fopen($filePath, "r");
        $row = 0;
        $rowHeader = 0;
        $dataHeader = [];

        try {
            if (config('app.env') === 'testing') {
                DB::connection($connection)->unprepared('ALTER TABLE ' . $table . ' DISABLE TRIGGER all');
            }

            while (!feof($file)) {
                $dataRow = fgetcsv($file);
                if ($row == $rowHeader) {
                    if (in_array('id', $dataRow)) {
                        $dataHeader = $dataRow;
                    } else {
                        $data = $this->convertToDataInsert($dataHeader, $columns, $dataRow);
                        if (count($data) != 0) {
                            DB::connection($connection)->table($table)->insert($data);
                        }
                    }
                } elseif ($row > $rowHeader) {
                    $data = $this->convertToDataInsert($dataHeader, $columns, $dataRow);
                    if (count($data) != 0) {
                        DB::connection($connection)->table($table)->insert($data);
                    }
                }
                $row++;
            }

            fclose($file);
        } finally {
            if (config('app.env') === 'testing') {
                DB::connection($connection)->unprepared('ALTER TABLE ' . $table . ' ENABLE TRIGGER all');
            }
        }
    }

    /**
     * convertToDataInsert
     *
     * @param array      $listColumn     : list_column will insert into database
     * @param array      $listColumnType : list type of column in database
     * @param bool|array $dataRow        : data in file csv (1 row)
     * @return array
     * @throws Exception
     */
    private function convertToDataInsert(
        array $listColumn = [],
        array $listColumnType = [],
        bool|array $dataRow = false
    ): array {
        if (!$dataRow) {
            return [];
        }
        $data = [];
        if (count($listColumn) != 0) {
            if (count($listColumn) !== count($dataRow)) {
                throw new Exception(
                    'Data not enough:'
                    . ' [Length column in DB - ' . count($listColumn) . '] ,'
                    . ' [Data in csv - ' . count($dataRow) . ']'
                    . ' [Data in row - ' . json_encode($dataRow) . ']'
                );
            }
            foreach ($listColumn as $index => $column) {
                $column = trim($column);
                $key = $column;
                if ($key == 'order') {
                    $key = '`order`';
                }
                if (isset($listColumnType[$key])) {
                    $data[$column] = $this->convertToType(
                        $dataRow[$index],
                        $listColumnType[$key]->getType()->getName()
                    );
                } else {
                    throw new Exception('This column not exist: ' . $column);
                }
            }
        } else {
            if (count($listColumnType) !== count($dataRow)) {
                throw new Exception(
                    'Data not enough:'
                    . ' [Length column in DB - ' . count($listColumnType) . '] ,'
                    . ' [Data in csv - ' . count($dataRow) . ']'
                    . ' [Data in row - ' . json_encode($dataRow) . ']'
                );
            }
            $index = 0;
            foreach ($listColumnType as $key => $columnType) {
                $column = $key;
                if ($column == '`order`') {
                    $column = 'order';
                }
                $data[$column] = $this->convertToType($dataRow[$index], $columnType->getType()->getName());
                $index++;
            }
        }

        return $data;
    }

    /**
     * convertToType
     *
     * @param mixed|null  $data : data
     * @param string|null $type : type in database (int8,varchar,bool,timestamp...)
     * @return Carbon|mixed|null
     * @throws Exception
     */
    private function convertToType(mixed $data = null, string $type = null)
    {
        if ($type === null) {
            return $data;
        }
        if ($data === null) {
            return $data;
        }
        if ($data === '\N') {
            return null;
        }
        switch ($type) {
            case 'time':
            case 'date':
            case 'datetime':
                if ($data === '') {
                    throw new Exception('Datetime can\'t be empty');
                }
                return Carbon::parse($data);
            case 'smallint':
            case 'bigint':
            case 'integer':
            case 'float':
            case 'boolean':
            case 'string':
            case 'text':
            case 'json':
                return $data;
            default:
                throw new Exception('Type undefined: ' . $type);
        }
    }

    /**
     * getPathFileCSV
     *
     * @return string
     */
    abstract function getPathFileCSV(): string;

    /**
     * getTableName
     *
     * @return string
     */
    abstract function getTableName(): string;

    /**
     * getConnectionName
     *
     * @return string
     */
    abstract function getConnectionName(): string;
}
