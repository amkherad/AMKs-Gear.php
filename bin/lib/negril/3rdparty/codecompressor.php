<?php
//Copyright 2009 by Simon Wippich, www.wippich.org
class CodeCompressor{
	/**
	 * Method for compressing HTML-sourcecode.
	 * 
	 * @access public
	 * @param $htmlSourceCode String The original HTML-sourcecode
	 * @return String The compressed HTML-sourcecode
	 */
	final public static function CompressHtml($htmlSourceCode,$removeComments = true){
		// Initialize a returning variable
		$returnValue = '';
		try {
			// Check if the given parameter matches datatype string
			if(is_string($htmlSourceCode)){
				// Exclude pre- or code-tags
				preg_match_all(
					'!(<(?:code|pre).*>[^<]+</(?:code|pre)>)!',
					$htmlSourceCode,
					$pre
				);
				// Remove all pre- or code-tags
				$htmlSourceCode = preg_replace(
					'!<(?:code|pre).*>[^<]+</(?:code|pre)>!',
					'#pre#',
					$htmlSourceCode
				);
				// Remove HTML-comments if required
				if($removeComments === true){
					$htmlSourceCode = preg_replace(
						'/<!--(.|\s)*?-->/',
						'',
						$htmlSourceCode
					);
				}
				// Remove new lines, spaces and tabs
				$htmlSourceCode = preg_replace(
					'/[\r\n\t]+/',
					' ',
					$htmlSourceCode
				);
				$htmlSourceCode = preg_replace(
					'/>[\s]+</',
					'><',
					$htmlSourceCode
				);
				$htmlSourceCode = preg_replace(
					'/[\s]+/',
					' ',
					$htmlSourceCode
				);
				if(!empty($pre[0])){
					foreach($pre[0] as $tag){
						// Return pre- and code-tags
						$htmlSourceCode = preg_replace(
							'!#pre#!',
							$tag,
							$htmlSourceCode,
							1
						);
					}
				}
				// Remove preceding and trailing spaces and write
				// the processed sourcecode into the returning variable
				$returnValue = trim($htmlSourceCode);
				// Delete the original sourcecode
				unset($htmlSourceCode);
			} else{
				// Throw an exception in case of an invalid method parameter
				throw new Exception(
					'Method "' .
					__FUNCTION__ .
					'" of class "CodeCompressor" reported an error: ' .
					'Invalid parameter!'
				);
			}
		} catch(Exception $e){
			// Catch all occuring exceptions form the preceding codeblock
			// and write the errormessages into the returning variable
			$returnValue = $e->getMessage();
		}
		// Return the filled variable
		return $returnValue;
	}
}
?>