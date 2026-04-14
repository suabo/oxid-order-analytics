<?php
/**
 * OXID eShop Module - Suabo/OrderStatistics
 * @author Marcel Grolms
 */

namespace Suabo\OrderStatistics\Controller\Admin;

use OxidEsales\Eshop\Core\DatabaseProvider;
use OxidEsales\Eshop\Core\Registry;

class OrderOverview extends OrderOverview_parent{

    /**
     * Render Template
     * @return string
     */
    public function render()
    {
        $this->_aViewData['startDate'] = $this->_getOrderStatisticsStartDate();
        $this->_aViewData['endDate'] = $this->_getOrderStatisticsEndDate();

        $this->_calculateOrderStatistics();
        return parent::render();
    }

    /**
     * Calculate days in month
     * @param int $iMonth
     * @param int $iYear
     * @return bool
     */
    public function _calculateDaysInMonth($iMonth, $iYear)
    { 
        return $iMonth == 2 ? ($iYear % 4 ? 28 : ($iYear % 100 ? 29 : ($iYear % 400 ? 28 : 29))) : (($iMonth - 1) % 7 % 2 ? 30 : 31);
    }

    /**
     * Get order statistics
     */
    protected function _calculateOrderStatistics() {
        // Tag
        $sTimestampFrom = date("Y-m-d")." 00:00:00";
        $sTimestampTill = date("Y-m-d")." 23:59:59";
        $this->_aViewData['mgstat_day'] = $this->_calculateOrderStatisticsFrom($sTimestampFrom, $sTimestampTill);

        // diesen Monat
        $sTimestampFrom = date("Y-m")."-1 00:00:00";
        $sTimestampTill = date("Y-m-d H:i:s", mktime(23, 59, 59, ( date("m") + 1 ), 0, date("Y")));
        $this->_aViewData['mgstat_month'] = $this->_calculateOrderStatisticsFrom($sTimestampFrom, $sTimestampTill);

        // letzten Monat
        $lmonth = (intval(date("m"))-1);
        $sTimestampFrom = date("Y-").(intval(date("m"))-1)."-1 00:00:00";
        $sTimestampTill = date("Y-").(intval(date("m"))-1)."-".$this->_calculateDaysInMonth($lmonth,date("Y"))." 23:59:59";
        $this->_aViewData['mgstat_lmonth'] = $this->_calculateOrderStatisticsFrom($sTimestampFrom, $sTimestampTill);
        
        // Benutzereingabe
        $aUserStat = Registry::getRequest()->getRequestParameter("mgstat");
        if(!isset($aUserStat) || empty($aUserStat)) {
            $sUserFrom = date("Y-").(intval(date("m")) - 1).date("-d");
            $sUserTill = date("Y-m-d");
            
            $aUserFrom = array("day" => date("d"), "month" => date("m") - 1, "year" => date("Y"));
            $aUserTill = array("day" => date("d"), "month" => date("m"), "year" => date("Y"));
            
            $this->_aViewData['mgstat_useredit_from'] = $aUserFrom;
            $this->_aViewData['mgstat_useredit_till'] = $aUserTill;            
            
        } else {
            $sUserFrom = $aUserStat["from"]["year"]."-".$aUserStat["from"]["month"]."-".$aUserStat["from"]["day"];
            $sUserTill = $aUserStat["till"]["year"]."-".$aUserStat["till"]["month"]."-".$aUserStat["till"]["day"];
            
            $this->_aViewData['mgstat_useredit_from'] = $aUserStat["from"];
            $this->_aViewData['mgstat_useredit_till'] = $aUserStat["till"];        
        }
        
        $sTimestampFrom = $sUserFrom." 00:00:00";
        $sTimestampTill = $sUserTill." 23:59:59";
        
        $this->_aViewData['mgstat_userdef'] = $this->_calculateOrderStatisticsFrom($sTimestampFrom, $sTimestampTill);
        
        // Total
        $sTimestampFrom = "1970-01-01 00:00:00";
        $sTimestampTill = date("Y-m-d")." 23:59:59";
        $this->_aViewData['mgstat_total'] = $this->_calculateOrderStatisticsFrom($sTimestampFrom, $sTimestampTill);
    }

    /**
     * Get order statistics from and till given date
     * @param string $sFrom
     * @param string $sTill
     * @return array
     */
    protected function _calculateOrderStatisticsFrom($sFrom, $sTill) {
        $sSqlFilter = "UNIX_TIMESTAMP(oxorderdate) >= UNIX_TIMESTAMP('$sFrom') AND UNIX_TIMESTAMP(oxorderdate) <= UNIX_TIMESTAMP('$sTill')";
        
        // Anzahl Bestellungen
        $iOrderCnt = $this->_getOrderCount($sSqlFilter);
        
        // Warenwert
        $aArticleSum = $this->_getArticleSum($sSqlFilter);
        
        // Gesamtwert
        $fGesamtBrutto = $aArticleSum["fTotalBrutto"] + $aArticleSum["fShipping"];
        $fGesamtNetto  = $aArticleSum["fTotalNetto"] + ($aArticleSum["fShipping"] / 1.19);
        $aGesamt = array($fGesamtBrutto, $fGesamtNetto);
        
        return array("iOrderCnt"    => $iOrderCnt,
                     "aArticleSum"  => $aArticleSum,
                     "aGesamt"      => $aGesamt);    
    }

    /**
     * Get order count
     * @param string $sSqlFilter
     * @return int
     */
    protected function _getOrderCount($sSqlFilter) {
        $iShopID = Registry::getConfig()->getShopId();
        $sSelect = "SELECT COUNT(oxid) FROM oxorder WHERE OXSHOPID = '{$iShopID}' AND $sSqlFilter";
        $iOrderCnt = DatabaseProvider::getDb()->getOne($sSelect);
        
        return $iOrderCnt;
    }

    /**
     * Get order article sum
     * @param string $sSqlFilter
     * @return array
     */
    protected function _getArticleSum($sSqlFilter) {
        $iShopID = Registry::getConfig()->getShopId();
        $fTotalBrutto = 0;
        $fTotalNetto  = 0;
        $fShipping    = 0;
        
        $sSelect = "SELECT oxtotalbrutsum, oxtotalnetsum, oxdelcost FROM oxorder WHERE OXSHOPID = '{$iShopID}' AND $sSqlFilter";
        $aArticleSumList = DatabaseProvider::getDb()->getAll($sSelect);

        foreach($aArticleSumList as $aArticleSum) {
            $fTotalBrutto += $aArticleSum[0];
            $fTotalNetto += $aArticleSum[1];
            $fShipping += $aArticleSum[2];
        }
        
        return array(
            "fTotalBrutto" => $fTotalBrutto,
            "fTotalNetto"  => $fTotalNetto,
            "fShipping"    => $fShipping
        );
    }

    /**
     * Get order statistics start date from module config
     * @return int
     */
    protected function _getOrderStatisticsStartDate() {
        $iYear = Registry::getConfig()->getConfigParam('sSuaboOrderStatStart');
        return $iYear;
    }

    /**
     * Get order statistics end date
     * @return int
     */
    protected function _getOrderStatisticsEndDate() {
        $iYear = intval(date('Y')) + 1;
        return $iYear;
    }
}