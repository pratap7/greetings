<style>
#greeting
{
	width:600px;
	margin:10 auto;
	background-color:#666;
	height:370px;
	background-repeat:no-repeat;
}
#object{
	width:300px;
	height:370px;
	float:right;
	position:relative;
	background-repeat:no-repeat;
	background-position:bottom;
}
#messagetitle{
	width:600px;
	height:30px;
	float:left;
	position:relative;
	z-index:100px;
	background-repeat:no-repeat;
	font-size:36px;
	color:#FFFFFF;
	font-family:Georgia, "Times New Roman", Times, serif;
}
#message{
	width:300px;
	height:330px;
	float:left;
	position:absolute;
	z-index:100px;
	background-repeat:no-repeat;
	font-size:24px;
	color:#FFFFFF;
	font-family:Georgia, "Times New Roman", Times, serif;

}

</style>
<div id="greeting">
<div id="messagetitle">Type greeting title</div><br />
<br />
<br />

<div id="message">Type greeting message</div>
<div id="object"></div></div>
