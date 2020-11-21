<?php

if (isset($_POST['delete'])) {
    if (
        $stmt = $db->delete('budget', [
            'id' => $_POST['id'],
            'user_id' => $_SESSION['user_id'],
        ])
    ) {
        $msgBox = success($m_deletesuccess);
    } else {
        $msgBox = error($m_deleteerror);
    }
}

if (isset($_POST['create'])) {
    $dict = [
        'category' => '0',
        'maximum' => '0',
        'frequency' => '0',
    ];

    foreach ($dict as $key => $value) {
        if (
            (!isset($_POST[$key]) || trim($_POST[$key]) == '') &&
            $key != 'description'
        ) {
            $msgBox = error('Field ' . $key . ' error');
            break;
        } else {
            $dict[$key] = trim($_POST[$key]);
        }
    }

    // Custom time
    if (isset($_POST['manual-dates'])) {
        if (isset($_POST['from-date']) && isset($_POST['to-date']) && $_POST['from-date'] != '' && $_POST['to-date'] != '') {
            $dict['from-date'] = $_POST['from-date'];
            $dict['to-date'] = $_POST['to-date'];
        } else {
            $msgBox = error($m_missingdates);
        }
    } else {
        // Once
        $dict['from-date'] = null;
        $dict['to-date'] = null;
    }

    if (!isset($msgBox)) {
        try {
            $db->run(
                'CALL `Add budget`(?,?,?,?,?,?)',
                $_SESSION['user_id'],
                $dict['category'],
                $dict['maximum'],
                $dict['frequency'],
                $dict['from-date'],
                $dict['to-date']
            );
            $msgBox = success($m_addsuccess);
        } catch (PDOException $exception) {
            $error = $exception->getMessage();
            $error = ($pos = strpos($error, '1644 '))
                ? substr($error, $pos + 5)
                : $error;
            $msgBox = error($error);
        }
    }
}

if (isset($_POST['edit'])) {
    $dict = [
        'category' => '0',
        'maximum' => '0',
        'frequency' => '0',
        'from-date' => '0',
        'to-date' => '0',
    ];

    foreach ($dict as $key => $value) {
        if (
            (!isset($_POST[$key]) || trim($_POST[$key]) == '') &&
            $key != 'description'
        ) {
            $msgBox = error('Field ' . $key . ' error');
            break;
        } else {
            $dict[$key] = trim($_POST[$key]);
        }
    }

    if ( 
        $stmt = $db->update(
            'budget',
            [
                'category' => $dict['category'],
                'maximum' => $dict['maximum'],
                'frequency' => $dict['frequency'],
                'start_time' => $dict['from-date'],
                'end_time' => $dict['to-date'],
            ],
            ['id' => $_POST['id'], 'user_id' => $_SESSION['user_id']]
        )
    ) {
        $msgBox = success($m_savesuccess);
    } else  {
        $msgBox = error($m_saveerror);
    }

}

if ($_POST) {
    if (isset($msgBox)) {
        $_SESSION['msgBox'] = $msgBox;
    }
    header('Location: ?p=budget', true, 303);
}
exit();
?>
