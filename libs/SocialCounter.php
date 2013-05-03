<?php

class SocialCounter {

  public $rss_or_xml;

  public $twitter_api_url = 'http://urls.api.twitter.com/1/urls/count.json?url=';
  public $fb_api_url = 'https://graph.facebook.com/?ids=';
  public $su_api_url = 'http://www.stumbleupon.com/services/1.01/badge.getinfo?url=';

  public function __construct($client) {
    $this->Client = $client;
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

  public function getFBCount($url) {
    $api_url = $this->fb_api_url;

	$url = $api_url . $url;
	$response = $this->Client->get($url);

	// $response should be json
	$data = json_decode($response, true);
	$data = current($data);

	if (isset($data) && isset($data['shares']))
	return (int) $data['shares'];
	else
	return  0;
  }

  public function getSUCount($url) {
    $api_url = $this->su_api_url;

      $url = $api_url . $url;
      $response = $this->Client->get($url);
      
      $data = json_decode($response);

      if (isset($data->result) && isset($data->result->views))
        return = (int) $data->result->views;
      else
        return 0;
  }

} // end of class
