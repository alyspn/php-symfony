<?php
namespace App\Command;

use App\Service\ArticleService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;


#[AsCommand(name: 'app:validate-article')]
class ValidateArticleCommand extends Command
{
    private $articleService;

    public function __construct(articleService $articleService)
    {
        parent::__construct();
        $this->articleService = $articleService;
    }

    protected function configure()
    {
        $this->setDescription('Validates a article by its ID')
             ->addArgument('id', InputArgument::OPTIONAL, 'The ID of the article');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $id = $input->getArgument('id');

        if (!$id) {
            $helper = $this->getHelper('question');
            $question = new Question('Please enter the ID of the article: ');
            $id = $helper->ask($input, $output, $question);
        }

        $article = $this->articleService->validateArticle($id);

        if ($article) {
            $output->writeln('article with ID ' . $id . ' has been validated.');
        } else {
            $output->writeln('article not found.');
        }

        return Command::SUCCESS;
    }
}
