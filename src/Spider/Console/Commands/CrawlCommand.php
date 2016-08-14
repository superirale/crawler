<?php namespace Superirale\Spider\Console\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Goutte\Client;
use RedBeanPHP\R;

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
        } 

        if ($input->getOption('posts')) {
        	switch ($input->getOption('posts')) {
        		case 'misykona':
        			//load crawling rules for misykona
        			$class['posts'] ="article h4 a";
        			$class['post'] ="div.post_text_area";
        			break;
        		
        		default:
        			$class = "post";
        			break;
        	}
           $posts = $this->crawlPosts($crawler, $client, $class);
        }
      	foreach ($posts as $post) {

      		$status = $this->savePost($post);
      		
      		if(!$status)
      			$errors[] = $post['title'].' - not saved';
      	}
      	$response = $this->formatResponse('Scaping Done!');
      	if(!empty($errors)){
      		$response = $this->formatResponse(join("\n", $errors), false);
      	}

        $output->writeln($response);
    }

    public function crawlPosts(\Symfony\Component\DomCrawler\Crawler $crawler, \Goutte\Client $client, $class)
    {
    	$posts = [];
    	$count = 0;
    	$crawler->filter($class['posts'])->each(function ($node) use (&$posts, &$count, $crawler, $client) {
    	
		    $posts[$count]['title']  $node->text();
		    $posts[$count]['link'] = $node->attr('href');
	
		    $count ++;
		});

		foreach ($posts as $key => $value) {
			$page_obj = $client->request('GET', $posts[$key]['link']);
			$posts[$key]['content'] = $page_obj->filter($class['post'])->text();

		}

		return $posts;
    }

    private function crawlPaginatedLinks(\Goutte\Client $client)
    {
    	
    }

    private function savePost($post_data = [])
    {
        $post_data = (new \GUMP())->sanitize($post_data);
        
    	if(!empty($post_data)){
    		$post = R::dispense('posts');
    		$post->title = $post_data['title'];
    		$post->link = $post_data['link'];
    		$post->content = $post_data['content'];
    		$save = R::store($post);
          
    		if(is_int($save) && $save != 0)
    			return true;
    	}
    	return false;
    }

    public function formatResponse($response, $status = true)
    {
    	$resp = "<info>".$response."</info>";
    	if(!$status)
    		$resp = "<error>".$response."</error>";

    	return $resp;

    }
}