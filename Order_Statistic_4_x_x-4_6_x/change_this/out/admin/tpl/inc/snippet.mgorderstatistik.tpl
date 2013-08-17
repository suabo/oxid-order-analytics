            [{*------ Statistik ------*}]
            <table class="mgstat">
              <tr>
                <td class="topic"></td>
                <td class="topic brdl">Heute</td>
                <td class="topic brdl">dieser Monat</td>
                <td class="topic brdl">letzter Monat</td>
                <td class="topic brdl">
                  <form name="mgstatuserdef" id="mgstatuserdef" action="[{ $shop->selflink }]" method="post">
                      [{ $shop->hiddensid }]
                      <input type="hidden" name="oxid" value="[{ $oxid }]">
                      <input type="hidden" name="cl" value="order_overview">
                                                            
                      von&nbsp;
                      <select name="mgstat[from][day]" onchange="document.mgstatuserdef.submit();">
                      [{ section name="i" start=1 loop=32 step=1 }]
                        <option[{if $mgstat_useredit_from.day == $smarty.section.i.index}] selected[{/if}]>[{ $smarty.section.i.index }]</option>
                      [{ /section }]                                        
                      </select>
                      
                      <select name="mgstat[from][month]" onchange="document.mgstatuserdef.submit();">
                      [{ section name="i" start=1 loop=13 step=1 }]
                        <option[{if $mgstat_useredit_from.month == $smarty.section.i.index}] selected[{/if}]>[{ $smarty.section.i.index }]</option>
                      [{ /section }] 
                      </select>
                      <select name="mgstat[from][year]" onchange="document.mgstatuserdef.submit();">
                      [{ section name="i" start=2007 loop=2021 step=1 }]
                        <option[{if $mgstat_useredit_from.year == $smarty.section.i.index}] selected[{/if}]>[{ $smarty.section.i.index }]</option>
                      [{ /section }] 
                      </select>
                      <br>                                    
                      bis&nbsp;
                      <select name="mgstat[till][day]" onchange="document.mgstatuserdef.submit();">
                      [{ section name="i" start=1 loop=32 step=1 }]
                        <option[{if $mgstat_useredit_till.day == $smarty.section.i.index}] selected[{/if}]>[{ $smarty.section.i.index }]</option>
                      [{ /section }]                                        
                      </select>
                      
                      <select name="mgstat[till][month]" onchange="document.mgstatuserdef.submit();">
                      [{ section name="i" start=1 loop=13 step=1 }]
                        <option[{if $mgstat_useredit_till.month == $smarty.section.i.index}] selected[{/if}]>[{ $smarty.section.i.index }]</option>
                      [{ /section }] 
                      </select>
                      <select name="mgstat[till][year]" onchange="document.mgstatuserdef.submit();">
                      [{ section name="i" start=2007 loop=2021 step=1 }]
                        <option[{if $mgstat_useredit_till.year == $smarty.section.i.index}] selected[{/if}]>[{ $smarty.section.i.index }]</option>
                      [{ /section }] 
                      </select>
                  </form>
                </td>
                <td class="topic brdl">Total</td>
              </tr>
              <tr class="white">
                <td>Anzahl Bestellungen:</td>
                <td class="brdl">[{$mgstat_day.iOrderCnt}]</td>
                
                <td class="brdl">[{$mgstat_month.iOrderCnt}]</td>
                
                <td class="brdl">[{$mgstat_lmonth.iOrderCnt}]</td>
                <td class="brdl">[{$mgstat_userdef.iOrderCnt}]</td>
                
                <td class="brdl"><b>[{$mgstat_total.iOrderCnt}]</b></td>
              </tr>
              <tr class="grey">
                <td>Warenwert Netto:</td>
                <td class="brdl">[{$mgstat_day.aArticleSum.fTotalNetto}]</td>
                <td class="brdl">[{$mgstat_month.aArticleSum.fTotalNetto}]</td>
                <td class="brdl">[{$mgstat_lmonth.aArticleSum.fTotalNetto}]</td>
                <td class="brdl">[{$mgstat_userdef.aArticleSum.fTotalNetto}]</td>
                <td class="brdl">[{$mgstat_total.aArticleSum.fTotalNetto}]</td>
              </tr>
              <tr class="white">
                <td>Warenwert Brutto:</td>
                <td class="brdl">[{$mgstat_day.aArticleSum.fTotalBrutto}]</td>
                <td class="brdl">[{$mgstat_month.aArticleSum.fTotalBrutto}]</td>
                <td class="brdl">[{$mgstat_lmonth.aArticleSum.fTotalBrutto}]</td>
                <td class="brdl">[{$mgstat_userdef.aArticleSum.fTotalBrutto}]</td>
                <td class="brdl">[{$mgstat_total.aArticleSum.fTotalBrutto}]</td>
              </tr>              
              <tr class="grey">
                <td>Versandkosten:</td>
                <td class="brdl">[{$mgstat_day.aArticleSum.fShipping}]</td>
                <td class="brdl">[{$mgstat_month.aArticleSum.fShipping}]</td>
                <td class="brdl">[{$mgstat_lmonth.aArticleSum.fShipping}]</td>
                <td class="brdl">[{$mgstat_userdef.aArticleSum.fShipping}]</td>
                <td class="brdl">[{$mgstat_total.aArticleSum.fShipping}]</td>
              </tr>  
              <tr class="white">
                <td>Gesamt Brutton:</td>
                <td class="brdl">[{$mgstat_day.aGesamt.0}]</td>
                <td class="brdl">[{$mgstat_month.aGesamt.0}]</td>
                <td class="brdl">[{$mgstat_lmonth.aGesamt.0}]</td>
                <td class="brdl">[{$mgstat_userdef.aGesamt.0}]</td>
                <td class="brdl">[{$mgstat_total.aGesamt.0}]</td>
              </tr>   
              <tr class="grey">
                <td>Gesamt Netto:</td>
                <td class="brdl">[{$mgstat_day.aGesamt.1}]</td>
                <td class="brdl">[{$mgstat_month.aGesamt.1}]</td>
                <td class="brdl">[{$mgstat_lmonth.aGesamt.1}]</td>
                <td class="brdl">[{$mgstat_userdef.aGesamt.1}]</td>
                <td class="brdl">[{$mgstat_total.aGesamt.1}]</td>
              </tr>                                                                    
            </table>
            [{*------ Statistik ------*}]