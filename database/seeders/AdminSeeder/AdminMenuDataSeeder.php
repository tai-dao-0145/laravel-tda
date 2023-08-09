<?php

namespace Database\Seeders\AdminSeeder;

/**
 * Class MAdminMenuDataSeeder
 */
class AdminMenuDataSeeder extends BaseImportCSVSeeder
{
    /**
     * getPathFileCSV
     *
     * @return string
     */
    public function getPathFileCSV(): string
    {
        return dirname(__FILE__) . '/csv/admin_menu.csv';
    }

    /**
     * getTableName
     *
     * @return string
     */
    public function getTableName(): string
    {
        return 'admin_menu';
    }

    /**
     * getConnectionName
     *
     * @return string
     */
    public function getConnectionName(): string
    {
        return 'mysql';
    }
}
