id: 13
name: CKEditor
description: 'CKEditor WYSIWYG editor plugin for MODX2 and MODX3'
plugincode: "/**\r\n * CKEditor WYSIWYG Editor Plugin\r\n *\r\n * Events: OnManagerPageBeforeRender, OnRichTextEditorRegister, OnRichTextEditorInit, OnRichTextBrowserInit\r\n *\r\n * @var modX $modx\r\n * @author Danil Kostin <danya.postfactum(at)gmail.com>\r\n *\r\n * @package ckeditor\r\n */\r\n$enabled = $modx->getOption('which_editor', null, 'CKEditor') == 'CKEditor' && $modx->getOption('use_editor', null, true);\r\n\r\nswitch ($modx->event->name) {\r\n    case 'OnRichTextEditorRegister':\r\n        $modx->event->output('CKEditor');\r\n        break;\r\n    case 'OnManagerPageBeforeRender':\r\n        if ($enabled) {\r\n            /** @var CKEditor $ckeditor */\r\n            $ckeditor = $modx->getService('ckeditor', 'CKEditor', $modx->getOption('ckeditor.core_path', null, $modx->getOption('core_path').'components/ckeditor/') . 'model/ckeditor/');\r\n            $ckeditor->initialize();\r\n        }\r\n        break;\r\n    case 'OnRichTextEditorInit':\r\n        break;\r\n    case 'OnRichTextBrowserInit':\r\n        if ($enabled) {\r\n            $funcNum = $_REQUEST['CKEditorFuncNum'];\r\n            $modx->event->output(\"function(data){\r\n                window.parent.opener.CKEDITOR.tools.callFunction({$funcNum}, data.fullRelativeUrl);\r\n            }\");\r\n        }\r\n        break;\r\n}\r\n\r\nreturn;"
properties: null
static: 1
static_file: ckeditor/elements/plugins/ckeditor.plugin.php

-----


/**
 * CKEditor WYSIWYG Editor Plugin
 *
 * Events: OnManagerPageBeforeRender, OnRichTextEditorRegister, OnRichTextEditorInit, OnRichTextBrowserInit
 *
 * @var modX $modx
 * @author Danil Kostin <danya.postfactum(at)gmail.com>
 *
 * @package ckeditor
 */
$enabled = $modx->getOption('which_editor', null, 'CKEditor') == 'CKEditor' && $modx->getOption('use_editor', null, true);

switch ($modx->event->name) {
    case 'OnRichTextEditorRegister':
        $modx->event->output('CKEditor');
        break;
    case 'OnManagerPageBeforeRender':
        if ($enabled) {
            /** @var CKEditor $ckeditor */
            $ckeditor = $modx->getService('ckeditor', 'CKEditor', $modx->getOption('ckeditor.core_path', null, $modx->getOption('core_path').'components/ckeditor/') . 'model/ckeditor/');
            $ckeditor->initialize();
        }
        break;
    case 'OnRichTextEditorInit':
        break;
    case 'OnRichTextBrowserInit':
        if ($enabled) {
            $funcNum = $_REQUEST['CKEditorFuncNum'];
            $modx->event->output("function(data){
                window.parent.opener.CKEDITOR.tools.callFunction({$funcNum}, data.fullRelativeUrl);
            }");
        }
        break;
}

return;