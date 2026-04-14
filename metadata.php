<?php
/**
 * Metadata version
 */
$sMetadataVersion = '2.1';

/**
 * Module information
 */
$aModule = array(
    'id'          => 'suaboorderstat',
    'title'       => 'Bestell Statistik',
    'description' => array(
      'de' => 'Zeigt in der Bestellübersicht, wenn noch keine Bestellung gewählt wurde eine ausführliche Bestellstatistik an.',
      'en' => 'Show an advanced statistic in the order overview admin panel.'
    ),
    'thumbnail'   => 'logo.png',
    'version'     => '2.0.0',
    'author'      => 'suabo',
    'url'         => 'https://www.suabo.de',
    'email'       => 'info@suabo.de',
    'extend' => array(
		\OxidEsales\Eshop\Application\Controller\Admin\OrderOverview::class => Suabo\OrderAnalytics\Model\SuaboOrderStatistik::class
    )
);
