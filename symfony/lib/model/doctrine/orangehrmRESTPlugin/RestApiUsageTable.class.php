<?php

/**
 * RestApiUsageTable
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class RestApiUsageTable extends PluginRestApiUsageTable
{
    /**
     * Returns an instance of this class.
     *
     * @return RestApiUsageTable The table instance
     */
    public static function getInstance()
    {
        return Doctrine_Core::getTable('RestApiUsage');
    }
}