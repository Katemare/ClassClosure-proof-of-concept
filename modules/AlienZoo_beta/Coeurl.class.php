<?php

// should be: return class extends $this->get_basic_zoo()->get_class('Animal') { changes from Animal }
$animal_class=$this->get_basic_zoo()->get_class('Animal');

return eval('
$temporary_object=new class() extends '.$animal_class->get_alias().'
{
	public function eat()
	{
		parent::eat();
		$this->express(\'Stupid humans!\');
	}
};
return new ClassClosure($temporary_object);
');
// or we can eval() the contents of a some file AlienZoo/Coeurl.class.eval.php after replacing %PARENT_ALIAS% placeholder with $animal_class->get_alias()... ugly still, and probably can't have its own inherited anonymous classes, but at least has syntax highlighting.