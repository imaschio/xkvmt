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

class esynCheckOwner extends eSyndiCat
{
    function GetCheckOwnerCod($id)
    {

        $this->setTable('listings');
        $listing = $this->row("domain, url, date","`id`={$id}");
        $this->resetTable();
        $string = md5(md5($listing['url']).md5($listing['domain']).md5($listing['date']).md5(IA_SALT_STRING));

        return $string;
    }
    function CheckString($string, $id)
    {
        if ($this->GetCheckOwnerCod($id)==$string && $id>0)
        {
            return 1;
        }
        return 0;
    }
    function CheckStringOnUrl($string, $id)
    {
        $this->setTable('listings');
        $url=$this->one("url", "`id`={$id}");
        $this->resetTable();
        $content=esynUtil::getPageContent($url);

        $string_preg="/".$string."/";
        if (preg_match($string_preg,$content))
        {
	        $_SESSION['string_url_update'] = $this->GetCheckOwnerCod($id);
            return 1;
        }
        else
        {
        	$this->setTable('listings');
	        $listing = $this->row("domain, url, date","`id`={$id}");
	        $this->resetTable();
        	$verification_txt = esynUtil::getPageContent(trim($listing['url'], " /")."/verification.txt");
			if ($verification_txt)
			{
				if ($verification_txt==$string)
				{
					$_SESSION['string_url_update'] = $this->GetCheckOwnerCod($id);
					return 1;
				}
			}
        }
	    return 0;
    }
}


?>