</table>
<table width="98%" border="0" cellspacing="0" cellpadding="0" class="orderstat-table">
    <thead>
        <tr>
            <th class="topic"></th>
            <th class="topic brdl text-right">[{oxmultilang ident="SUABOORDERSTAT_TODAY"}]</th>
            <th class="topic brdl text-right">[{oxmultilang ident="SUABOORDERSTAT_THISMONTH"}]</th>
            <th class="topic brdl text-right">[{oxmultilang ident="SUABOORDERSTAT_LASTMONTH"}]</th>
            <th class="topic brdl text-right">
                <form name="mgstatuserdef" id="mgstatuserdef" action="[{$oViewConf->getSelfLink()}]" method="post">
                    [{$oViewConf->getHiddenSid()}]
                    <input type="hidden" name="oxid" value="[{$oxid}]">
                    <input type="hidden" name="cl" value="order_overview">

                    [{oxmultilang ident="SUABOORDERSTAT_FROM"}]&nbsp;
                    <select name="mgstat[from][day]" onchange="document.mgstatuserdef.submit();">
                        [{section name="i" start=1 loop=32 step=1}]
                    <option[{if $mgstat_useredit_from.day == $smarty.section.i.index}] selected[{/if}]>[{$smarty.section.i.index}]</option>
                    [{/section}]
                    </select>

                    <select name="mgstat[from][month]" onchange="document.mgstatuserdef.submit();">
                        [{section name="i" start=1 loop=13 step=1}]
                    <option[{if $mgstat_useredit_from.month == $smarty.section.i.index}] selected[{/if}]>[{$smarty.section.i.index}]</option>
                    [{/section}]
                    </select>
                    <select name="mgstat[from][year]" onchange="document.mgstatuserdef.submit();">
                        [{section name="i" start=$startDate loop=$endDate step=1}]
                    <option[{if $mgstat_useredit_from.year == $smarty.section.i.index}] selected[{/if}]>[{$smarty.section.i.index}]</option>
                    [{/section}]
                    </select>
                    <br>
                    [{oxmultilang ident="SUABOORDERSTAT_TILL"}]&nbsp;
                    <select name="mgstat[till][day]" onchange="document.mgstatuserdef.submit();">
                        [{section name="i" start=1 loop=32 step=1}]
                    <option[{if $mgstat_useredit_till.day == $smarty.section.i.index}] selected[{/if}]>[{$smarty.section.i.index}]</option>
                    [{/section}]
                    </select>

                    <select name="mgstat[till][month]" onchange="document.mgstatuserdef.submit();">
                        [{section name="i" start=1 loop=13 step=1}]
                    <option[{if $mgstat_useredit_till.month == $smarty.section.i.index}] selected[{/if}]>[{$smarty.section.i.index}]</option>
                    [{/section}]
                    </select>
                    <select name="mgstat[till][year]" onchange="document.mgstatuserdef.submit();">
                        [{section name="i" start=$startDate loop=$endDate step=1}]
                    <option[{if $mgstat_useredit_till.year == $smarty.section.i.index}] selected[{/if}]>[{$smarty.section.i.index}]</option>
                    [{/section}]
                    </select>
                </form>
            </th>
            <th class="topic brdl text-right">[{oxmultilang ident="SUABOORDERSTAT_TOTAL"}]</th>
        </tr>
    </thead>

    <tr class="white">
        <td>[{oxmultilang ident="SUABOORDERSTAT_ORDERCNT"}]:</td>
        <td class="brdl text-right">[{$mgstat_day.iOrderCnt}]</td>

        <td class="brdl text-right">[{$mgstat_month.iOrderCnt}]</td>

        <td class="brdl text-right">[{$mgstat_lmonth.iOrderCnt}]</td>
        <td class="brdl text-right">[{$mgstat_userdef.iOrderCnt}]</td>

        <td class="brdl text-right total"><b>[{$mgstat_total.iOrderCnt}]</b></td>
    </tr>
    <tr class="grey">
        <td>[{oxmultilang ident="SUABOORDERSTAT_ARTNETTO"}]:</td>
        <td class="brdl text-right">[{oxprice price=$mgstat_day.aArticleSum.fTotalNetto}]</td>
        <td class="brdl text-right">[{oxprice price=$mgstat_month.aArticleSum.fTotalNetto}]</td>
        <td class="brdl text-right">[{oxprice price=$mgstat_lmonth.aArticleSum.fTotalNetto}]</td>
        <td class="brdl text-right">[{oxprice price=$mgstat_userdef.aArticleSum.fTotalNetto}]</td>
        <td class="brdl text-right total">[{oxprice price=$mgstat_total.aArticleSum.fTotalNetto}]</td>
    </tr>
    <tr class="white">
        <td>[{oxmultilang ident="SUABOORDERSTAT_ARTBRUTTO"}]:</td>
        <td class="brdl text-right">[{oxprice price=$mgstat_day.aArticleSum.fTotalBrutto}]</td>
        <td class="brdl text-right">[{oxprice price=$mgstat_month.aArticleSum.fTotalBrutto}]</td>
        <td class="brdl text-right">[{oxprice price=$mgstat_lmonth.aArticleSum.fTotalBrutto}]</td>
        <td class="brdl text-right">[{oxprice price=$mgstat_userdef.aArticleSum.fTotalBrutto}]</td>
        <td class="brdl text-right total">[{oxprice price=$mgstat_total.aArticleSum.fTotalBrutto}]</td>
    </tr>
    <tr class="grey">
        <td>[{oxmultilang ident="SUABOORDERSTAT_DELCOST"}]:</td>
        <td class="brdl text-right">[{oxprice price=$mgstat_day.aArticleSum.fShipping}]</td>
        <td class="brdl text-right">[{oxprice price=$mgstat_month.aArticleSum.fShipping}]</td>
        <td class="brdl text-right">[{oxprice price=$mgstat_lmonth.aArticleSum.fShipping}]</td>
        <td class="brdl text-right">[{oxprice price=$mgstat_userdef.aArticleSum.fShipping}]</td>
        <td class="brdl text-right total">[{oxprice price=$mgstat_total.aArticleSum.fShipping}]</td>
    </tr>
    <tr class="white">
        <td>[{oxmultilang ident="SUABOORDERSTAT_TOTALNETTO"}]:</td>
        <td class="brdl text-right">[{oxprice price=$mgstat_day.aGesamt.1}]</td>
        <td class="brdl text-right">[{oxprice price=$mgstat_month.aGesamt.1}]</td>
        <td class="brdl text-right">[{oxprice price=$mgstat_lmonth.aGesamt.1}]</td>
        <td class="brdl text-right">[{oxprice price=$mgstat_userdef.aGesamt.1}]</td>
        <td class="brdl text-right total">[{oxprice price=$mgstat_total.aGesamt.1}]</td>
    </tr>
    <tr class="total">
        <td>[{oxmultilang ident="SUABOORDERSTAT_TOTALBRUTTO"}]:</td>
        <td class="brdl text-right">[{oxprice price=$mgstat_day.aGesamt.0}]</td>
        <td class="brdl text-right">[{oxprice price=$mgstat_month.aGesamt.0}]</td>
        <td class="brdl text-right">[{oxprice price=$mgstat_lmonth.aGesamt.0}]</td>
        <td class="brdl text-right">[{oxprice price=$mgstat_userdef.aGesamt.0}]</td>
        <td class="brdl text-right total">[{oxprice price=$mgstat_total.aGesamt.0}]</td>
    </tr>