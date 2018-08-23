<?php
session_start();

?>
<html>
<head><title>Buy Products</title></head>
<body>
<form method="GET" action="buy.php">
	<label>Shopping Basket:</label>
	<div id="shopout">&nbsp;</div>
	
	
</form>
<?php
$price=0;

//$_SESSION['basket'];

//$bname=array();
//$bimg=array();
//$bpri=array();
if(isset($_GET['buy']))
{
	$pid=$_GET['buy'];
		$xmlstr3 = file_get_contents("http://sandbox.api.ebaycommercenetwork.com/publisher/3.0/rest/GeneralSearch?apiKey=****");
		$xml3 = new SimpleXMLElement($xmlstr3);
		$buyd=$xml3->categories->category->items->product;
		$mp=$buyd->minPrice;
		$bi=$buyd->images->image->sourceURL;
		$bn=$buyd->name;
		//$n=count($_SESSION['basket']);

		//array_push($bname,$bn);
		//array_push($bimg,$bi);
		//array_push($bpri,$mp);
		
		//$_SESSION['basket'][$n]=$bi;
		//$_SESSION['basket'][$n]=$mp;
		//$_SESSION['n']++;


		

	if(isset($_SESSION["price"]))
	{
		
		$_SESSION["price"]=$_SESSION["price"]+floatval($mp);
		//$_SESSION["price"]=$price;
		//print "<label>Total: ".$_SESSION["price"] ."$<div id=\"total\"></div></label>";	
	}
	else
	{
		$_SESSION["price"]=floatval($mp);
		$_SESSION['basket']=array();
		//$_SESSION["price"]=$price;
		//print "<label>Total: ".$_SESSION["price"] ."$<div id=\"total\"></div></label>";
	}
	if(!in_array($pid, $_SESSION['basket']))
	{
		array_push($_SESSION['basket'],$pid);
	}
	else
	{
		$_SESSION["price"]=$_SESSION["price"]-floatval($mp);
	}
}
else
		{
			"<label>Total:0 $<div id=\"total\"></div></label>";
		}

//print "<label>Total: ".$_SESSION["price"] ."$<div id=\"total\"></div></label>"
?>


<?php
if(isset($_GET['clear']))
{
	session_unset();
	$_SESSION['basket']=array();
	$_SESSION["price"]=0;
	

}
if(isset($_GET['delete']))
{

	$dpid=array();
	$dpid[0]=$_GET['delete'];
	$xmlstr12 = file_get_contents("http://sandbox.api.ebaycommercenetwork.com/publisher/3.0/rest/GeneralSearch?apiKey=****");
	$xml12 = new SimpleXMLElement($xmlstr12);
	$dpath=$xml12->categories->category->items->product;
	$dprice=$dpath->minPrice;
	$_SESSION['basket']=array_diff($_SESSION['basket'], $dpid);
	$_SESSION['price']=$_SESSION['price']-floatval($dprice);
}
?>



<table border=1>
<tbody><tr>
<?php

//print($_SESSION['basket']);
if(isset($_SESSION['price']))
{
$nu=0;
foreach ($_SESSION['basket'] as $ppid) 
{
	$xmlstr11 = file_get_contents("http://sandbox.api.ebaycommercenetwork.com/publisher/3.0/rest/GeneralSearch?apiKey=****");
	$xml11 = new SimpleXMLElement($xmlstr11);
	$path=$xml11->categories->category->items->product;
	$bname=$path->name;
	$bimg=$path->images->image->sourceURL;
	$bprice=$path->minPrice;
	$bpid=$path['id'];
	$boff=$path->productOffersURL;

	//print "<pre>".$bname."\n<img src=$bimg>"."\n".$bprice."<a href=\"buy.php?delete=$bpid\">Delete</a>"."\n</pre>";

//}}
?>
<td align="center"><b><?= ++$nu ?></b></td>
<td><a href="<?= $boff ?>" ><img src="<?= $bimg ?>"/></a></td>
<td><?= $bname ?></td>
<td><?= $bprice ?>$</td>
<td><a href= "buy.php?delete=<?= $bpid ?>">Delete</a></td>
</tr>
<?php
}}
?>
</tbody></table>
<?php
if(isset($_SESSION["price"]))
print "<br/><label>Total: ".$_SESSION["price"] ."$<div id=\"total\"></div></label><br/>";

