<?php

$temporary_object=new class()
{
	public function express($str)
	{
		echo $str.'<br>';
	}
	
	public function eat()
	{
		$this->express('I eat food!');
	}
};
return new ClassClosure($temporary_object);