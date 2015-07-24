<?php

return new class() extends Module
{
	public function test_zoos()
	{
		echo 'Testing zoos...<br>';
		$zoo_list=$this->get_zoo_list();
		$species=$this->config['test_species'] ?? 'Animal';
		foreach ($zoo_list as $zoo_key)
		{
			echo 'Testing <b>'.$zoo_key.'</b>, species <b>'.$species.'</b>...<hr>';
			$zoo=$this->framework->get_module($zoo_key);
			$lifeform=$zoo->get_class_instance($species);
			$lifeform->eat();
			echo '<hr>Finished testing <b>'.$zoo_key.'</b>.<br>';
		}
		echo 'Finished testing all zoos.';
	}
	
	public function get_zoo_list()
	{
		return $this->config['zoo_list'] ?? ['zoo'];
	}
};