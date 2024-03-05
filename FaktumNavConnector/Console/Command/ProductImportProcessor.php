<?php
/**
 * Product XML data importer.
 *
 * @package Immerce\FaktumNavConnector\Importer
 * @version 1.0.0
 * @license GPL 2.0+
 */
declare(strict_types=1);

namespace Immerce\FaktumNavConnector\Console\Command;

use Magento\Framework\App\Area;
use Magento\Framework\App\State;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Immerce\FaktumNavConnector\Cron\ProductImporterCron;

class ProductImportProcessor extends Command
{
    const ARG_VERBOSE = 'progress';
    /**
     * @var State
     */
    private State $state;
    /**
     * @var ProductImporterCron
     */
    private ProductImporterCron $productImporterCron;


    public function __construct(
        State $state,
        ProductImporterCron $productImporterCron
    )
    {
        $this->productImporterCron = $productImporterCron;
        $this->state = $state;
        parent::__construct();
    }

    /**
     * @inheritDoc
     */
    protected function configure()
    {
        $this->setName('faktum:nav-connector:product-data-import-processor');
        $this->setDescription('Product XML data import to system.');
        $this->setDefinition([
            new InputOption(
                self::ARG_VERBOSE,
                null,
                InputOption::VALUE_OPTIONAL,
                'Show quantity product data generation progress'
            )
        ]);

        parent::configure();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->state->setAreaCode(Area::AREA_ADMINHTML);
        $this->productImporterCron->execute();
        $output->writeln('<info>Successfully completed.</info>');
    }
}
