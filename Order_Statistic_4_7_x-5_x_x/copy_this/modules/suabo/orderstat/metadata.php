<?php
/**
 * Metadata version
 */
$sMetadataVersion = '1.1';

/**
 * Module information
 */
$aModule = array(
    'id'          => 'suaboorderstat',
    'title'       => 'Bestell Statistik',
    'description' => array(
      'de' => 'Zeigt in der Bestellübersich, wenn noch keine Bestellung gewählt wurde eine ausführliche Bestellstatistik an.',
      'en' => 'Show an advanced statistic in the order overview admin panel.'
    ),
    'thumbnail'   => '../logo.png',
    'version'     => '1.0.0',
    'author'      => 'suabo',
    'url'         => 'http://www.suabo.de',
    'email'       => 'info@suabo.de',
    'extend' => array(
        'order_overview' => 'suabo/orderstat/models/suabostatistik',
    ),
    'blocks' => array(
        array('template' => 'order_overview.tpl', 'block' => 'admin_order_overview_general', 'file' => 'views/blocks/orderstatistik.tpl'),
        array('template' => 'headitem.tpl', 'block' => 'admin_headitem_inccss', 'file' => 'views/blocks/orderstyles.tpl'),
    ),
);