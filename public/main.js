
$("table tr").each(function(e){
	var $tr = $(this);
	$url = $tr.find(".item_url").attr("href");
	//twitter
	$tr.find(".twitter_count").html("loading...");
	$.get("index.php", {get_count_for: $url, service: 'twitter'}, function(data){
		$tr.find(".twitter_count").html(data);
	});
	
	//facebook
	$tr.find(".facebook_count").html("loading...");
	$.get("index.php", {get_count_for: $url, service: 'facebook'}, function(data){
		$tr.find(".facebook_count").html(data);
	});
	
	//stumbleupon
	$tr.find(".stumbleupon_count").html("loading...");
	$.get("index.php", {get_count_for: $url, service: 'stumbleupon'}, function(data){
		$tr.find(".stumbleupon_count").html(data);
	});
});
