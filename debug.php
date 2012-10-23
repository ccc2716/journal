<?php

interface iDebug
{
    public function debugSetMask($bitMask);
    public function debugActive($bitMask);
    public function debugFileWrite($s);
}



class debug implements iDebug
{
	private $debugmask;
	private $fn;			// name of the file to save debug info into.
	
	function __construct($filename, $mask)
	{
		$this->debugmask= $mask;
		$this->fn= $filename;
	}
	
	public function debugSetMask($bitMask)
	{
		$this->debugmask= $bitMask;
	}

	public function debugActive($bitMask)
	{
		$x= (($this->debugmask & $bitMask) == 0)?0:1;
		return($x);
	}
	
	public function debugFileWrite($s)
	{
		$ef= fopen($this->fn, "a");
		fwrite($ef, $s);
		fclose($ef);
	}
}

?>
