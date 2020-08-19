<?php
/**
 * @var string $dnsDomain
 * @var string $restUrl
 * @var string $apiKey
 * @var string $apiSecret
 */
ini_set( "display_errors", "On" );
ini_set( "error_reporting", E_ALL );
include_once __DIR__ . "/config.php";
include_once __DIR__ . "/functions.php";
include_once __DIR__ . "/WebsupportRestApi.php";

$endpoint = "/user/self/zone/{$dnsDomain}/record/";

$rest = new WebsupportRestApi( $restUrl, $apiKey, $apiSecret );
?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport"
		  content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>Websupport DNS List</title>
	<link rel="stylesheet" href="assets/css/styles.css"/>
</head>
<body>
<header>
	<h1><a href="index.php">Websupport DNS</a></h1>
</header>
<div class="container">

	<?php
	showNotice();
	?>

	<div class="actions">
		<a href="add-record.php" class="button">Add record</a>
	</div>

	<div class="dns-list-wrapper">
		<?php
		try {
			$dnsRecords = $rest->get( "/user/self/zone/{$dnsDomain}/record/" );

			if ( isset( $dnsRecords->items ) ) {
				//Sort by type
				usort( $dnsRecords->items, function ( $a, $b ) {
					return strcmp( $a->type, $b->type );
				} );
				?>
				<table class="dns-list-table">
					<thead>
					<tr>
						<th class="type">Type</th>
						<th class="address">Address</th>
						<th class="target-address">Target address</th>
						<th class="ttl">TTL</th>
						<th class="note">Note</th>
						<th class="remove">Remove</th>
					</tr>
					</thead>
					<tbody>
					<?php
					foreach ( $dnsRecords->items as $item ) {
						?>
						<tr>
							<td class="type"><?= $item->type; ?></td>
							<td class="address"><?= $item->name; ?></td>
							<td class="target-address">
								<?php if ( $item->type === "MX" ): ?>
									<p><strong>Mail handled by: </strong><?= $item->content; ?></p>
									<p><strong>Priority: </strong><?= $item->prio; ?></p>
								<?php elseif ( $item->type === "SRV" ): ?>
									<p><strong>Points to: </strong><?= $item->content; ?></p>
									<p><strong>Priority: </strong><?= $item->prio; ?></p>
									<p><strong>Port: </strong><?= $item->port; ?></p>
									<p><strong>Weight: </strong><?= $item->weight; ?></p>
								<?php elseif ( $item->type === "TXT" ): ?>
									<p><?= $item->content; ?></p>
								<?php else: ?>
									<p><strong>Points to: </strong><?= $item->content; ?></p>
								<?php endif; ?>

							</td>
							<td class="ttl"><?= $item->ttl; ?></td>
							<td class="note"><?= $item->note; ?></td>
							<td class="remove"><a href="remove.php?id=<?php echo $item->id; ?>">x</a></td>
						</tr>
						<?php
					}
					?>
					</tbody>
				</table>
				<?php
			}

		} catch ( Exception $e ) {
			?>
			<div class="alert alert-danger">
				<?= $e->getMessage(); ?>
			</div>
			<?php
		}
		?>
	</div>
</div>

</body>
</html>
