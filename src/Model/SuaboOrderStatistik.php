<?php
namespace Suabo\OrderAnalytics\Model;

use Doctrine\DBAL\Driver\Exception;
use OxidEsales\Eshop\Core\Registry;
use OxidEsales\EshopCommunity\Core\Di\ContainerFacade;
use OxidEsales\EshopCommunity\Internal\Framework\Database\QueryBuilderFactoryInterface;

class SuaboOrderStatistik extends SuaboOrderStatistik_parent{

	/**
	 * Render template
	 * @throws Exception
     * @throws \Doctrine\DBAL\Exception
	 */
	public function render() {
        $this->getOrderStatistics();
        return parent::render();
    }

	/**
	 * Calculate days in month
	 * @param int $month
	 * @param int $year
	 *
	 * @return int
	 */
    public function calculateDaysInMonth(int $month, int $year): int
	{
        // calculate number of days in a month 
        return $month == 2 ? ($year % 4 ? 28 : ($year % 100 ? 29 : ($year % 400 ? 28 : 29))) : (($month - 1) % 7 % 2 ? 30 : 31); 
    }


	/**
	 * Get order statistics
	 * @throws Exception
     * @throws \Doctrine\DBAL\Exception
	 */
	protected function getOrderStatistics(): void
	{
        // Tag
        $sTimestampFrom = date("Y-m-d")." 00:00:00";
        $sTimestampTill = date("Y-m-d")." 23:59:59";
        $this->_aViewData['mgstat_day'] = $this->getOrderStatisticsFrom($sTimestampFrom, $sTimestampTill);

        // diesen Monat
        $sTimestampFrom = date("Y-m")."-1 00:00:00";
        $sTimestampTill = date("Y-m-d H:i:s", mktime(23, 59, 59, ( intval(date("m")) + 1 ), 0, date("Y")));
        $this->_aViewData['mgstat_month'] = $this->getOrderStatisticsFrom($sTimestampFrom, $sTimestampTill);

        // letzten Monat
        $lastMonth = (intval(date("m"))-1);
        $sTimestampFrom = date("Y-").(intval(date("m"))-1)."-1 00:00:00";
        $sTimestampTill = date("Y-").(intval(date("m"))-1)."-".$this->calculateDaysInMonth($lastMonth,date("Y"))." 23:59:59";
        $this->_aViewData['mgstat_lmonth'] = $this->getOrderStatisticsFrom($sTimestampFrom, $sTimestampTill);
        
        // Benutzereingabe
        $aUserStat = Registry::getRequest()->getRequestParameter('mgstat');
        if(empty($aUserStat)) {
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
        
        $this->_aViewData['mgstat_userdef'] = $this->getOrderStatisticsFrom($sTimestampFrom, $sTimestampTill);
        
        // Total
        $sTimestampFrom = "2000-01-01 00:00:00";
        $sTimestampTill = "2029-01-01 23:59:59";
        $this->_aViewData['mgstat_total'] = $this->getOrderStatisticsFrom($sTimestampFrom, $sTimestampTill);
    }

	/**
	 * Get order statistics from
	 * @param string $sFrom
	 * @param string $sTill
	 * @return array
     * @throws Exception
     * @throws \Doctrine\DBAL\Exception
	 */
	protected function getOrderStatisticsFrom(string $sFrom, string $sTill): array
    {
        // Anzahl Bestellungen
        $iOrderCnt = $this->getOrderCount($sFrom, $sTill);
        
        // Warenwert
        $aArticleSum = $this->getArticleTotalSum($sFrom, $sTill);
        
        // Gesamtwert
        $fGesamtBrutto = number_format($aArticleSum["totalBrutto"] + $aArticleSum["shipping"], 2, ',', '.');
        $fGesamtNetto  = number_format($aArticleSum["totalNetto"] + ($aArticleSum["shipping"] * 0.81), 2, ',', '.');
        $aGesamt = array($fGesamtBrutto, $fGesamtNetto);
        
        return [
			"iOrderCnt"    => $iOrderCnt,
			"aArticleSum"  => [
				"totalBrutto" => number_format($aArticleSum['totalBrutto'], 2, ',', '.'),
				"totalNetto"  => number_format($aArticleSum['totalNetto'],2, ',', '.'),
				"shipping"    => number_format($aArticleSum['shipping'], 2, ',', '.')
			],
			"aGesamt"      => $aGesamt
		];
    }

    /**
     * Get order count
     * @param string $fromDate
     * @param string $tillDate
     * @return int
     * @throws Exception
     * @throws \Doctrine\DBAL\Exception
     */
	protected function getOrderCount(string $fromDate, string $tillDate): int
	{
        $queryBuilderFactory = ContainerFacade::get(QueryBuilderFactoryInterface::class);
        $queryBuilder = $queryBuilderFactory->create();
        $shopId = Registry::getConfig()->getActiveShop()->getId();

        $queryBuilder
            ->select('COUNT(oxid)')
            ->from('oxorder')
            ->where('oxshopid = :shopId')
            ->andWhere('oxorderdate >= :from')
            ->andWhere('oxorderdate <= :till')
            ->setParameters([
                'shopId'    => $shopId,
                'from'      => $fromDate,
                'till'      => $tillDate,
            ]);

        $blocksData = $queryBuilder->execute();
        return $blocksData->fetchOne();
    }

    /**
     * Get article total sum
     * @param string $fromDate
     * @param string $tillDate
     * @return array
     * @throws Exception
     * @throws \Doctrine\DBAL\Exception
     */
	protected function getArticleTotalSum(string $fromDate, string $tillDate): array
    {
        $totalBrutto = $totalNetto = $shipping = 0;
        $queryBuilderFactory = ContainerFacade::get(QueryBuilderFactoryInterface::class);
        $queryBuilder = $queryBuilderFactory->create();
        $shopId = Registry::getConfig()->getActiveShop()->getId();

        $queryBuilder
            ->select('oxtotalbrutsum, oxtotalnetsum, oxdelcost')
            ->from('oxorder')
            ->where('oxshopid = :shopId')
            ->andWhere('oxorderdate >= :from')
            ->andWhere('oxorderdate <= :till')
            ->setParameters([
                'shopId'    => $shopId,
                'from'      => $fromDate,
                'till'      => $tillDate,
            ]);

        $blocksData = $queryBuilder->execute();
        $aArticleSumList = $blocksData->fetchAllAssociative();
        
        foreach($aArticleSumList as $aArticleSum) {
            $totalBrutto += $aArticleSum['oxtotalbrutsum'];
            $totalNetto += $aArticleSum['oxtotalnetsum'];
            $shipping += $aArticleSum['oxdelcost'];
        }
        
        return [
			"totalBrutto" => $totalBrutto,
        	"totalNetto"  => $totalNetto,
            "shipping"    => $shipping
		];
    }    
}