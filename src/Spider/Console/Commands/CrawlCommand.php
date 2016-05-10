<?php namespace Superirale\Spider\Console\Commands;


use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class CrawlCommand extends Command
{
    protected function configure()
    {
        $this->setName('crawl:url')
            ->setDescription('crawl URI')
            ->addArgument(
                'name',
                InputArgument::OPTIONAL,
                'Which uri do you want to crawl?'
            )
            ->addOption(
               'posts',
               null,
               InputOption::VALUE_NONE,
               'If set, the task will yell in uppercase letters'
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $name = $input->getArgument('name');
        if ($name) {
            $text = 'Hello '.$name;
        } else {
            $text = 'Hello';
        }

        if ($input->getOption('posts')) {
           $this->$crawlPosts();
        }

        $output->writeln($text);
    }

    private function crawlPosts()
    {
    	# code...
    }
}