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

require_once IA_INCLUDES . 'phpimageworkshop' . IA_DS . 'ImageWorkshop.php';

class esynImage extends ImageWorkshop
{
	/**
	 * Return image file extension
	 *
	 * @param string $aFile file mime type
	 *
	 * @return string
	 */
	function getImageExt($aFile)
	{
		$imgtypes = array("image/gif"=>"gif", "image/jpeg"=>"jpg", "image/pjpeg"=>"jpg", "image/png"=>"png");

		return array_key_exists($aFile, $imgtypes) ? '.'.$imgtypes[$aFile] : '';
	}

	static function _applyWaterMark($image)
	{
		global $esynConfig;

		// add watermark to an image
		$watermark_file = IA_HOME . 'uploads' . IA_DS . $esynConfig->getConfig('site_watermark');
		if (file_exists($watermark_file) && !is_dir($watermark_file))
		{
			$watermark_positions = array(
				'top_left'		=> 'TL',
				'top_center'	=> 'MT',
				'top_right'		=> 'RT',
				'middle_left'	=> 'LM',
				'middle_center'	=> 'MM',
				'middle_right'	=> 'RM',
				'bottom_left'	=> 'LB',
				'bottom_center'	=> 'MB',
				'bottom_right'	=> 'RB'
			);

			$position = $esynConfig->getConfig('site_watermark_position');
			$watermark_position = !empty($position) && array_key_exists($position, $watermark_positions) ? $watermark_positions[$position] : $watermark_positions['bottom_right'];

			$watermark = ImageWorkshop::initFromPath($watermark_file);

			$image->addLayerOnTop($watermark, 10, 10, $watermark_position);
		}

		return $image;
	}

	/**
	 * Process image types here and returns filename to write
	 *
	 * @param array $aFile uploaded file information
	 * @param string $folder the file path
	 * @param string $aName the file name
	 * @param array $image_info image information array: width, height, resize_mode
	 *
	 * @return string
	 */
	function processImage($aFile, $folder, $aName, $image_info)
	{
		$image = ImageWorkshop::initFromPath($aFile['tmp_name']);

		// process thumbnails for files uploaded in CKEditor and other tools
		if (empty($image_info['image_width']) && empty($image_info['image_height']))
		{
			// apply watermark
			$image = self::_applyWaterMark($image);
			$image->save(IA_HOME . 'uploads' . IA_DS, $aName);

			return true;
		}

		// check this is an animated GIF
		if ('gif' == substr($aName, -3))
		{
			require_once IA_INCLUDES . 'phpimageworkshop' . IA_DS . 'Core' . IA_DS . 'GifFrameExtractor.php';
			$gifPath = $aFile['tmp_name'];
			if (GifFrameExtractor::isAnimatedGif ($gifPath))
			{
				// Extractions of the GIF frames and their durations
				$gfe = new GifFrameExtractor();
				$frames = $gfe->extract($gifPath);

				// For each frame, we add a watermark and we resize it
				$retouchedFrames = array();
				$thumbFrames = array();
				foreach ($frames as $frame)
				{
					$frameLayer = ImageWorkshop::initFromResourceVar($frame['image']);
					$thumbLayer = ImageWorkshop::initFromResourceVar($frame['image']);

					$frameLayer->resizeInPixel($image_info['image_width'], $image_info['image_height'], true);
					$frameLayer = self::_applyWaterMark($frameLayer);
					$retouchedFrames[] = $frameLayer->getResult();

					$thumbLayer->resizeInPixel($image_info['thumb_width'], $image_info['thumb_height'], true);
					$thumbFrames[] = $thumbLayer->getResult();
				}

				// Then we re-generate the GIF
				require_once IA_INCLUDES . 'phpimageworkshop' . IA_DS . 'Core' . IA_DS . 'GifCreator.php';

				$gc = new GifCreator();
				$gc->create($retouchedFrames, $gfe->getFrameDurations(), 0);
				file_put_contents($folder . $aName, $gc->getGif ());

				$thumb_creator = new GifCreator();
				$thumb_creator->create($thumbFrames, $gfe->getFrameDurations(), 0);
				file_put_contents($folder . 'small_' . $aName, $thumb_creator->getGif ());

				return true;
			}
		}

		// save full image
		($image_info['image_width'] > $image_info['image_height']) ? $largestSide = $image_info['image_width'] : $largestSide = $image_info['image_height'];
		$image->resizeByLargestSideInPixel($largestSide, true);
		$image = self::_applyWaterMark($image);
		$image->save($folder, $aName);

		if ($image_info['thumb_width'] || $image_info['thumb_height'])
		{
			$thumb = ImageWorkshop::initFromPath($aFile['tmp_name']);
			$resize_mode = isset($image_info['resize_mode']) ? $image_info['resize_mode'] : 'crop';
			switch ($resize_mode)
			{
				case 'fit':
					$thumb->resizeInPixel($image_info['thumb_width'], $image_info['thumb_height'], true, 0, 0, 'MM');
					break;

				case 'crop':
					($image_info['thumb_width'] > $image_info['thumb_height']) ? $largestSide = $image_info['thumb_width'] : $largestSide = $image_info['thumb_height'];
					$thumb->cropMaximumInPixel(0, 0, "MM");
					$thumb->resizeInPixel($largestSide, $largestSide);
					$thumb->cropInPixel($image_info['thumb_width'], $image_info['thumb_height'], 0, 0, 'MM');
					break;

				default:
					break;
			}

			$thumb->save($folder, 'small_' . $aName);
		}
	}
}
