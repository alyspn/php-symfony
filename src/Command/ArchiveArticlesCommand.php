<?php
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


#[AsCommand(name: 'app:archive-contents')]
class ArchiveContentsCommand extends Command
{

    private $articleService;

    public function __construct(articleService $articleService)
    {
        parent::__construct();
        $this->articleService = $articleService;
    }

    protected function configure()
    {
        $this->setDescription('Archives all unvalidated articles');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $articles = $this->articleService->getUnvalidatedarticles();

        $progressBar = new ProgressBar($output, count($articles));
        $progressBar->start();

        foreach ($articles as $article) {
            $this->articleService->archivearticle($article);
            $progressBar->advance();
        }

        $progressBar->finish();

        $output->writeln('');
        $output->writeln('All unvalidated articles have been archived.');

        return Command::SUCCESS;
    }
}
