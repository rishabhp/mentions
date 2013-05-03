
$("table tr").each(function(e){
	var $tr = $(this);
	$url = $tr.find(".item_url").attr("href");
	$tr.find(".twitter_count").html("loading...");
	$.post("", {get_count_for: $url, service: 'twitter'}, function(data){
		$tr.find(".twitter_count").html(data);
	})
});
