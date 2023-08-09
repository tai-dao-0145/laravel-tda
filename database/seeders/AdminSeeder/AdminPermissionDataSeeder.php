<?php

namespace Database\Seeders\AdminSeeder;

/**
 * Class MAdminPermissionDataSeeder
 */
class AdminPermissionDataSeeder extends BaseImportCSVSeeder
{
    /**
     * getPathFileCSV
     *
     * @return string
     */
    public function getPathFileCSV(): string
    {
        return dirname(__FILE__) . '/csv/admin_permissions.csv';
    }

    /**
     * getTableName
     *
     * @return string
     */
    public function getTableName(): string
    {
        return 'admin_permissions';
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
