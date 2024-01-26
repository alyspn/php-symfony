<?php
namespace App\Command;

use App\Service\ArticleService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'app:list-archived-articles')]
class ListArchivedArticlesCommand extends Command
{
    private $articleService;

    public function __construct(articleService $articleService)
    {
        parent::__construct();
        $this->articleService = $articleService;
    }

    protected function configure()
    {
        $this->setDescription('Lists all archived articles');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $articles = $this->articleService->getArchivedarticles();

        $table = new Table($output);
        $table->setHeaders(['ID', 'Title', 'Status'])
            ->setRows($articles);
        $table->render();

        return Command::SUCCESS;
    }
}
