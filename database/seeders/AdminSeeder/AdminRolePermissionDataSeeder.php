<?php

namespace Database\Seeders\AdminSeeder;

/**
 * Class RAdminRolePermissionDataSeeder
 */
class AdminRolePermissionDataSeeder extends BaseImportCSVSeeder
{
    /**
     * getPathFileCSV
     *
     * @return string
     */
    public function getPathFileCSV(): string
    {
        return dirname(__FILE__) . '/csv/admin_role_permissions.csv';
    }

    /**
     * getTableName
     *
     * @return string
     */
    public function getTableName(): string
    {
        return 'admin_role_permissions';
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
