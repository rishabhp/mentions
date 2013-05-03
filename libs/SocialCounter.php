<?php

class SocialCounter {

  public $rss_or_xml;

  public $twitter_api_url = 'http://urls.api.twitter.com/1/urls/count.json?url=';
  public $fb_api_url = 'https://graph.facebook.com/?ids=';
  public $su_api_url = 'http://www.stumbleupon.com/services/1.01/badge.getinfo?url=';

  public function __construct($client, $simple_pie) {
    $this->Client = $client;
    $this->SimplePie = $simple_pie;
  }

  /*
  $urls must be an array of URLs that
  you want to get the tweet counts for
  */
  public function getTweetCount($url) {
    $api_url = $this->twitter_api_url;

	$url = $api_url . $url;
	$response = $this->Client->get($url);

	// $response should be json
	$data = json_decode($response);
	return (int) $data->count;
  }

  public function getFBCount($items) {
    $api_url = $this->fb_api_url;

    foreach ($items as $key => $item) {
      // API URL to call to get counts
      $url = $api_url . $item['permalink'];
      $response = $this->Client->get($url);
      
      // $response should be json
      $data = json_decode($response, true);
      //pr($data);
      $data = current($data);

      if (isset($data) && isset($data['shares']))
        $items[$key]['fb_count'] = (int) $data['shares'];
      else
        $items[$key]['fb_count'] = 0;
    }

    //pr($items);die();
    return $items;
  }

  public function getSUCount($items) {
    $api_url = $this->su_api_url;

    foreach ($items as $key => $item) {
      // API URL to call to get counts
      $url = $api_url . $item['permalink'];
      $response = $this->Client->get($url);
      
      // $response should be json
      $data = json_decode($response);
      //pr($data);
      if (isset($data->result) && isset($data->result->views))
        $items[$key]['su_count'] = (int) $data->result->views;
      else
        $items[$key]['su_count'] = 0;
    }

    return $items;
  }

} // end of class
