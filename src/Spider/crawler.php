<?php namespace Superirale\Spider;

use Goutte\Client;

class Crawler
{
	private $client;
	private $maxDepth;

	function __construct($depth = 10)
	{
		$this->client = new Client();
		$this->maxDepth = $depth;
	}

	public function getDataObject($url, $params = [])
	{
		try {
				$this->setParams($params);

				$data = $this->client->request('GET', $url);

				if($data)
					return $data;
				else
					return false;
		}
		 catch (\Guzzle\Http\Exception\CurlException $e) {
				echo $e->getMessage();
		}
	
	}


	public function setParams($params = [])
	{
		if(!empty($params)){
			foreach ($params as $key => $value) {
				$this->client->getClient()->setDefaultOption($params[$key], $value);
			}
		}
	}


}