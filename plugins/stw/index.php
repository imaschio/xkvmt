<?php

global $STW_Account;

$sUrl = $_GET['url'];
$eC = $esynConfig;

$aOptions['Size']               = $eC->gC('stw_size') ? $eC->gC('stw_size') : '';
$aOptions['MaxHeight']          = $eC->gC('stw_max_height') ? $eC->gC('stw_max_height') : '';
$aOptions['WidescreenY']        = $eC->gC('stw_wide_screen') ? $eC->gC('stw_wide_screen') : '';

$aOptions['RefreshOnDemand']    = $STW_Account['stw_refresh_ondemand'] ? $eC->gC('stw_refresh_ondemand') : false;
$aOptions['Delay']              = $STW_Account['stw_custom_delay'] ? $eC->gC('stw_custom_delay') : '';
$aOptions['Quality']            = $STW_Account['stw_custom_quality'] ? $eC->gC('stw_custom_quality') : '';
$aOptions['SizeCustom']         = $STW_Account['stw_custom_size'] ? $eC->gC('stw_custom_size') : '';
$aOptions['FullSizeCapture']    = $STW_Account['stw_full_length'] ? $eC->gC('stw_full_length') : false;
$aOptions['NativeResolution']   = $STW_Account['stw_custom_resolution'] ? $eC->gC('stw_custom_resolution') : '';

$sImageHTML = getThumbnailHTML($sUrl, $aOptions);
echo $sImageHTML;