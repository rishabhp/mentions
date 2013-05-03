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

  
  // If the URL is a feedburner or RSS URL
  // then this method will retrieve ALL item URLs
  // from that and return in an array

  public function getItems($url, $limit = 50) {
    $items = [];
    $limit_flag = 0;

    $content_type = get_headers($url, 1)['Content-Type'];
    if (strpos($content_type, 'xml') !== false && strpos($content_type, 'atom') !== false) {
      $rss_or_xml = true;
    }
    else {
      $rss_or_xml = false;
    }

    if ($rss_or_xml) {
      // Yup, this is RSS/XML

      $this->SimplePie->set_feed_url($url);
      $this->SimplePie->init();
      
      foreach ($this->SimplePie->get_items() as $item) {
        if ($limit_flag >= $limit) break;

        $rss_item = [];
        $rss_item['title'] = $item->get_title();
        $rss_item['pub_date'] = $item->get_date('j F Y | g:i a');

        $rss_item['permalink'] = $item->get_permalink();
        $orig = $item->get_item_tags('http://rssnamespace.org/feedburner/ext/1.0',
'origLink');
        // Sometimes its the direct URL itself
        // not feedburner one, then the key will be NULL
        // so isset checking is important
        if (isset($orig[0]['data']))
          $rss_item['permalink'] = $orig[0]['data'];

        $items[] = $rss_item;

        ++$limit_flag;
      }
    }
    else {
      // Most probably the URL passed is not a XML/RSS so
      // lets try to read its source and figure out the RSS URL
      $res = $this->Client->get($url);
      $feed = false;
      if ($res) {
        // Find the first string which is a URL and contains
        // 'feed' in it
        // '@(https?://\S+?feed\S+)@ui' ???
        // $feed = ...
      }

      if (!$feed) {
        // Just set the URL that was passed as the item
        // We'll just show stats for it
        $items[] = [
          'pub_date' => null,
          'title' => $url,
          'permalink' => $url
        ];
      }
    }

    $this->rss_or_xml = $rss_or_xml;

    //echo '<pre>';
    //print_r($items);
    //die();
    return $items;
  }

  /*
  $urls must be an array of URLs that
  you want to get the tweet counts for
  */
  public function getTweetCount($items) {
    $api_url = $this->twitter_api_url;

    foreach ($items as $key => $item) {
      // API URL to call to get counts
      $url = $api_url . $item['permalink'];
      $response = $this->Client->get($url);
      
      // $response should be json
      $data = json_decode($response);
      $items[$key]['tweet_count'] = (int) $data->count;
    }

    return $items;
  }

  public function getFBCount($items) {
    $api_url = $this->fb_api_url;

    foreach ($items as $key => $item) {
      // API URL to call to get counts
      $url = $api_url . $item['permalink'];
      $response = $this->Client->get($url);
      
      // $response should be json
      $data = json_decode($response);
      //pr($data);
      if (isset($data->{$item['permalink']}) && isset($data->{$item['permalink']}->shares))
        $items[$key]['fb_count'] = (int) $data->{$item['permalink']}->shares;
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