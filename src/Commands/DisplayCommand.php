<?php namespace Carbontwelve\AzureMonitor\Commands;

use Carbon\Carbon;
use Symfony\Component\Console\Input\InputArgument;
use WindowsAzure\Common\ServiceException;
use WindowsAzure\Common\ServicesBuilder;
use WindowsAzure\Table\Models\Entity;
use WindowsAzure\Table\Models\Filters\Filter;
use WindowsAzure\Table\Models\QueryEntitiesOptions;
use WindowsAzure\Table\Models\QueryEntitiesResult;

class DisplayCommand extends Command
{

    /** @var \WindowsAzure\Table\TableRestProxy $tableRestProxy */
    private $tableRestProxy;

    /** @var null|string */
    private $tableName;

    protected function configure()
    {
        $this->setName('display')
            ->setDescription('Display statistics')
            ->addArgument('AccountName', InputArgument::REQUIRED, 'Name of the storage account')
            ->addArgument('AccountKey', InputArgument::REQUIRED, 'Your storage account API key');
    }

    /**
     * @return int
     */
    protected function fire()
    {
        $connectionString = "DefaultEndpointsProtocol=https;AccountName=" . $this->input->getArgument('AccountName') . ";AccountKey=" . $this->input->getArgument('AccountKey');

        /** @var \WindowsAzure\Table\TableRestProxy $tableRestProxy */
        $this->tableRestProxy = ServicesBuilder::getInstance()->createTableService($connectionString);

        $this->identifyTableName();

        $result = $this->getQueryResult("Timestamp le datetime'2016-01-12T16:12:59' and Timestamp ge datetime'2016-01-12T16:02:59'");
        if (! $result instanceof QueryEntitiesResult) {
            return $result;
        }

        $entities = $result->getEntities();

        $n = 1;

        /** @var Entity $entity */
        foreach ($entities as $entity) {
            $props = $entity->getProperties();

            $vmName = $this->getVirtualMachineNameFromPartitionKey($entity->getPartitionKey());
            $this->output->writeln($vmName . ' [' . $entity->getTimestamp()->format('d-m-Y H:i:s') . ']' . '<info>' . $entity->getPropertyValue('CounterName') . '</info> : <comment>' . $entity->getPropertyValue('Last') . '</comment>');
        }

        $this->info( count($entities) . 'rows');
    }

    /**
     * Azure splits metrics tables every 10 days so we need to get the latest one from the storage pool
     * @return void
     */
    private function identifyTableName()
    {
        $tables = $this->tableRestProxy->queryTables()->getTables();
        $lastDate = null;
        $tmpCurrentTable = null;
        foreach ($tables as $table) {
            if (strpos($table, "WADMetricsPT1MP10") !== false) {
                $tableDate = Carbon::createFromFormat('Ymd', substr($table, -8));

                if (is_null($lastDate)) {
                    $lastDate = $tableDate;
                    $tmpCurrentTable = $table;
                    continue;
                }

                if ($tableDate->gt($lastDate)) {
                    $lastDate = $tableDate;
                    $tmpCurrentTable = $table;
                }

            }
        }
        unset($table, $tableDate);

        $this->tableName = $tmpCurrentTable;
    }

    private function getVirtualMachineNameFromPartitionKey($key)
    {
        $key = str_replace(':002F', '/', $key);
        $key = str_replace(':002D', '-', $key);
        $key = str_replace(':002E', '.', $key);

        $key = explode('/', $key);

        return end($key);
    }

    private function getQueryResult($filter = '')
    {

        try {
            $result = $this->tableRestProxy->queryEntities($this->tableName, $filter);
            return $result;
        } catch (ServiceException $e) {
            $this->output->writeln('<error>[!]</error> Exception Code: <comment>' . $e->getCode() . '</comment>');
            $this->output->writeln($e->getMessage());
            return $e->getCode();
        }

    }
}
