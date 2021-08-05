<?php
require('phpQuery/phpQuery.php');

$id = 46418;
$url = "http://loveread.ec/read_book.php?id=%d&p=%d";
$page = 1;
$endPage = 63;
$content = [];

function parsePage($id, $page, $endPage, $url, &$content)
{
    $pageUrl = sprintf($url, $id, $page);
    $text = file_get_contents($pageUrl);
    $doc = phpQuery::newDocumentHTML($text);
    foreach ($doc->find('div.MsoNormal') as $div)
    {
        $content[] = trim(str_replace('Страница', '', $div->textContent));
    }

    if ($page !== $endPage) {
        $page++;
        parsePage($id, $page, $endPage, $url, $content);
    }
}

parsePage($id, $page, $endPage, $url, $content);

file_put_contents($id . '.txt', implode(PHP_EOL, $content));