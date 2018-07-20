<?PHP
    header("Access-Control-Allow-Headers: X-Requested-With");
//    header("Access-Control-max-age: 0");
	header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET, POST");
    header("Vary: Accept-Encoding");
	if ( !isset($SERVER_ROOT) ) $SERVER_ROOT = '../../../';
	//if ( !isset($_REQUEST['grade_input']) ) exit('');

// Because handling form requests is easier with PHP.
// Return the AJAX request

function error_out($slug)
{
    $error_url = '';
    header('Location: ' . $error_url . $slug);
    die();
}

if ( !isset($_POST) ) error_out('nopost');


// Validate input.
// If we're taking input from a non-javascript enabled browser, we check that out here:
$is_ajax = FALSE;
if ( isset($_POST['grade_select']) ) $grade = intval($_POST['grade_select']);
if ( isset($_SERVER['HTTP_ORIGIN']) )
{
    $is_ajax = TRUE;
    $grade = intval($_POST['grade_input']);
}

// Make sure there are POST fields for the submit_x and submit_y values
//if ( $is_ajax === FALSE && intval($_POST['x']) == 0 && intval($_POST['y']) == 0 ) error_out('noxy');
//if ( strpos('nydailynews', $_SERVER["HTTP_REFERER"]) === FALSE ) error_out('referer');
if ( $grade < 0 ) header('Location: ' . $_SERVER['HTTP_REFERER'] . '?source=err_novote');


// Passed the security checks. Now we add the record to the database.
$slug = htmlspecialchars(str_replace('_', '-', $_POST['slug']));
require_once ($SERVER_ROOT . 'includes/php/mysql_connect_staging.php');

/*
$query = 'SELECT * FROM ' . $TABLE . ' WHERE id = "' . $player_id . '" LIMIT 1';
$result = @mysql_query($query);

while ($row = mysql_fetch_array($result, MYSQL_ASSOC)):
	    $yes = $row['keep'];
    $no = $row['dump'];
endwhile;

$update = 'UPDATE Interactive.' . $TABLE . ' SET ' . $verb . '=' . $value . ' WHERE id = "' . $player_id . '" LIMIT 1';
$result = @mysql_query($update);
*/
// Figure out which card we're inserting for:
$sql = 'SELECT id FROM report_card WHERE slug = "' . $slug . '" LIMIT 1';
$result = mysql_query($sql);
$row = mysql_fetch_row($result);
$card_id = $row[0];

// Insert the grade
if ( $grade != -1 ):
    $sql = 'INSERT INTO report_card_grade (card_id, grade, ip) VALUES (' . $card_id . ', ' . $grade . ', "' . $_SERVER['X_FORWARDED_FOR'] . '")';
    $result = mysql_query($sql);
endif;

// Compute the current grade results
$sql = 'SELECT count(grade) as voters, AVG(grade) as grade FROM report_card_grade WHERE card_id = ' . $card_id;
$result = mysql_query($sql);
$row = @mysql_fetch_row($result);
if ( !isset($row) ) die();
$voters = $row[0];
$voters_midpoint = floor($voters/2);
$grade_mean = $row[1];

// Now get the letter-grade distribution:
$sql = 'SELECT COUNT(*) as count, grade FROM report_card_grade WHERE card_id = ' . $card_id . ' GROUP BY grade ORDER BY grade DESC';
$result = mysql_query($sql);
$letters = array(
    'a' => 0,
    'b' => 0,
    'c' => 0,
    'd' => 0,
    'f' => 0);
while ( $row = mysql_fetch_array($result, MYSQL_ASSOC)):
    $letter = 'e';
    switch ( $row['grade'] ):
        case '4':
            $letter = 'a';
            break;
        case '3':
            $letter = 'b';
            break;
        case '2':
            $letter = 'c';
            break;
        case '1':
            $letter = 'd';
            break;
        case '0':
            $letter = 'f';
            break;
    endswitch;
    $letters[$letter] = $row['count'];
endwhile;

//echo $grade_average . ',' . $voters;
//echo $grade_average . ',' . $voters . ',0,0,0,0,0';
echo $grade_mean . ',' . $voters . ',' . $letters['a'] . ',' . $letters['b'] . ',' . $letters['c'] . ',' . $letters['d'] . ',' . $letters['f'];


//echo '<pre>';
//var_dump($_POST);
//var_dump($_SERVER);
