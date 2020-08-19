<?php
include_once __DIR__ . "/functions.php";

$recordTypes = array( 'A', 'AAAA', 'MX', 'ANAME', 'CNAME', 'NS', 'TXT', 'SRV' );
?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport"
		  content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>Websupport Add Record</title>
	<link rel="stylesheet" href="assets/css/styles.css"/>
</head>
<body>
<header>
	<h1><a href="index.php">Websupport DNS</a></h1>
</header>
<div class="container">

	<?php showNotice(); ?>

	<form method="post" action="process-record.php" class="add-record">
		<h2>Add record to DNS</h2>
		<div class="form-group">
			<label for="type">Type: <sup>*</sup></label>
			<select id="type" name="type">
				<?php foreach ( $recordTypes as $type ): ?>
					<option value="<?= $type ?>"><?= $type ?></option>
				<?php endforeach; ?>
			</select>
		</div>

		<div class="form-group">
			<label for="address">Address: <sup>*</sup></label>
			<input type="text" name="address" id="address"/>
		</div>

		<div class="form-group">
			<label for="content">Target address: <sup>*</sup></label>
			<input type="text" name="content" id="content"/>
		</div>

		<div class="form-group">
			<label for="ttl">TTL:</label>
			<input type="number" step="1" name="ttl" id="ttl"/>
		</div>

		<div class="form-group" id="prio-group">
			<label for="prio">Priority: <sup>*</sup></label>
			<input type="number" step="1" name="prio" id="prio"/>
		</div>

		<div class="form-group" id="port-group">
			<label for="port">Port: <sup>*</sup></label>
			<input type="number" step="1" name="port" id="port"/>
		</div>

		<div class="form-group" id="weight-group">
			<label for="weight">Weight: <sup>*</sup></label>
			<input type="number" step="1" name="weight" id="weight"/>
		</div>

		<div class="form-group">
			<input type="submit" name="submit" value="Add record" class="button"/>
		</div>
	</form>
</div>
<script src="assets/js/add-record.js"></script>
</body>
</html>
