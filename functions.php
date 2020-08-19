<?php
/**
 * @param string $msg
 * @param string $status
 */
function createNotice( $msg, $status ) {
	session_start();

	$_SESSION['notice'][] = [
		'status'  => $status,
		'message' => $msg
	];
}

/**
 * @return array
 */
function getNotices() {
	session_start();

	$notice = array();

	if ( isset( $_SESSION['notice'] ) && ! empty( $_SESSION['notice'] ) ) {
		$notice = $_SESSION['notice'];
		unset( $_SESSION['notice'] );
	}

	return $notice;
}

function showNotice() {
	$notices = getNotices();

	if ( $notices ) {
		$status = "success";

		foreach ($notices as $notice) {
			if ( $notice['status'] == 'error' ) {
				$status = "danger";
			}
			echo "<div class='notice-wrapper'>";
			echo "<div class='alert alert-" . $status . "'>" . $notice['message'] . "</div>";
			echo "</div>";
		}
	}
}
