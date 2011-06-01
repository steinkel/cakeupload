<?php
class UploadHelper extends AppHelper {

	var $helpers = array('Html');

	/**
	 * Shows upload button, including necesary scripts
	 */
	function button($label, $group = null, $folder = 'files') {
		echo $this->Html->css('/cakeupload/css/fileuploader.css', null, array('inline' => false));
		echo $this->Html->script('/cakeupload/js/fileuploader.js', array('inline' => false, 'once' => true));
		echo "<div id='cakeupload-uploader'>";
		echo "<noscript>";
		echo "<p>Please enable JavaScript to use file uploader.</p>";
		echo "<!-- or put a simple form for upload here -->";
		echo "</noscript>";
		echo "</div>";
		ob_start();
		?>

        function createUploader(){            
            var uploader = new qq.FileUploader({
                element: document.getElementById('cakeupload-uploader'),
                action: '<?php echo $this->Html->url(array('plugin' => 'cakeupload', 'controller' => 'cakeupload_files', 'action' => 'upload')); ?>',
                params: { group: '<?php echo $group; ?>'},
                debug: true
            });           
        }
        
        // in your app create uploader as soon as the DOM is ready
        // don't wait for the window to load  
        window.onload = createUploader;     


		<?php
		$jscript = ob_get_contents(); //return output buffer to variable
		ob_end_clean(); //must clean buffer or javascript above will print TWICE (one inline, one in header)
		echo $this->Html->scriptBlock($jscript, array('inline' => false));
			
	}
}
?>