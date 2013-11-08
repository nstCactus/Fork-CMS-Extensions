<?php

/*
 * This file is part of the photogallery module.
 *
 * For the full copyright and license information, please view the license
 * file that was distributed with this source code.
 */
	use Symfony\Component\Filesystem\Filesystem;

	/**
* This delete album action
 *
 * @author Frederik Heyninck <frederik@figure8.be>
 */
class BackendPhotogalleryRotateImage extends BackendBaseActionDelete
{
	/**
	 * Execute this action.
	 */
	public function execute()
	{
		// get parameters
		$this->id = $this->getParameter('id', 'int');
		$album_id = $this->getParameter('album_id', 'int');
		$angle = $this->getParameter('angle', 'int');

		// does the item exist
		if($this->id !== null && BackendPhotogalleryModel::existsImage($this->id))
		{
			// call parent, this will probably add some general CSS/JS or other required files
			parent::execute();

			// get all data for the item we want to edit
			$this->record = (array) BackendPhotogalleryModel::getImage($this->id);

			// check if gd is available
			if(!extension_loaded('gd')) throw new SpoonThumbnailException('GD2 isn\'t loaded. Contact your server-admin to enable it.');

			// rotate image & update thumbnails
			$image_file = PATH_WWW . "/frontend/files/photogallery/sets/original/{$this->record['set_id']}/{$this->record['filename']}";
			if(!$this->rotateImage($image_file, $angle)) $this->redirect(BackendModel::createURLForAction('edit_image') . "&id={$this->id}&album_id={$album_id}&error=unexpected_error&var=" . urlencode($this->record['title']));
			$this->updateThumbnails($angle);

			// rotation done, so redirect
			$this->redirect(BackendModel::createURLForAction('edit_image') . "&id={$this->id}&album_id={$album_id}&report=picture_rotated&var=" . urlencode($this->record['original_filename']));
		}

		// something went wrong
		$this->redirect(BackendModel::createURLForAction('edit') . "&error=non-existing&id={$album_id}#tabImages");
	}

	/**
	 * @param int $angle The angle of the rotation
	 */
	private function updateThumbnails($angle){
		// rotate the backend thumbnails
		//TODO: find a way to invalidate the browser cache
		$base_path = PATH_WWW . "/frontend/files/photogallery/sets/backend/{$this->record['set_id']}/";
		foreach(scandir($base_path) as $dir){
			$this->rotateImage($base_path . $dir . '/' . $this->record['filename'], $angle);
		}

		// deletes the frontend thumbnails
		$base_path = PATH_WWW . "/frontend/files/photogallery/sets/frontend/{$this->record['set_id']}/";
		foreach(scandir($base_path) as $dir){
			if(is_file($base_path . $dir) || $dir == '.' || $dir == '..') continue;
			if(file_exists($base_path . $dir . '/' . $this->record['filename'])) unlink($base_path . $dir . '/' . $this->record['filename']);
		}
	}

	private function rotateImage($filename, $angle){
		// ensure that angle is between 0 and 360
		$angle = (360 + (int)$angle) % 360;

		$fs = new Filesystem();
		if($fs->exists($filename)){
			// TODO remove or rotate generated pictures
			$extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
			switch($extension){
				case 'jpg':
				case 'jpeg':
					$image = imagecreatefromjpeg($filename);
					break;
				case 'gif':
					$image = imagecreatefromgif($filename);
					break;
				case 'png':
					$image = imagecreatefrompng($filename);
					break;
				default:
					throw new Exception('Unsupported file type.');
			}

			$rotated_image = imagerotate($image, $angle, imagecolorallocate($image, 255, 255, 255));
			if(!$rotated_image) return false;

			switch($extension){
				case 'jpg':
				case 'jpeg':
					imagejpeg($rotated_image, $filename, 100);
					break;
				case 'gif':
					imagegif($rotated_image, $filename);
					break;
				case 'png':
					imagepng($rotated_image, $filename, 9);
					break;
				default:
					throw new Exception('Unsupported file type.');
			}
		}

		return true;
	}
}