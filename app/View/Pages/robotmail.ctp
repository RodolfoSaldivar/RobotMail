

<?php

	if ($_POST["action"] == "sub")
	{
		$_POST["mails"] = trim($_POST["mails"]);
		$mails = explode("\x0D", $_POST["mails"]);
		$trimmed_array=array_map('trim',$mails);
		$arrMails;
		foreach ($trimmed_array as $key => $value)
		{
			$arrMails[$key]["numero"] = $key + 1;
			$arrMails[$key]["mail"] = $value;
		}
	}

	
	function validarMail($mailAvalidar, $key, &$arrMails)
	{
		$post_string = "email=$mailAvalidar";

		$curl_connection = curl_init('http://www.mailtester.com/testmail.php');

		curl_setopt($curl_connection, CURLOPT_CONNECTTIMEOUT, 0);
	    curl_setopt($curl_connection, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1)");
	    curl_setopt($curl_connection, CURLOPT_RETURNTRANSFER, true);
	    curl_setopt($curl_connection, CURLOPT_SSL_VERIFYPEER, false);
	    curl_setopt($curl_connection, CURLOPT_FOLLOWLOCATION, 1);

		curl_setopt($curl_connection, CURLOPT_POSTFIELDS, $post_string);

		$result = curl_exec($curl_connection);

		$first = strpos($result, "E-mail address is valid");
		if ($first !== false)
		{
			echo '<td><div class="circle center-align" style="background:#00DD00"></div></td>';
			echo "<td>".substr($result, $first, 23)."</td>";
			$arrMails[$key]["valido"] = "Si";
		}

		$first = strpos($result, "Server doesn't allow e-mail address verification");
		if ($first !== false)
		{
			echo '<td><div class="circle center-align" style="background:#FFBB00"></div></td>';
			echo "<td>".substr($result, $first, 48)."</td>";
			$arrMails[$key]["valido"] = "Si";
		}

		$first = strpos($result, "E-mail address does not exist on this server");
		if ($first !== false)
		{
			echo '<td><div class="circle center-align" style="background:#FF4444"></div></td>';
			echo "<td>".substr($result, $first, 44)."</td>";
			$arrMails[$key]["valido"] = "No";
		}

		curl_close($curl_connection);
	}

//----------------------------------------------------------------------

	if($_POST['action'] == 'call_this')
	{
		$arrMails = unserialize($_POST["mails"]);
		bajarExcel($arrMails);
	}

	function cleanData(&$str)
	{
		$str = preg_replace("/\t/", "\\t", $str);
		$str = preg_replace("/\r?\n/", "\\n", $str);
		if(strstr($str, '"')) $str = '"' . str_replace('"', '""', $str) . '"';
	}


	function bajarExcel($arrMails)
	{	
		$filename = "robotMail_" . date('Ymd') . ".xls";

		header("Content-Disposition: attachment; filename=\"$filename\"");
		header("Content-Type: application/vnd.ms-excel");

		$flag = false;
		foreach($arrMails as $row)
		{
			if(!$flag)
			{
				echo implode("\t", array_keys($row)) . "\n";
				$flag = true;
			}
			array_walk($row, __NAMESPACE__ . '\cleanData');
			echo implode("\t", array_values($row)) . "\n";
		}

		exit;
	}

//----------------------------------------------------------------------

?>

<style>
	.circle{
		width: 30px;
		height: 30px;
		border-radius: 50px;
	}
</style>

<div class="container" style="margin-bottom:50px;">
	<div class="row">
		<div class="col s6">
			<a href="/" class="waves-effect waves-light btn">
				Regresar
				<i class="material-icons right">undo</i>
			</a>
		</div>
		<div class="col s6">
			<button class="btn waves-effect waves-light right" id="btnTrigger">
				Excel
				<i class="material-icons right">file_download</i>
			</button>
		</div>
	</div>
	<div class="row">
		<div class="col s12">
			<table class="highlight">
				<thead>
					<tr>
						<th>Número</th>
						<th>Mail</th>
						<th>Valido</th>
						<th>Mensaje</th>
					</tr>
				</thead>

				<tbody>
					<?php foreach ($arrMails as $key => $mail): ?>
						<?php set_time_limit(0); ?>
						<tr>
							<td><?php echo $mail["numero"]; ?></td>
							<td><?php echo $mail["mail"]; ?></td>
							<?php validarMail($mail["mail"], $key, $arrMails); ?>
						</tr>
					<?php endforeach ?>
				</tbody>
			</table>
		</div>
	</div>
	<div class="row">
		<div class="col s6">
			<a href="/" class="waves-effect waves-light btn">
				Regresar
				<i class="material-icons right">undo</i>
			</a>
		</div>
		<form method="post" action="robotmail" class="col s6">
			<input type="hidden" name="mails" value='<?php echo serialize($arrMails); ?>'>
			<button class="btn waves-effect waves-light right" type="submit" name="action" value="call_this" id="btnSubmit">
				Excel
				<i class="material-icons right">file_download</i>
			</button>
		</form>
	</div>
</div>
<script type="text/javascript"></script>
<?php $this->Html->scriptStart(array('inline' => false)); ?>

	$( "#btnTrigger" ).on( "click", function() {
		$( "#btnSubmit" ).trigger( "click" );
	});

<?php $this->Html->scriptEnd(); ?>