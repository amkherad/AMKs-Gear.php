<?php
namespace Negril\IO\Exceptions;

\Module::Load('Negril\Core\Exceptions\IException');

class FileNotFound extends \Exception implements \Negril\Core\Exceptions\IException{
    protected $message = 'File not found exception.'; // Exception message
    private   $string;      // Unknown
    protected $code    = 0; // User-defined exception code
    protected $file;        // Source filename of exception
    protected $line;        // Source line of exception
    private   $trace;       // Unknown

    public function __construct($message=null,$code=0){
        parent::__construct("File '$message' not found.", $code);
    }
    public function __toString(){
        return get_class($this)." '{$this->message}' in {$this->file}({$this->line})\n"
                               ."{$this->getTraceAsString()}";
    }
}
?>