<!DOCTYPE html>
<html lang="en">
<head>
  <link rel="stylesheet" type="text/css" href="/public/style.css">
</head>
<body>
  <header><h2>Mentions.in</h2></header>

  <h1>Generate social sharing analytics for any URL or RSS Feed.</h1>

  <form method="get">
    <input type="text" name="url" placeholder="url, rss eg: http://feeds.mashable.com/Mashable", id="url" value="<?= $url ?>">
    <input type="submit" name="submit" value="GO" id="submit">
  </form>

  <table border="0" cellspacing="0" cellpadding="0">
    <tr>
      <th class="date" style="">Date</th>
      <th style="border-bottom: 5px solid #4e5f72;">Title</th>
      <th style="border-bottom: 5px solid hsla(207, 84%, 57%, 1)">Tweets</th>
      <th style="border-bottom: 5px solid hsla(221, 44%, 41%, 1)">FB Likes</th>
      <!--<th style="border-bottom: 5px solid hsla(6, 65%, 51%, 1)">+1s</th>-->
      <th style="border-bottom: 5px solid hsla(11, 83%, 53%, 1)">Stumbles</th>
    </tr>
    <? foreach( $items as $item ) { ?>
    <tr>
      <td><?= $item['pub_date'] ?></td>
      <td><a class="item_url" href="<?= $item['permalink'] ?>"><?= $item['title'] ?></a></td>
      <td class="twitter_count"></td>
      <td class="facebook_count"></td>
      <td class="stumbleupon_count"></td>
    </tr>
    <? } ?>
  </table>
  <footer>
  	made by <a href="https://twitter.com/_rishabhp">@_rishabhp</a>
    &nbsp;&nbsp;|&nbsp;&nbsp;
    code is <a href="http://github.com/rishabhp/mentions">forkable</a>
  </footer>
  
  <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
  <script src="/public/main.js"></script>

  <script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-40645811-1', 'mentions.in');
  ga('send', 'pageview');

  </script>
</body>
</html>
