<!DOCTYPE html>
<html lang="en">
<head>
  <link rel="stylesheet" type="text/css" href="/public/style.css">
</head>
<body>
  <header>
  	<div id="social_buttons">
	  	<div id="twitter_button">
		  	<a href="https://twitter.com/share" class="twitter-share-button" data-url="http://mentions.in/">Tweet</a>
			<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>
		</div>
		<div id="facebook_button">
			<div id="fb-root"></div>
			<script>(function(d, s, id) {
			  var js, fjs = d.getElementsByTagName(s)[0];
			  if (d.getElementById(id)) return;
			  js = d.createElement(s); js.id = id;
			  js.src = "//connect.facebook.net/en_GB/all.js#xfbml=1&appId=163181677023";
			  fjs.parentNode.insertBefore(js, fjs);
			}(document, 'script', 'facebook-jssdk'));</script>
			<div class="fb-like" data-href="http://mentions.in/" data-send="false" data-width="450" data-show-faces="false"></div>
		</div>
		<div id="googleplus_button">
			<!-- Place this tag where you want the +1 button to render. -->
			<div class="g-plusone" data-size="medium" data-annotation="inline" data-width="300" data-href="http://mentions.in/"></div>

			<!-- Place this tag after the last +1 button tag. -->
			<script type="text/javascript">
			  (function() {
				var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
				po.src = 'https://apis.google.com/js/plusone.js';
				var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
			  })();
			</script>
		</div>
	</div>
  	<h2><a href="/">Mentions.in</a></h2>
  </header>

  <h1>Generate social sharing analytics for any URL or RSS Feed.</h1>

  <form method="get">
    <input type="text" name="url" id="url" value="<?= $url ?>">
    <input type="submit" name="submit" value="GO" id="submit">
  </form>

  <? if (isset($recommend_feed_url)) { ?>
  <p>You may also want to check social mentions for <a href="<?= e($recommend_feed_url) ?>">your feed</a>.</p>
  <? } ?>

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
      <td><?= e($item['pub_date']) ?></td>
      <td><a class="item_url" href="<?= e($item['permalink']) ?>"><?= e($item['title']) ?></a></td>
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
