id: 12
source: 1
name: EmailQueue
description: 'Хук для FormIt/AjaxForm. Авторы: Евгений Дурягин.'
category: EmailQueue
properties: "a:6:{s:3:\"tpl\";a:7:{s:4:\"name\";s:3:\"tpl\";s:4:\"desc\";s:19:\"emailqueue_prop_tpl\";s:4:\"type\";s:9:\"textfield\";s:7:\"options\";a:0:{}s:5:\"value\";s:19:\"tpl.EmailQueue.item\";s:7:\"lexicon\";s:21:\"emailqueue:properties\";s:4:\"area\";s:0:\"\";}s:6:\"sortby\";a:7:{s:4:\"name\";s:6:\"sortby\";s:4:\"desc\";s:22:\"emailqueue_prop_sortby\";s:4:\"type\";s:9:\"textfield\";s:7:\"options\";a:0:{}s:5:\"value\";s:4:\"name\";s:7:\"lexicon\";s:21:\"emailqueue:properties\";s:4:\"area\";s:0:\"\";}s:7:\"sortdir\";a:7:{s:4:\"name\";s:7:\"sortdir\";s:4:\"desc\";s:23:\"emailqueue_prop_sortdir\";s:4:\"type\";s:4:\"list\";s:7:\"options\";a:2:{i:0;a:2:{s:4:\"text\";s:3:\"ASC\";s:5:\"value\";s:3:\"ASC\";}i:1;a:2:{s:4:\"text\";s:4:\"DESC\";s:5:\"value\";s:4:\"DESC\";}}s:5:\"value\";s:3:\"ASC\";s:7:\"lexicon\";s:21:\"emailqueue:properties\";s:4:\"area\";s:0:\"\";}s:5:\"limit\";a:7:{s:4:\"name\";s:5:\"limit\";s:4:\"desc\";s:21:\"emailqueue_prop_limit\";s:4:\"type\";s:11:\"numberfield\";s:7:\"options\";a:0:{}s:5:\"value\";i:10;s:7:\"lexicon\";s:21:\"emailqueue:properties\";s:4:\"area\";s:0:\"\";}s:15:\"outputSeparator\";a:7:{s:4:\"name\";s:15:\"outputSeparator\";s:4:\"desc\";s:31:\"emailqueue_prop_outputSeparator\";s:4:\"type\";s:9:\"textfield\";s:7:\"options\";a:0:{}s:5:\"value\";s:1:\"\n\";s:7:\"lexicon\";s:21:\"emailqueue:properties\";s:4:\"area\";s:0:\"\";}s:13:\"toPlaceholder\";a:7:{s:4:\"name\";s:13:\"toPlaceholder\";s:4:\"desc\";s:29:\"emailqueue_prop_toPlaceholder\";s:4:\"type\";s:13:\"combo-boolean\";s:7:\"options\";a:0:{}s:5:\"value\";b:0;s:7:\"lexicon\";s:21:\"emailqueue:properties\";s:4:\"area\";s:0:\"\";}}"
static_file: core/components/emailqueue/elements/snippets/snippet.emailqueue.php

-----

/*
Cниппет для drop-in replacement замены хука email для FormIt/AjaxForm
Просто в вызове поменять хук на EmailQueue
[[!Formit?
    &hooks=`EmailQueue`
    &emailTpl=`feedbackEmail`
    &emailTo=`test@mail.ru`
    &emailSubject=`Сообщение с сайта`
    &replyTo=`[[+email]]`
    &emailFrom=`[[++emailsender]]`
]]
Поддержку вложений не делал, т.к. не было необходимости.
И не все параметры FormIt поддерживает (например emailHtml, emailToName, emailCC, итд), т.к. компонент их не учитывает.


Авторы: Евгений Дурягин.
*/
$fields = $hook->getValues();

$tpl = $modx->getOption('emailTpl', $hook->formit->config, '');
/* get from name */
$emailFrom = $modx->getOption('emailFrom', $hook->formit->config, '');
if (empty($emailFrom)) {
    $emailFrom = !empty($fields['email']) ? $fields['email'] : $modx->getOption('emailsender');
}
$emailFrom = $hook->_process($emailFrom, $fields);
$emailFromName = $modx->getOption('emailFromName', $hook->formit->config, $modx->getOption('site_name', null, $emailFrom));
$emailFromName = $hook->_process($emailFromName, $fields);

