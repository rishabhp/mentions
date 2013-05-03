var $loaded = 0;
var $values = $(".twitter_count").length*3;
var highest_fb = 0;
var highest_tw = 0;
var highest_su = 0;
$("table tr:not(:first-child)").each(function(e){
	var $tr = $(this);
	$url = $tr.find(".item_url").attr("href");

	//twitter
	$.get("index.php", {get_count_for: $url, service: 'twitter'}, function(data){
		$loaded++;
		$tr.find(".twitter_count").html(data);
		highest_tw = Math.max(parseInt(highest_tw), parseInt(data));
		apply_opacity();
	});

	//facebook
	$.get("index.php", {get_count_for: $url, service: 'facebook'}, function(data){
		$loaded++;
		$tr.find(".facebook_count").html(data);
		highest_fb = Math.max(parseInt(highest_fb), parseInt(data));
		apply_opacity();
	});

	//stumbleupon
	$.get("index.php", {get_count_for: $url, service: 'stumbleupon'}, function(data){
		$loaded++;
		$tr.find(".stumbleupon_count").html(data);
		highest_su = Math.max(parseInt(highest_su), parseInt(data));
		apply_opacity();
	});
});

function apply_opacity()
{
	var opacity = 1;
	if($loaded >= $values)
	{
		$(".twitter_count").each(function(){
			opacity = parseInt($(this).html())/highest_tw*0.5 + 0.5;
			$(this).css("color", "rgba(0, 0, 0, "+opacity+")");
		});
		$(".facebook_count").each(function(){
			opacity = parseInt($(this).html())/highest_fb*0.5 + 0.5;
			$(this).css("color", "rgba(0, 0, 0, "+opacity+")");
		});
		$(".stumbleupon_count").each(function(){
			opacity = parseInt($(this).html())/highest_su*0.5 + 0.5;
			$(this).css("color", "rgba(0, 0, 0, "+opacity+")");
		});
	}
}
