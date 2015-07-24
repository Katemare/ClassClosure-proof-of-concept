<?php

return new class() extends Module
{
	public
		$quick_classes=['Coeurl'];
	
	public function get_basic_zoo()
	{
		return $this->framework->get_module($this->config['zoo_key'] ?? 'zoo');
	}
};