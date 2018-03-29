<?php
//error_reporting(E_ERROR | E_WARNING | E_PARSE);
// This file will handle all functions used by Pomfe's Moe Panel
session_start();
require_once 'database.inc.php';

function register($email, $pass)
{
    global $db;
	    $hash = password_hash($pass, PASSWORD_DEFAULT);
        $do = $db->prepare("INSERT INTO accounts (email, pass, level) VALUES (:email, :pass, 0)");
        $do->bindParam(':email', $email);
        $do->bindParam(':pass', $hash);
        $do->execute();
        $_SESSION['email'] = $email;
        header('Location: api.php?do=cp');
}

function generateRandomString()
{
    $characters = ID_CHARSET;
    $randomString = '';
    for ($i = 0; $i < LENGTH; $i++) {
        $randomString .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $randomString;
}

function login($email, $pass)
{
    global $db;
    $do = $db->prepare("SELECT pass, id, email, level FROM accounts WHERE email = (:email)");
    $do->bindParam(':email', $email);
    $do->execute();
    $result = $do->fetch(PDO::FETCH_ASSOC);

    if (password_verify($pass, $result['pass'])) {
        $_SESSION['id'] = $result['id'];
        $_SESSION['email'] = $result['email'];
        $_SESSION['level'] = $result['level'];
        header('Location: api.php?do=cp');
    } else {
        header('Location: ../login/index.php#fail');
    }
}

function cfdelete($file)
{
    $cloudflare_string = null;
    $cloudflare = array(
        'a' => 'zone_file_purge',
        'tkn' => CF_TOKEN,
        'email' => CF_EMAIL,
        'z' => POMF_URL,
        'url' => urlencode(POMF_URL.$file),
    );

    foreach ($cloudflare as $x => $y) {
        $cloudflare_string .= $x.'='.$y.'&';
    }

    rtrim($cloudflare_string, '&');

    $hue = curl_init();
    curl_setopt($hue, CURLOPT_URL, 'https://www.cloudflare.com/api_json.html');
    curl_setopt($hue, CURLOPT_POST, count($cloudflare));
    curl_setopt($hue, CURLOPT_POSTFIELDS, $cloudflare_string);
    curl_setopt($hue, CURLOPT_RETURNTRANSFER, true);
    curl_exec($hue);
    curl_close($hue);
}

function delete($filename, $deleteid)
{
    if (empty($filename)) {
        echo "You did something wrong, baka.";
    } else {
        global $db;
        $do = $db->prepare("SELECT filename, delid, id, user FROM files WHERE filename = (:filename)");
        $do->bindParam(':filename', $filename);
        $do->execute();
        $result = $do->fetch(PDO::FETCH_ASSOC);

        if ($_SESSION['level'] === '1' || $result['user'] === $_SESSION['email']) {
            $do = $db->prepare("DELETE FROM files WHERE id = (:id)");
            $do->bindParam(':id', $result['email']);
            $do->execute();
            unlink(POMF_FILES_ROOT.$filename);
            cfdelete($filename);
            echo "<br/>File deleted and hopefully deleted from Cloudflares cache in a moment..<br/>";
        } else {
            echo 'Shame on you';
        }
    }
}

function fetchUserFiles($user) {
    if ($user == "*") {

    } else {
        global $db;
        $do = $db->prepare("SELECT * FROM files WHERE user = (:userid) ORDER BY id DESC");
        $do->bindParam(":userid", $user);
        require('../templates/search.php');
        $do->execute();
        $i = 0;
        while ($row = $do->fetch(PDO::FETCH_ASSOC)) {
            $i++;
            echo '<tr><td>'.$row['id'].'</td>
            <td>'.strip_tags($row['originalname']).'</td>
            <td><a href="'.POMF_URL.$row['filename'].'" target="_BLANK">'.$row['filename'].'</a> ('.$row['originalname'].')</td>
            <td>'.$row['size'].'</td>
            <td><a class="btn btn-default" href="'.MOE_URL.'/includes/api.php?do=delete&action=remove&fileid='.$row['id'].'&filename='.$row['filename'].'" target="_BLANK">Remove</a></td></tr>';
        }
        echo '</table>';
        require('../templates/footer.php');
        if ($i == 0) {
            echo '<p>No files found.</p>';
        } else {
            echo '<p>'.$i.' files found.</p>';
        }
    }
}

function fetchFiles($date, $count, $keyword)
{
    global $db;
	if ($date == null && $count == null && $keyword == null) {
			$do = $db->prepare("SELECT * FROM files WHERE user = (:userid) ORDER BY id DESC LIMIT 0");
			$do->bindValue(':userid', $_SESSION['email']);
	} else {
		if ($_SESSION['level'] > '0') {
		    echo "Admin";
			$do = $db->prepare("SELECT * FROM files WHERE originalname LIKE (:keyword) AND date LIKE (:date) OR filename LIKE (:keyword) AND date LIKE (:date) ORDER BY id DESC LIMIT 0,:amount");
		} else {
			$do = $db->prepare("SELECT * FROM files WHERE originalname LIKE (:keyword) AND date LIKE (:date) AND user = (:userid) OR filename LIKE (:keyword) AND date LIKE (:date) AND user = (:userid) ORDER BY id DESC LIMIT 0,:amount");
			$do->bindValue(':userid', $_SESSION['email']);
		}
		$do->bindValue(':date', "%".$date."%");
		$do->bindValue(':keyword', "%".$keyword."%");
		$do->bindValue(':amount', (int) $count, PDO::PARAM_INT);
	}

    require('../templates/search.php');
	$do->execute();
    $i = 0;
    while ($row = $do->fetch(PDO::FETCH_ASSOC)) {
        $i++;
        echo '<tr><td>'.$row['id'].'</td>
            <td>'.strip_tags($row['originalname']).'</td>
            <td><a href="'.POMF_URL.$row['filename'].'" target="_BLANK">'.$row['filename'].'</a> ('.$row['originalname'].')</td>
            <td>'.$row['size'].'</td>
            <td><a class="btn btn-default" href="'.MOE_URL.'/includes/api.php?do=delete&action=remove&fileid='.$row['id'].'&filename='.$row['filename'].'" target="_BLANK">Remove</a></td></tr>';
    }
    echo '</table>';
    require('../templates/footer.php');
	if ($i == 0) {
		echo '<p>No files found.</p>';
	} else {
		echo '<p>'.$i.' files found.</p>';
	}
}

function dbInsertFile($path) {
	global $db;
	$hash = sha1_file($path);
	$size = filesize($path);
	$originalName = pathinfo($path, PATHINFO_FILENAME);
	$dir = str_replace($originalName, "", $path);
	$date = date_default_timezone_get();
	$user = $_SESSION['email'];
	
	$extension = pathinfo($path, PATHINFO_EXTENSION);
	
	do {
		// Iterate until we reach the maximum number of retries
		if ($tries-- == 0) throw new Exception('Phew! It\'s exhausting trying to find an unused name.', 500);

		$chars = 'abcdefghijklmnopqrstuvwxyz';

		$name  = '';

		for ($i = 0; $i < 7; $i++) {

			$name .= $chars[mt_rand(0, 25)];

			// $chars string length is hardcoded, should use a variable to store it?
		}

		// Add the extension to the file name

		if (isset($ext) && $ext !== '')

			$name .= '.' . strip_tags($extension);

		// Check if a file with the same name does already exist in the database

		$q = $db->prepare('SELECT COUNT(name) FROM files WHERE name = (:name)');

		$q->bindValue(':name', $name, PDO::PARAM_STR);

		$q->execute();

		$result = $q->fetchColumn();

	// If it does, generate a new name

	} while($result > 0);
	
	$newName = $name;
}

function report($file)
{
    global $db;
    if (empty($file)) {
        include('../templates/report.php');
    } else {
        $do = $db->prepare("select id, hash from files where filename = :file");
        $do->bindValue(':file', strip_tags($file));
        $do->execute();
        $query = $do->fetch(PDO::FETCH_ASSOC);

        $do = $db->prepare("INSERT INTO reports (hash, date, file, fileid, reporter) VALUES (:hash, :date, :file, :fileid, :reporter)");
        $do->bindValue(':file', strip_tags($file));
        $do->bindValue(':date', date('Y-m-d'));
        $do->bindValue(':reporter', $_SESSION['email']);
        $do->bindValue(':fileid', $query['id']);
        $do->bindValue(':hash', $query['hash']);
        $do->execute();
        echo 'Thank you, report has been sent. The file will be reviewed and probably deleted.';
    }

}

function mod($action, $date, $count, $why, $file, $keyword, $fileid, $hash, $orginalname)
{
    if ($_SESSION['level'] > '0') {
        global $db;
        switch ($action) {
            case "fetch":
                fetchFiles($date, $count, $keyword);
                break;

            case "report":
                report($file, $fileid, $count);
                break;

            case "reports":
                if ($_SESSION['id'] === '1') {
                    $do = $db->prepare("SELECT * FROM reports WHERE status = '0'");
                    $do->execute();

                    $i = 0;
                    require('../templates/reports.php');
                    while ($row = $do->fetch(PDO::FETCH_ASSOC)) {
                        $i++;
                        echo '<tr><td>'.$row['id'].'</td>
                        <td><a href="'.POMF_URL.strip_tags($row['file']).'" target="_BLANK">'.strip_tags($row['file']).'</td>
                        <td>'.$row['fileid'].'</td>
                        <td>'.$row['reporter'].'</td>
                        <td>'.$row['status'].'</td>
                        <td><a class="btn btn-default" href="'.MOE_URL.'/includes/api.php?do=mod&action=remove&fileid='.$row['fileid'].'&file='.$row['file'].'" target="_BLANK">Remove file</a></td></tr>';
                    }
                    echo '</table>';
                    require 'footer.php';
                    echo $i.' Reports in total at being shown.';
                } else {
                    echo 'You are not allowed to be here, yet.';
                }
                break;

            case "remove":
                if ($_SESSION['id'] < '0') {
                    delete($file, $fileid);
                }
                if ($_SESSION['id'] > '0') {
                    $do = $db->prepare("DELETE FROM files WHERE id = (:id)");
                    $do->bindParam(':id', $fileid);
                    $do->execute();
                    unlink(POMF_FILES_ROOT.$file);
                    cfdelete($file);
                    $do = $db->prepare("UPDATE reports SET status = (:status) WHERE fileid = (:fileid)");
                    $do->bindValue(':status', '1');
                    $do->bindValue(':fileid', $fileid);
                    $do->execute();
                    echo 'Deleted';
                    break;
                }
        }
    }
}