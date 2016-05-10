<?php namespace Superirale\Spider\Console\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Goutte\Client;

class CrawlCommand extends Command
{
    protected function configure()
    {
        $this->setName('spider:crawl')
            ->setDescription('crawl URI')
            ->addArgument(
                'url',
                InputArgument::OPTIONAL,
                'Which uri do you want to crawl?'
            )
            ->addOption(
               'posts',
               null,
               InputOption::VALUE_REQUIRED,
               'If set, the task will use the value in the option in execution'
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $url = $input->getArgument('url');
        if ($url) {
        	$client = new Client();

            $crawler = $client->request('GET', $url);
            // dump($crawler);
        } 
        if ($input->getOption('posts')) {
        	switch ($input->getOption('posts')) {
        		case 'misykona':
        			//load crawling rules for misykona
        			// $class ="isotopeElement post_format_standard odd isotopeElementShow";
        			$class['posts'] ="article h4 a";
        			$class['post'] ="div.post_text_area";
        			break;
        		
        		default:
        			$class = "post";
        			break;
        	}
           $posts = $this->crawlPosts($crawler, $client, $class);
        }
        dump($posts);
        // $output->writeln($text);
    }

    public function crawlPosts(\Symfony\Component\DomCrawler\Crawler $crawler, \Goutte\Client $client, $class)
    {
    	$posts = [];
    	$count = 0;
    	$crawler->filter($class['posts'])->each(function ($node) use (&$posts, &$count, $crawler, $client) {
    	
		    $posts[$count]['title'] = $node->text();
		    $posts[$count]['link'] = $node->attr('href');
		    // $posts[$count]['content'] = $client->click($crawler->selectLink($node->text())->link());
		    // $node->attr('href');
		 
		    $count ++;
		});

		foreach ($posts as $key => $value) {
		
			$page_obj = $client->request('GET', $posts[$key]['link']);
			$posts[$key]['content'] = $page_obj->filter($class['post'])->text();
			dump($posts[$key]);exit;

		}


		return $posts;
    }

    private function crawl(\Goutte\Client $client)
    {
    	
    }

    public function savePost($posts = []);
    {
    	# code...
    }
}