<?php
/*
 * Simple Bruteforce
 *
 * This is just for demonstration, it's not multi-threaded and so not very fast
 *
 * @package Simple Bruteforce
 * @version 1.0
 * @link http://github.com/surikat/simple-bruteforce/
 * @author Jo Surikat <jo@surikat.pro>
 * @website http://wildsurikat.com
 */
function bruteForce($chars,$check,$max=0,$min=0,$trymax=0,&$count=0){
	$key = false;
	$len = strlen($chars);
	if($min>1){
		$min -=1;
		$y = pow($len,$min);
		for($i=0;$i<$min;$i++){
			$y += pow($len, $i);
		}
		$y -= 1;
	}
	else{
		$y = 0;
	}
	if($max)
		$max += 1;
	while(false===$key){
		$i = 1;
		$res = [];
		$num = $y;
		while($num>=0){
			$res[] = $chars[($num/pow($len,$i-1))%$len];
			$num -= pow($len, $i);
			$i += 1;
		}
		$pass = implode('',array_reverse($res));
		if(($trymax&&$trymax<=$count)||($max&&$max<=strlen($pass))){
			return false;
		}
		$key = call_user_func($check,$pass);
		$count++;
		$y += 1;
	}
	return $pass;
}

$charsTable = [
	'alpha'				=> 'abcdefghijklmnopqrstuvwxyz',
	'alphacase'			=> 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ',
	'numeric'			=> '0123456789',
	'alphanumeric'		=> 'abcdefghijklmnopqrstuvwxyz0123456789',
	'alphacasenumeric'	=> 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789',
	
	'basicLatin'		=> ' !"#$%&\'()*+,-./0123456789:;<=>?@ABCDEFGHIJKLMNOPQRSTUVWXYZ[\]^_`abcdefghijklmnopqrstuvwxyz{|}~',
	'latinSupplement'	=> '¡¢£¤¥¦§¨©ª«¬­®¯°±²³´µ¶·¸¹º»¼½¾¿ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖ×ØÙÚÛÜÝÞßàáâãäåæçèéêëìíîïðñòóôõö÷øùúûüýþÿ',
	'latinExtendedA'	=> 'ĀāĂăĄąĆćĈĉĊċČčĎďĐđĒēĔĕĖėĘęĚěĜĝĞğĠġĢģĤĥĦħĨĩĪīĬĭĮįİıĲĳĴĵĶķĸĹĺĻļĽľĿŀŁłŃńŅņŇňŉŊŋŌōŎŏŐőŒœŔŕŖŗŘřŚśŜŝŞşŠšŢţŤťŦŧŨũŪūŬŭŮůŰűŲųŴŵŶŷŸŹźŻżŽžſ',
	'latinExtendedB'	=> 'ƀƁƂƃƄƅƆƇƈƉƊƋƌƍƎƏƐƑƒƓƔƕƖƗƘƙƚƛƜƝƞƟƠơƢƣƤƥƦƧƨƩƪƫƬƭƮƯưƱƲƳƴƵƶƷƸƹƺƻƼƽƾƿǀǁǂǃǄǅǆǇǈǉǊǋǌǍǎǏǐǑǒǓǔǕǖǗǘǙǚǛǜǝǞǟǠǡǢǣǤǥǦǧǨǩǪǫǬǭǮǯǰǱǲǳǴǵǶǷǸǹǺǻǼǽǾǿȀȁȂȃȄȅȆȇȈȉȊȋȌȍȎȏȐȑȒȓȔȕȖȗȘșȚțȜȝȞȟȠȡȢȣȤȥȦȧȨȩȪȫȬȭȮȯȰȱȲȳȴȵȶȷȸȹȺȻȼȽȾȿɀɁɂɃɄɅɆɇɈɉɊɋɌɍɎɏ',
];
$chars = isset($_POST['chars'])&&!empty($_POST['chars'])?$_POST['chars']:$charsTable['alphacasenumeric'];
$max = isset($_POST['max'])&&is_integer(filter_var($_POST['max'],FILTER_VALIDATE_INT))?(int)$_POST['max']:0;
$min = isset($_POST['min'])&&is_integer(filter_var($_POST['min'],FILTER_VALIDATE_INT))?(int)$_POST['min']:0;
$trymax = isset($_POST['trymax'])&&is_integer(filter_var($_POST['trymax'],FILTER_VALIDATE_INT))?(int)$_POST['trymax']:0;
if($min>$max)
	$min = 0;
header('Content-Type: text/html; charset=utf-8');
?>
<style type="text/css">
p,
form,
fieldset,
label,
input,
textarea{
	float:left;
}
p{
	font-weight:bold;
	font-size:12px;
	clear:both;
}
fieldset{
	clear:both;
	border:0;
}
label{
	width:200px;
	vertical-align:top;
	display:block;
}
textarea{
	height:70px;
	width:450px;
}
input{
	width:70px;
}
input[type=number]{
	text-align:center;
}
</style>
<form method="POST">
	<fieldset>
		<label for="chars">Characters</label>
		<textarea name="chars" id="chars"><?php echo $chars;?></textarea>
	</fieldset>
	<fieldset>
		<label for="max">Max Columns</label>
		<input type="number" id="max" name="max" value="<?php echo $max;?>">
	</fieldset>
	<fieldset>
		<label for="min">Min Columns</label>
		<input type="number" id="min" name="min" value="<?php echo $min;?>">
	</fieldset>
	<fieldset>
		<label for="min">Try Max</label>
		<input type="number" id="trymax" name="trymax" value="<?php echo $trymax;?>">
	</fieldset>
	<fieldset>
		<input type="hidden" name="try" value="1">
		<input type="submit" value="Find it">
	</fieldset>
</form>
<p>
<?php
$count = 0;
if(isset($_POST['try'])){
	set_time_limit(5);
	$check = function($pwd){
		//print htmlentities($pwd).'<br>';
		return $pwd == 'abcd';
	};
	$password = bruteForce($chars,$check,$max,$min,$trymax,$count);
	if(false===$password)
		print('Password not found');
	else
		printf('Password is "%s"',$password);
}
?>
</p>
<p style="font-size:11px;">
<?php
print ((int)$count).' tries ';
print ' took '.sprintf("%.2f",(microtime(true)-$_SERVER["REQUEST_TIME_FLOAT"])).' secondes';
?>
</p>