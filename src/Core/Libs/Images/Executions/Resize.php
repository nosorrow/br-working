<?php

namespace Core\Libs\Images\Executions;

use Core\Libs\Images\Image as Image;

class Resize extends AbstractExecutions
{

	/**
	 * @throws \Exception
	 */
	public function execute(Image $image)
    {
		if (count($this->arguments) > 2) {
			throw new \Exception("Too many arguments");

		}

		if (count($this->arguments) === 2) {
			[$width, $height] = $this->arguments;

		} elseif (count($this->arguments) === 1){
			$width = $this->arguments[0];
			$height = null;

		} else {
			throw new \Exception("No arguments !");

		}

		[$width_orig, $height_orig] = $image->src_image_info;

        return $this->resize($image->src_image, $width, $height, $width_orig, $height_orig);
    }

    /**
     * @param $image
     * @param null $width
     * @param null $height
     * @param $width_orig
     * @param $height_orig
     * @return resource
     */
    protected function resize($image, $width = null, $height = null, $width_orig=null, $height_orig=null)
    {
        $ratio_orig = $width_orig / $height_orig;

        if ($height === null) {
            $height = (int)($width / $ratio_orig);

        } elseif ($width === null) {
            $width =(int)($height * $ratio_orig);
        }

        $dst_image = imagecreatetruecolor($width, $height);
        imagecopyresampled($dst_image, $image, 0, 0, 0, 0, $width, $height, $width_orig, $height_orig);

        return $dst_image;

    }

}
