<?php

namespace Database\Seeders\AdminSeeder;

/**
 * Class MAdminRoleDataSeeder
 */
class AdminRoleDataSeeder extends BaseImportCSVSeeder
{
    /**
     * getPathFileCSV
     *
     * @return string
     */
    public function getPathFileCSV(): string
    {
        return dirname(__FILE__) . '/csv/admin_roles.csv';
    }

    /**
     * getTableName
     *
     * @return string
     */
    public function getTableName(): string
    {
        return 'admin_roles';
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
