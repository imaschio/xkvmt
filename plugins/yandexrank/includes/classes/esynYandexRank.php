<?php
/******************************************************************************
 *
 *	 COMPANY: Intelliants LLC
 *	 PROJECT: eSyndiCat Directory Software
 *	 VERSION: 3.3.0
 *	 LISENSE: http://www.esyndicat.com/license.html
 *	 http://www.esyndicat.com/
 *
 *	 This program is a commercial software and any kind of using it must agree 
 *	 to eSyndiCat Directory Software license.
 *
 *	 Link to eSyndiCat.com may not be removed from the software pages without
 *	 permission of eSyndiCat respective owners. This copyright notice may not
 *	 be removed from source code in any case.
 *
 *	 Copyright 2007-2013 Intelliants LLC
 *	 http://www.intelliants.com/
 *
 ******************************************************************************/

class esynYandexRank extends eSyndiCat
{

	var $mTable = 'listings';

    function GetContent($urlStat)
    {
        return esynUtil::getPageContent($urlStat);
    }

	function getYandexRank($aUrl)
	{
        if (!function_exists('xml2array'))
        {
            include_once IA_INCLUDES . "xml" . IA_DS . "xml2array.php";
        }

		$request = "bar-navig.yandex.ru/u?ver=2&url=http://$aUrl/&show=1";
        $data = $this->GetContent($request);

        $arrXml = xml2array($data);

        return $arrXml['urlinfo']['tcy_attr']['value'];
	}
	
	function setYandexRank($YaVal=0, $aId)
	{
		return $this->update(array("yandex_rank"=>$YaVal), "`id`='".$aId."'");
	}	
}