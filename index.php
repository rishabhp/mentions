<?php

date_default_timezone_set('UTC');

require('./libs/Client.php');
require('./libs/SocialCounter.php');
require_once('./php/autoloader.php');

require('helpers.php');

$client = new Client();
$simple_pie = new SimplePie();

$items = [];
$url = '';

if (isset($_GET['url'])) {
  $url = $_GET['url'];

  $counter = new SocialCounter($client, $simple_pie);
  $items = $counter->getItems($url, 20);

  $items = $counter->getTweetCount($items);
  $items = $counter->getFBCount($items);
  $items = $counter->getSUCount($items);
  //pr($items);die();

  if (!$counter->rss_or_xml) {
    // We must suggest something like
    // "Do you want to test [RSS Link] ?"
    // For that getting the Feeds URL is important

    // '@(https?://\S+?feed\S+)@ui' ???
  }
}

require('./views/home.php');