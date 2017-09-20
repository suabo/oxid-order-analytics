<?php
class suabostatistik extends suabostatistik_parent{

    public function render() {
        $this->_mgGetStats();        
        return parent::render();
    } 
    
    public function days_in_month($month, $year) 
    { 
        // calculate number of days in a month 
        return $month == 2 ? ($year % 4 ? 28 : ($year % 100 ? 29 : ($year % 400 ? 28 : 29))) : (($month - 1) % 7 % 2 ? 30 : 31); 
    } 

    
    protected function _mgGetStats() {
        // Tag
        $sTimestampFrom = date("Y-m-d")." 00:00:00";
        $sTimestampTill = date("Y-m-d")." 23:59:59";
        $this->_aViewData['mgstat_day'] = $this->_mgGetStatsFrom($sTimestampFrom, $sTimestampTill); 

        // diesen Monat
        $sTimestampFrom = date("Y-m")."-1 00:00:00";
        $sTimestampTill = date("Y-m-d H:i:s", mktime(23, 59, 59, ( date("m") + 1 ), 0, date("Y")));
        $this->_aViewData['mgstat_month'] = $this->_mgGetStatsFrom($sTimestampFrom, $sTimestampTill);

        // letzten Monat
        $lmonth = (intval(date("m"))-1);
        $sTimestampFrom = date("Y-").(intval(date("m"))-1)."-1 00:00:00";
        $sTimestampTill = date("Y-").(intval(date("m"))-1)."-".$this->days_in_month($lmonth,date("Y"))." 23:59:59";
        $this->_aViewData['mgstat_lmonth'] = $this->_mgGetStatsFrom($sTimestampFrom, $sTimestampTill);
        
        // Benutzereingabe
        $aUserStat = oxRegistry::getConfig()->getRequestParameter("mgstat");
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
        
        $this->_aViewData['mgstat_userdef'] = $this->_mgGetStatsFrom($sTimestampFrom, $sTimestampTill);        
        
        // Total
        $sTimestampFrom = "2000-01-01 00:00:00";
        $sTimestampTill = "2020-01-01 23:59:59";
        $this->_aViewData['mgstat_total'] = $this->_mgGetStatsFrom($sTimestampFrom, $sTimestampTill);     
    }   
    
    protected function _mgGetStatsFrom($sFrom, $sTill) {
        $sSqlFilter = "UNIX_TIMESTAMP(oxorderdate) >= UNIX_TIMESTAMP('$sFrom') AND UNIX_TIMESTAMP(oxorderdate) <= UNIX_TIMESTAMP('$sTill')";
        
        // Anzahl Bestellungen
        $iOrderCnt = $this->_mgGetOrderCount($sSqlFilter);
        
        // Warenwert
        $aArticleSum = $this->_mgGetArticleSum($sSqlFilter);
        
        // Gesamtwert
        $fGesamtBrutto = number_format($aArticleSum["fTotalBrutto"] + $aArticleSum["fShipping"], 2);
        $fGesamtNetto  = number_format($aArticleSum["fTotalNetto"] + ($aArticleSum["fShipping"] / 1.19), 2);
        $aGesamt = array($fGesamtBrutto, $fGesamtNetto);
        
        return array("iOrderCnt"    => $iOrderCnt,
                     "aArticleSum"  => $aArticleSum,
                     "aGesamt"      => $aGesamt);    
    }    
    
    protected function _mgGetOrderCount($sSqlFilter) {
        $sSelect = "SELECT COUNT(oxid) FROM oxorder WHERE $sSqlFilter";
        $iOrderCnt = oxDb::getDb()->getOne($sSelect);
        
        return $iOrderCnt;
    }
    
    protected function _mgGetArticleSum($sSqlFilter) {
        $fTotalBrutto = 0;
        $fTotalNetto  = 0;
        $fShipping    = 0;
        
        $sSelect = "SELECT oxtotalbrutsum, oxtotalnetsum, oxdelcost FROM oxorder WHERE $sSqlFilter";
        $aArticleSumList = oxDb::getDb()->getAll($sSelect);
        
        foreach($aArticleSumList as $aArticleSum) {
            $fTotalBrutto += $aArticleSum[0];
            $fTotalNetto += $aArticleSum[1];
            $fShipping += $aArticleSum[2];    
        }
        
        return array("fTotalBrutto" => number_format($fTotalBrutto, 2),
                     "fTotalNetto"  => number_format($fTotalNetto, 2),
                     "fShipping"    => number_format($fShipping, 2)
                     );
    }    
}
?>
