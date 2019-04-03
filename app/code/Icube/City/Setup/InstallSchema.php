<?php 
namespace Icube\City\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Ddl\Table;

class InstallSchema implements InstallSchemaInterface
{
    /**
     * Installs DB schema for a module
     *
     * @param SchemaSetupInterface $setup
     * @param ModuleContextInterface $context
     * @return void
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;

        $installer->startSetup();

        $table = $installer->getConnection()
            ->newTable($installer->getTable('city'))
            ->addColumn(
                'id',
                Table::TYPE_INTEGER,
                null,
                ['identity' => true, 'nullable' => false, 'primary' => true],
                'id city'
            )
            ->addColumn('country_id', Table::TYPE_TEXT, 4, ['nullable' => false, 'default' => '0'], 'Country Id in ISO-2')
            ->addColumn('region_code', Table::TYPE_TEXT, 32, ['nullable' => false])
            ->addColumn('city', Table::TYPE_TEXT, '255', [])
            ->addColumn('kecamatan', Table::TYPE_TEXT, '255', ['nullable' => false])
            ->addColumn('kecamatan_code', Table::TYPE_TEXT, '255', ['nullable' => false])
            ->addIndex($installer->getIdxName('IDX_ICUBE_COUNTRY_REGION_CITY_COUNTRY_ID', ['country_id']), ['country_id'])
            ->setComment('Icube City Region');

        $installer->getConnection()->createTable($table);


        $installer->endSetup();
    }

}
