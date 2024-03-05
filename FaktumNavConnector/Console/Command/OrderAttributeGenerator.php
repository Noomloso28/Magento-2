<?php
/**
 * Product Attributes generate.
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
use Immerce\FaktumNavConnector\Model\OrderAttributesToJsonProcessor;

class OrderAttributeGenerator extends Command
{
    const ARG_VERBOSE = 'progress';
    /**
     * @var State
     */
    private State $state;

    /**
     * @var OrderAttributesToJsonProcessor
     */
    private OrderAttributesToJsonProcessor $attributesToJsonProcessor;

    public function __construct(
        State $state,
        OrderAttributesToJsonProcessor $attributesToJsonProcessor
    )
    {
        $this->state = $state;
        $this->attributesToJsonProcessor = $attributesToJsonProcessor;
        parent::__construct();
    }

    /**
     * @inheritDoc
     */
    protected function configure()
    {
        $this->setName('faktum:nav-connector:order-generate-atribute-data');
        $this->setDescription('Generate order atttributes for import mapping.');
        $this->setDefinition([
            new InputOption(
                self::ARG_VERBOSE,
                null,
                InputOption::VALUE_OPTIONAL,
                'Show quantity attribute value generation progress'
            )
        ]);

        parent::configure();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->state->setAreaCode(Area::AREA_ADMINHTML);
        $this->attributesToJsonProcessor->processing();
        $output->writeln('<info>Successfully completed.</info>');
    }
}
