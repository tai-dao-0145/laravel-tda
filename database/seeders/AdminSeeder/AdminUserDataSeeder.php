<?php

namespace Database\Seeders\AdminSeeder;

/**
 * Class MAdminUserDataSeeder
 */
class AdminUserDataSeeder extends BaseImportCSVSeeder
{
    /**
     * getPathFileCSV
     *
     * @return string
     */
    public function getPathFileCSV(): string
    {
        return dirname(__FILE__) . '/csv/admin_users.csv';
    }

    /**
     * getTableName
     *
     * @return string
     */
    public function getTableName(): string
    {
        return 'admin_users';
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
