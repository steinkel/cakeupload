<?php
class CakeuploadFilesController extends CakeuploadAppController {
	var $name = 'CakeuploadFiles';

	/**
	 * Uploads the file to the server
	 */
	function upload(){
		$desiredGroup = null;

		if (isset($this->params['url']['group'])){
			$desiredGroup = $this->params['url']['group'];
		}

		// list of valid extensions, ex. array("jpeg", "xml", "bmp")
		$allowedExtensions = array();
		// max file size in bytes
		$sizeLimit = 10 * 1024 * 1024;

		$uploader = new qqFileUploader($allowedExtensions, $sizeLimit);
		$result = $uploader->handleUpload(ROOT . DS . APP_DIR . DS . 'uploads' . DS, false, $desiredGroup);

		if (isset($result['success']) && $result['success']){
			// save the new file to table
			$originalFilename = $uploader->getOriginalFileName();
			$uploadedFilename = $uploader->getUploadedFileName();
			$fspath = $uploader->getFspath();
			$group = $uploader->getGroup();
			$newFile = array('CakeuploadFile' => array('group' => $group, 'originalFilename' => $originalFilename, 'uploadedFilename' => $uploadedFilename, 'fspath' => $fspath));
			$this->CakeuploadFile->create();
			if (!$this->CakeuploadFile->save($newFile)){
				$this->log('Unable to save record in database, the file was uploaded but no model was updated ' . $fspath);
			}
		}

		// to pass data through iframe you will need to encode all html tags
		echo htmlspecialchars(json_encode($result), ENT_NOQUOTES);

		exit();
	}

	function index() {
		$this->CakeuploadFile->recursive = 0;
		$this->set('cakeuploadFiles', $this->paginate());
	}

	function view($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid cakeupload file', true));
			$this->redirect(array('action' => 'index'));
		}
		$this->set('cakeuploadFile', $this->CakeuploadFile->read(null, $id));
	}

	/*
	 function add() {
		if (!empty($this->data)) {
		$this->CakeuploadFile->create();
		if ($this->CakeuploadFile->save($this->data)) {
		$this->Session->setFlash(__('The cakeupload file has been saved', true));
		$this->redirect(array('action' => 'index'));
		} else {
		$this->Session->setFlash(__('The cakeupload file could not be saved. Please, try again.', true));
		}
		}
		}
		*/

	function edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid cakeupload file', true));
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			if ($this->CakeuploadFile->save($this->data)) {
				$this->Session->setFlash(__('The cakeupload file has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The cakeupload file could not be saved. Please, try again.', true));
			}
		}
		if (empty($this->data)) {
			$this->data = $this->CakeuploadFile->read(null, $id);
		}
	}

	function delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for cakeupload file', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->CakeuploadFile->delete($id)) {
			$this->Session->setFlash(__('Cakeupload file deleted', true));
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(__('Cakeupload file was not deleted', true));
		$this->redirect(array('action' => 'index'));
	}
}

/* ******* Utility classes below */

/**
 * Handle file uploads via XMLHttpRequest
 */
class qqUploadedFileXhr {
	/**
	 * Save the file to the specified path
	 * @return boolean TRUE on success
	 */
	function save($path) {
		$input = fopen("php://input", "r");
		$temp = tmpfile();
		$realSize = stream_copy_to_stream($input, $temp);
		fclose($input);

		if ($realSize != $this->getSize()){
			return false;
		}

		$target = fopen($path, "w");
		fseek($temp, 0, SEEK_SET);
		stream_copy_to_stream($temp, $target);
		fclose($target);

		return true;
	}
	function getName() {
		return $_GET['qqfile'];
	}
	function getSize() {
		if (isset($_SERVER["CONTENT_LENGTH"])){
			return (int)$_SERVER["CONTENT_LENGTH"];
		} else {
			throw new Exception('Getting content length is not supported.');
		}
	}
}

/**
 * Handle file uploads via regular form post (uses the $_FILES array)
 */
class qqUploadedFileForm {
	/**
	 * Save the file to the specified path
	 * @return boolean TRUE on success
	 */
	function save($path) {
		if(!move_uploaded_file($_FILES['qqfile']['tmp_name'], $path)){
			return false;
		}
		return true;
	}
	function getName() {
		return $_FILES['qqfile']['name'];
	}
	function getSize() {
		return $_FILES['qqfile']['size'];
	}
}

