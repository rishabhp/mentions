<?php

class rssreader {

  public $rss_or_xml;

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
    if (strpos($content_type, 'xml') !== false || strpos($content_type, 'atom') !== false) {
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

} // end of class
