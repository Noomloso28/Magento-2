<?php
declare(strict_types=1);
namespace Immerce\VerpackenProductsContent\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Immerce\VerpackenProductsContent\Model\ProductUpdate;
class ProductQuery extends Command
{
    private ProductUpdate $productUpdate;

    public function __construct(
        ProductUpdate $productUpdate,
        string $name = null
    )
    {
        $this->productUpdate = $productUpdate;
        parent::__construct($name);

    }

    protected function configure()
    {
        $this->setName('immerce:product-content-element-processing')
        ->setDescription('Change the products content from HTML to Editor element');
        parent::configure();
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $this->productUpdate->execute();

        $output->writeln('Products executed.');
    }

}