class qqFileUploader {
	private $allowedExtensions = array();
	private $sizeLimit = 10485760;
	private $file;
	private $newFilename;
	private $fspath;

	function __construct(array $allowedExtensions = array(), $sizeLimit = 10485760){
		$allowedExtensions = array_map("strtolower", $allowedExtensions);

		$this->allowedExtensions = $allowedExtensions;
		$this->sizeLimit = $sizeLimit;

		$this->checkServerSettings();

		if (isset($_GET['qqfile'])) {
			$this->file = new qqUploadedFileXhr();
		} elseif (isset($_FILES['qqfile'])) {
			$this->file = new qqUploadedFileForm();
		} else {
			$this->file = false;
		}
	}

	private function checkServerSettings(){
		$postSize = $this->toBytes(ini_get('post_max_size'));
		$uploadSize = $this->toBytes(ini_get('upload_max_filesize'));

		if ($postSize < $this->sizeLimit || $uploadSize < $this->sizeLimit){
			$size = max(1, $this->sizeLimit / 1024 / 1024) . 'M';
			die("{'error':'increase post_max_size and upload_max_filesize to $size' in your php.ini file}");
		}
	}

	private function toBytes($str){
		$val = trim($str);
		$last = strtolower($str[strlen($str)-1]);
		switch($last) {
			case 'g': $val *= 1024;
			case 'm': $val *= 1024;
			case 'k': $val *= 1024;
		}
		return $val;
	}

	/**
	 * Returns array('success'=>true) or array('error'=>'error message')
	 * The $desiredGroup tags the upload with a group tag and stores the file under a group folder inside the base uploads folder
	 */
	function handleUpload($uploadDirectory, $replaceOldFile = FALSE, $desiredGroup){

		if ($desiredGroup != null){
			$uploadDirectory .= $desiredGroup . DS;
		}

		if (!file_exists($uploadDirectory)){
			// try to create the folder
			if (!mkdir($uploadDirectory, 0750, true)){
				return array('error' => "Could not create folder $uploadDirectory");
			}
		}

		if (!is_writable($uploadDirectory)){
			return array('error' => "Server error. Upload directory isn't writable. $uploadDirectory");
		}

		if (!$this->file){
			return array('error' => 'No files were uploaded.');
		}

		$size = $this->file->getSize();

		if ($size == 0) {
			return array('error' => 'File is empty');
		}

		if ($size > $this->sizeLimit) {
			return array('error' => 'File is too large');
		}

		$pathinfo = pathinfo($this->file->getName());
		$filename = $pathinfo['filename'];
		$filename .= '-'.md5(uniqid());
		$ext = $pathinfo['extension'];
		$this->newFilename = $filename . '.' . $ext;
		$this->fspath = $uploadDirectory . $filename . '.' . $ext;
		$this->group = $desiredGroup;

		if($this->allowedExtensions && !in_array(strtolower($ext), $this->allowedExtensions)){
			$these = implode(', ', $this->allowedExtensions);
			return array('error' => 'File has an invalid extension, it should be one of '. $these . '.');
		}

		if(!$replaceOldFile){
			/// don't overwrite previous files that were uploaded
			while (file_exists($uploadDirectory . $filename . '.' . $ext)) {
				$filename .= rand(10, 99);
			}
		}

		if ($this->file->save($this->fspath)){
			return array('success'=>true);
		} else {
			return array('error'=> 'Could not save uploaded file.' .
                'The upload was cancelled, or server error encountered');
		}

	}

	/**
	 *
	 * Returns uploaded file size in bytes if the file is uploaded, -1 if not.
	 */
	function getUploadedFileSize(){
		if ($this->file){
			return $this->file->getSize();
		}
		else return -1;
	}

	/**
	 *
	 * Returns the original file name, before it is uploaded
	 */
	function getOriginalFileName(){
		if ($this->file){
			return $this->file->getName();
		}
		else return false;
	}

	/**
	 * Returns the new name assigned to the file
	 */
	function getUploadedFileName(){
		if ($this->file){
			return $this->newFilename;
		}
		else return false;
			
	}

	function getFspath(){
		if ($this->file){
			return $this->fspath;
		}
		else return false;
	}

	function getGroup(){
		if ($this->file){
			return $this->group;
		}
		else return false;
	}
}
?>