/* get subject */
$useEmailFieldForSubject = $modx->getOption('emailUseFieldForSubject', $hook->formit->config, true);
if (!empty($fields['subject']) && $useEmailFieldForSubject) {
    $subject = $fields['subject'];
} else {
    $subject = $modx->getOption('emailSubject', $hook->formit->config, '');
}
$subject = $hook->_process($subject, $fields);
/* check email to */
$emailTo = $modx->getOption('emailTo', $hook->formit->config, '');
$emailToName = $modx->getOption('emailToName', $hook->formit->config, $emailTo);
if (empty($emailTo)) {
    $hook->errors['emailTo'] = $modx->lexicon('formit.email_no_recipient');
    $modx->log(\modX::LOG_LEVEL_ERROR, '[FormIt] '.$modx->lexicon('formit.email_no_recipient'));
    return false;
}
/* compile message */
$origFields = $fields;
if (empty($tpl)) {
    $tpl = 'fiDefaultEmailTpl';
    $f = [];
    $multiSeparator = $modx->getOption('emailMultiSeparator', $hook->formit->config, "\n");
    $multiWrapper = $modx->getOption('emailMultiWrapper', $hook->formit->config, "[[+value]]");
    foreach ($fields as $k => $v) {
        if ($k == 'nospam') {
            continue;
        }
        if (is_array($v) && !empty($v['name']) && isset($v['error']) && $v['error'] == UPLOAD_ERR_OK) {
            $v = $v['name'];
            $f[$k] = '<strong>'.$k.'</strong>: '.$v.'<br />';
        } elseif (is_array($v)) {
            $vOpts = array();
            foreach ($v as $vKey => $vValue) {
                if (is_string($vKey) && !empty($vKey)) {
                    $vKey = $k.'.'.$vKey;
                    $f[$vKey] = '<strong>'.$vKey.'</strong>: '.$vValue.'<br />';
                } else {
                    $vOpts[] = str_replace('[[+value]]', $vValue, $multiWrapper);
                }
            }
            $newValue = implode($multiSeparator, $vOpts);
            if (!empty($vOpts)) {
                $f[$k] = '<strong>'.$k.'</strong>:'.$newValue.'<br />';
            }
        } else {
            $f[$k] = '<strong>'.$k.'</strong>: '.$v.'<br />';
        }
    }
    $fields['fields'] = implode("\n", $f);
} else {
    /* handle file/checkboxes in email tpl */
    $multiSeparator = $modx->getOption('emailMultiSeparator', $hook->formit->config, "\n");
    if (empty($multiSeparator)) {
        $multiSeparator = "\n";
    }
    if ($multiSeparator == '\n') {
        $multiSeparator = "\n"; /* allow for inputted newlines */
    }
    $multiWrapper = $modx->getOption('emailMultiWrapper', $hook->formit->config, "[[+value]]");
    if (empty($multiWrapper)) {
        $multiWrapper = '[[+value]]';
    }
    foreach ($fields as $k => &$v) {
        if (is_array($v) && !empty($v['name']) && isset($v['error']) && $v['error'] == UPLOAD_ERR_OK) {
            $v = $v['name'];
        } elseif (is_array($v)) {
            $vOpts = array();
            foreach ($v as $vKey => $vValue) {
                if (is_string($vKey) && !empty($vKey)) {
                    $vKey = $k.'.'.$vKey;
                    $fields[$vKey] = $vValue;
                    unset($fields[$k]);
                } else {
                    $vOpts[] = str_replace('[[+value]]', $vValue, $multiWrapper);
                }
            }
            $v = implode($multiSeparator, $vOpts);
            if (!empty($vOpts)) {
                $fields[$k] = $v;
            }
        }
    }
}
$message = $hook->formit->getChunk($tpl, $fields);
$message = $hook->_process($message, $hook->formit->config);


if (!$EmailQueue = $modx->getService('emailqueue', 'EmailQueue', $modx->getOption('emailqueue_core_path', null,
	$modx->getOption('core_path') . 'components/emailqueue/') . 'model/emailqueue/', array())) {
	$hook->addError('email', 'При отправке произошла ошибка');
	return false;
}

$data = array(
	'sender_package' => 'FormIt',
	'from' => $emailFrom,
	'from_name' => $emailFromName,
	'subject' => $subject,
	'body' => $message,
	'date' => date("Y-m-d H:i:s"),
);

$emailReplyTo = $modx->getOption('emailReplyTo', $hook->formit->config, '');
if (!empty($emailReplyTo)) {
    $data['reply_to'] = $hook->_process($emailReplyTo, $fields);
}
$emailTo = array_map('trim', explode(',', $emailTo));

foreach ($emailTo as $to) {
    $to = $hook->_process($to, $fields);
    if (!empty($to)) {
        $data['to'] = $to;
        $queue_email = $modx->newObject('EmailQueueItem');
        $queue_email->fromArray($data);
        $queue_email->save();
    }
}

return true;