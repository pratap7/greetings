<?php
	include "lib/php/include.php";
?>
<html>
<head>
<title>
</title>
<style>
body{
	margin:0px;
}
#topmenu{
	height:100px;background-color:#EEE;width:100%;
}

#leftmenu{
	height:500px;
	width:40%;
	background-color:#eee;
	float:left;
	overflow:auto;
	
}

#content{
	height:500px;
	width:60%;
	background-color:#fff;
	float:left;
}

#rightmenu{
	height:500px;
	width:20%;
	background-color:#eee;
	float:left;
	overflow:auto;
}

#footer{
	height:100px;background-color:#EEE;float:left; width:100%;
}

.leftmenuitem{
	width:100px;
	/*height:100px;
	border:1px solid #DDD;*/
	margin:3px; 
	float:left;
}
.leftmenuitem img{
	width:100px;
}


.rightmenuitem{
	width:100px;
	/*height:100px;
	border:1px solid #DDD;*/
	margin:3px; 
	float:left;
}
.rightmenuitem img{
	width:100px;
}

</style>
<script type="text/javascript" language="javascript">
function updateBackground(obj,filename)
{
	var gt = document.getElementById("greeting");
	gt.style.backgroundImage = "url(image/background/"+filename+")";

}
function updateObjects(obj,filename)
{
	
	var gt = document.getElementById("object");
	gt.style.backgroundImage = "url(image/objects/"+filename+")";
}
function updateText()
{
	document.getElementById("messagetitle").innerHTML=document.getElementById("messageTitleInput").value;
	var gt = document.getElementById("message");
	gt.innerHTML=document.getElementById("messageBox").value;
	gt.style.color=	document.getElementById("messageColor").value;
	gt.style.width=	document.getElementById("messageboxWidth").value;
	gt.style.fontSize=	document.getElementById("messageFontSize").value+"px";
	if(document.getElementById("messageShadow").checked==true){
		gt.style.textShadow="3px 3px 3px rgba(0,0,0,.5)";
		document.getElementById("messagetitle").style.textShadow="7px 7px 7px rgba(0,0,0,.5)";
	}
}
</script>
</head>
<body>
<div id="topmenu">
  <a href="#changeText">Change Text</a></div>

<div id="leftmenu">
<div style="width:100%; height:40px; float:left; ">Select Background</div>
<?php include "editor/leftmenu.php"; ?>
<div style="margin-top:100px; width:100%; height:40px; float:left;">Select Background</div>
<?php include "editor/rightmenu.php"; ?>

<a name="changeText">
<div style="margin-top:100px; width:100%; height:40px; float:left;">Message</div></a>
<?php include "editor/textbox.php"; ?>


</div>
<div id="content"><?php include"template/temp1.php"; ?></div>

<div id="footer"></div>
</body>
</html>