?>
<a href="buy.php?clear=1"><input type="submit" value="Empty Basket" ></a>

<?php
error_reporting(E_ALL);
ini_set('display_errors','On');
/*function addbasket($addp)
	{
		if($price)
			$price=$price+$addp;
		else
			$price=$addp;

		print $price;

	}*/
$xmlstr1 = file_get_contents("http://sandbox.api.ebaycommercenetwork.com/publisher/3.0/rest/CategoryTree?apiKey=****");
$xml1 = new SimpleXMLElement($xmlstr1);
$og=array();
$ogname=array();
$i=0;
foreach($xml1->category->categories->category as $tid)
{
	$og[$i]= array();
	$ogname[$i]= array();
	$idd=$tid['id'];
	$xmlstr2 = file_get_contents("http://sandbox.api.ebaycommercenetwork.com/publisher/3.0/rest/CategoryTree?apiKey=****");
	$xml2 = new SimpleXMLElement($xmlstr2);
	//print($tid['id']."\n".$tid->name."\n");
	
	$og[$i][0]=$tid['id'];
	$ogname[$i][0]=$tid->name;
	
	$j=1;
	
	if($xml2->category->categories->category['id']){

	foreach($xml2->category->categories->category as $did)
	{
		//print($did['id']."\n".$did->name."\n");

		$og[$i][$j]=$did['id'];
		$ogname[$i][$j]=$did->name;
		$j++;
	}
	$i=$i+1;
}
}

?>
<br/><br/>
<form method="get" action="">
	<fieldset>
	<legend>Find products:</legend>
	<label>Category:
	<select name="category">
	<?php 
	print "<option value=72>&#160&#160&#160Computers</option>";
	for($i=0; $i <count($og); $i++)
	{
		$name=$ogname[$i][0];
		print "<option value=$og[$i][0]>&#160&#160&#160$name</option>";
		print "<optgroup label='$name:'>";
		for($j=1; $j <count($og[$i]); $j++)
		{
			$oid=$og[$i][$j];
			$nam=$ogname[$i][$j];
			print "<option value=$oid>&#160&#160&#160&#160&#160$nam</option>";	
			
		}
		print "</optgroup>";
	}
	?>
	</select>
	
	</label>

	<label>Search keywords: <input name="search" type="text"><label>
	<input value="Search" type="submit">
	</label>
	</label>
	</fieldset>
	<br/>
	<table border="1"><tbody>
	<tr>
	<?php
		if(isset($_GET['search']))
{
	
	$id=$_GET['category'];
	$key=$_GET['search'];
	$xmlstr = file_get_contents("http://sandbox.api.ebaycommercenetwork.com/publisher/3.0/rest/GeneralSearch?apiKey=****");
	$file="new.xml";

	$xml = new SimpleXMLElement($xmlstr);
	file_put_contents($file, $xml->asxml());
	$num=0;
	foreach($xml->categories->category->items->product as $res)
	{
		$img=$res->images->image->sourceURL;
		$rid=$res['id'];

		//print "<pre>".$res->name."\n".$res->fullDescription."\n".$res->images->image->sourceURL."\n".$res->minPrice."$\n</pre>";
		//print "<pre>\n<a href=buy.php?buy=$rid><img src=$img\></a>\n".$res->productOffersURL."\n</pre>";

?>
<td align="center"><b><?= ++$num ?></b></td>
<td><a href="buy.php?buy=<?= $rid?>"><img src=<?= $img ?>\></a></td>
<td align="center"><?= $res->name ?></td>
<td align="center"><?= $res->minPrice ?>$</td>
<td align="justify"><?= $res->fullDescription ?></td>
<td align="justify"><a href="<?= $res->productOffersURL ?>"><?= $res->productOffersURL ?></a></td>
</tr>
<?php
	}

}
	
	?>
</tbody>
</table>
</form>
</body>
</html>
