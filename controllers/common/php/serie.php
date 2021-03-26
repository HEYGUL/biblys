<?php

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

$cm = new CycleManager();
$cycle = $cm->get(array('cycle_url' => $_GET['url']));

if (!$cycle) {
    throw new NotFoundHttpException();
}

$useOldController = $site->getOpt('use_old_cycle_controller');
if (!$useOldController) {
    return new RedirectResponse("/cycle/".$cycle->get("url"), 301);
}

$_PAGE_TITLE = 'La série &laquo; '.$cycle->get('name').' &raquo;';

$_ECHO .= '<h2>'.$_PAGE_TITLE.'</h2>';

if ($cycle->has('desc')) {
    $_ECHO .= '<p>'.$cycle->get('desc').'</p>';
}

if ($_V->isAdmin()) {
    $_ECHO .= '
        <div class="admin">
            <p>Cycle n&deg; '.$cycle->get('id').'</p>
            <p><a href="/pages/adm_cycle?id='.$cycle->get('id').'">modifier</a></p>
        </div>
    ';
}

$_REQ = "`cycle_id` = '".$cycle->get('id')."'";

// Trier par tome
$defaultOrderBy = 'article_tome';
$defaultSortOrder = 0;
$_REQ_ORDER = 'ORDER BY CAST(`article_tome` AS SIGNED), `article_pubdate`';

$path = get_controller_path('_list');
include $path;
