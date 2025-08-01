<?php

include_once('CCAMS.php');
if (array_key_exists('debug',$_GET)) {
	$CCAMS = new CCAMS(true);
	$CCAMSstats = new CCAMSstats(true);
} else {
	$CCAMS = new CCAMS();
	$CCAMSstats = new CCAMSstats();
}

if (array_key_exists('r',$_GET)) {
	if (array_key_exists('date', $_POST)) $seldate = new DateTime($_POST['date']);
	else $seldate = new DateTime('now');

	switch ($_GET['r']) {
		case 'get-ranges':
			echo $CCAMS->get_sqwk_ranges();
			break;
		case 'set-ranges':
			$CCAMS->set_sqwk_range($_POST['rangename'],$_POST['ranges']);
			//$CCAMS->set_squawk_range($_GET['rangename'],$_GET['ranges']);
			break;
		case 'squawks':
			echo $CCAMS->get_reserved_codes();
			break;
		case 'logfiles':
			echo $CCAMS->get_logs();
			break;
		case 'stats-daily':
			$CCAMSstats->readStats($seldate);
			echo $CCAMSstats->createStats();
			break;
		case 'stats-weekly':
			$date = clone $seldate;
			$date->sub(new DateInterval('P'.$date->format('N').'D'));
			do {
				$date->add(new DateInterval('P1D'));
				$CCAMSstats->readStats($date);
			} while ($date->format('N') < 7);
		//echo var_dump($seldate->diff($date)->days);
			echo $CCAMSstats->createStats();
			break;
		case 'stats-monthly':
			$date = clone $seldate;
			$date->sub(new DateInterval('P'.$date->format('j').'D'));
			while ($date->add(new DateInterval('P1D'))->format('n') == $seldate->format('n')) {
				$CCAMSstats->readStats($date);
			}
			echo $CCAMSstats->createStats();
			break;
		case 'stats-yearly':

			break;
		default:
			echo json_encode(array());
			break;
	}
}
?>