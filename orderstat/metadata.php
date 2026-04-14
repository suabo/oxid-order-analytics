<?php
/**
 * Metadata version
 */
$sMetadataVersion = '2.0';

/**
 * Module information
 */
$aModule = array(
    'id'          => 'suaboorderstat',
    'title'       => 'suabo: Bestell Statistik',
    'description' => array(
      'de' => 'Zeigt in der Bestellübersich, wenn noch keine Bestellung gewählt wurde eine ausführliche Bestellstatistik an.',
      'en' => 'Show an advanced statistic in the order overview admin panel.'
    ),
    'thumbnail'   => '/logo.png',
    'version'     => '2.0.1',
    'author'      => 'Marcel Grolms',
    'url'         => 'http://www.suabo.de',
    'email'       => 'info@suabo.de',
    'extend' => array(
        \OxidEsales\Eshop\Application\Controller\Admin\OrderOverview::class => \Suabo\OrderStatistics\Controller\Admin\OrderOverview::class,
    ),
    'blocks' => array(
        [
            'template'  => 'order_overview.tpl',
            'block'     => 'admin_order_overview_general',
            'file'      => 'views/blocks/orderstatistik.tpl'
        ],
        [
            'template'  => 'headitem.tpl',
            'block'     => 'admin_headitem_inccss',
            'file'      => 'views/blocks/orderstyles.tpl'
        ],
    ),
    'settings' => array(
        array('group' => 'SUABOORDERSTAT_MAIN', 'name' => 'sSuaboOrderStatStart', 'type' => 'str',  'value' => '2000')
    )
);
