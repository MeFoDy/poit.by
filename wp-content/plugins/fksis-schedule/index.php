<?php
/*
Plugin Name: FKSiS Schedule Updater
Plugin URI: http://wordpress.org
Description: Расписание ФКСиС
Author: Dark_MeFoDy
*/

// Hook for adding admin menus
add_action('admin_menu', 'mt_add_pages');

// action function for above hook
function mt_add_pages() {
    add_management_page('Расписание ФКСиС', 'Расписание ФКСиС', 8, 'testmanage', 'mt_manage_page');
    add_menu_page('Расписание ФКСиС', 'Расписание ФКСиС', 8, __FILE__, 'mt_toplevel_page');
}

function mt_manage_page() {
    echo "<h2>FKSiS Schedule Updater</h2>";
}

function mt_toplevel_page() {
    $filename = site_url() . '/schedule/schedule.json';
    $message = "<p>Расписание хранится в формате json по адресу '/wp-content/plugins/fksis-schedule/schedule.json'</p>";

    if (isset($_POST['update'])) {
        $result = generateScheduleFile();
        if ($result) {
            $message .= "<p style='color: green;'>Обновление прошло успешно</p>";
        } else {
            $message .= "<p style='color: red;'>Произошла ошибка обновления. Попробуйсте позже либо свяжитесь с разработчиком сайта.</p>";
        }
    } elseif (isset($_POST['restore'])) {
        $result = restoreBackup();
        if ($result) {
            $message .= "<p style='color: green;'>Восстановление прошло успешно</p>";
        } else {
            $message .= "<p style='color: red;'>Произошла ошибка восстановления. Попробуйсте позже либо свяжитесь с разработчиком сайта.</p>";
        }
    }

    echo <<<EOF
    <h2>Расписание ФКСиС</h2>
    $message<br><br>
    <form action="" method="POST" role="form">
    <button type="submit" class="btn btn-primary" name="update">Обновить расписание</button>
    </form>
    <form action="" method="POST" role="form">
    <button type="submit" class="btn btn-primary" name="restore">Восстановить расписание из бэкапа</button>
    </form>
EOF;
}






function xmlToArray($xmlString) {
    $xml = simplexml_load_string($xmlString);
    $json = json_encode($xml);
    $array = json_decode($json, TRUE);
    return $array;
}

function getScheduleGroupOnline($group) {
    $group = preg_replace('/[^\d]/', "", $group);

    $url = "http://bsuir.by/psched/rest/" . $group;
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_FAILONERROR, 1);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);

    $result['content'] = curl_exec($ch);
    $result['errorno'] = curl_errno($ch);
    $result['error'] = curl_error($ch);
    if ($result['errorno']) {
        //print curl_error($ch) . '('.$url.')';
        return false;
    }
    curl_close($ch);

    $result['content'] = xmlToArray($result['content']);
    return $result;
}

function prepareGroup($group) {
    return preg_replace('/[^0-9]/', "", $group);
}

function getScheduleYears() {
    $currentYear = date('Y');
    $currentStart = mktime(0, 0, 0, 9, 1, $currentYear);
    $nowWeek = time();
    if ($currentStart > $nowWeek)
        $currentYear--;
    $currentYear = substr($currentYear, -1);

    $result = array();
    for ($i=0; $i < 5; $i++) { 
        $result[] = ($currentYear - $i + 10) % 10;
    }
    return $result;
}

function getScheduleCodes() {
    return array(
        "510", "535", "520", "505"
        );
}

function canWriteToFile($filename) {
    if (file_exists($filename) && is_writable($filename)) {
        if (!rename($filename, $filename . "b")) {
            return false;
        }
        $handle = fopen($filename, "w");
        if ($handle === false) {
            return false;
        }
        fclose($handle);
    } else {
        $handle = fopen($filename, "w");
        if ($handle === false) {
            return false;
        }
        fclose($handle);
    }
    return true;
}

function writeToFile($filename, $obj) {
    $content = json_encode($obj);
    if (is_writable($filename)) {
        if (!$handle = fopen($filename, 'w')) {
            return false;
        }
        if (fwrite($handle, $content) === FALSE) {
            return false;
        }
        fclose($handle);
        return true;
    } else {
        return false;
    }
}

function restoreBackup() {
    $to = dirname(__FILE__) . '/schedule.json';
    $from = dirname(__FILE__) . '/schedule.jsonb';
    return copy($from, $to);
}

function generateScheduleFile() {
    $filename = dirname(__FILE__) . '/schedule.json';
    if (!canWriteToFile( $filename )) {
        // File access error
        return false;
    }

    $years = getScheduleYears();
    $codes = getScheduleCodes();
    $output = array();

    foreach ($years as $year) {
        foreach ($codes as $code) { 
            $groupBase = $year . $code . "0";

            for ($i=1; $i < 10; $i++) { 
                $group = prepareGroup( $groupBase . $i );
                $result = getScheduleGroupOnline( $group );
                if ($result === false)
                    break;

                foreach ($result['content']['ROW'] as $value) {
                    $attr = $value['@attributes'];
                    array_push($output, array(
                        'group' => $group,
                        'subgroup' => $attr['subgroup'],
                        'weekDay' => $attr['weekDay'],
                        'timePeriod' => $attr['timePeriod'],
                        'weekList' => $attr['weekList'],
                        'subject' => $attr['subject'],
                        'subjectType' => $attr['subjectType'],
                        'auditorium' => $attr['auditorium'],
                        'teacher' => $attr['teacher']
                        ));
                }
            }

        }
    }
    if (empty($output)) {
        // Connection problems
        return false;
    }
    if (!writeToFile($filename, $output)) {
        // File writing error
        return false;
    }
    return true;
}

?>