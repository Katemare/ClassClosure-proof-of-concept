<?php

// should be: return class { etc... }

$temporary_object=new class()
{
	public function eat()
	{
		echo 'I eat food!';
	}
};
return new ClassClosure($temporary_object);