
$("table tr").each(function(e){return;
  if(e == 1)
  {
    
  }
  else
  {
    twp = ""; fbp = ""; stp = ""; plp = "";
    tweets = parseInt(Math.random()*200); twop = tweets/200 + 0.25;
    likes = parseInt(Math.random()*100); fbop = likes/100 + 0.25;
    pluses = parseInt(Math.random()*50); plop = pluses/50 + 0.25;
    stumbles = parseInt(Math.random()*100); stop = stumbles/100 + 0.25;
    if(Math.random() > 0.75)
      twp = "<span class=\"rank tw\">+"+parseInt(Math.random()*10+1)+"</span>";
    if(Math.random() > 0.85)
      fbp = "<span class=\"rank fb\">+"+parseInt(Math.random()*10+1)+"</span>";
    if(Math.random() > 0.95)
      plp = "<span class=\"rank pl\">+"+parseInt(Math.random()*10+1)+"</span>";
    if(Math.random() > 0.95)
      stp = "<span class=\"rank st\">+"+parseInt(Math.random()*10+1)+"</span>";
    total = tweets + likes + pluses + stumbles;
    $(this).find("td:eq(2)").html(tweets+twp).css("color", "rgba(0, 0, 0, "+twop+")")//.css("background", "hsla(207, 84%, 57%, "+twop+")");
    $(this).find("td:eq(3)").html(likes+fbp).css("color", "rgba(0, 0, 0, "+fbop+")")//.css("background", "hsla(221, 44%, 41%, "+fbop+")");
    $(this).find("td:eq(4)").html(pluses+plp).css("color", "rgba(0, 0, 0, "+plop+")")//.css("background", "hsla(6, 65%, 51%, "+plop+")");
    $(this).find("td:eq(5)").html(stumbles+stp).css("color", "rgba(0, 0, 0, "+stop+")")//.css("background", "hsla(11, 83%, 53%, "+stop+")");
  }
});