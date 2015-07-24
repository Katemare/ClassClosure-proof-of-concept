<?php

class Framework
{
	protected
		$modules=[];

	public function __construct($config)
	{
		$this->populate_modules($config['modules']);
	}
		
	protected function populate_modules($config)
	{
		foreach ($config as $module_key=>$module_config)
		{
			$this->modules[$module_key]=$module=Module::from_config($module_config, $this);
		}
	}
	
	public function modules_dir()
	{
		return __DIR__.'/modules';
	}
	
	public function get_module($module_key)
	{
		return $this->modules[$module_key];
	}

}

class ClassClosure
{
	static $next_free_temporary_class=0;
	
	public
		$reflection,
		$object;
		
	protected
		$alias;
	
	public function __construct($argument)
	{
		$this->reflection=new ReflectionClass($argument);
		if (is_object($argument)) $this->object=$argument;
		else die('UNIMPLEMENTED YET: objectless ClassClosure');
	}
	
	public function get_alias()
	{
		if ($this->alias===null)
		{
			class_alias(get_class($this->object), $this->alias=$this->generate_alias());
		}
		return $this->alias;
	}
	
	protected function generate_alias()
	{
		return 'TempClass'.++static::$next_free_temporary_class;
	}
	
	public function __call($method, $args)
	{
		return $this->reflection->$method(...$args);
	}
}

class Module
{
	protected
		$config,
		$framework,
		$module_dir='',
		$classes=[],
		$functions=[],
		$loaded=[],
		
		$quick_classes=[];
	
	public static function from_config($module_config, $framework)
	{
		$module_dir=$module_config['dir'];
		$module=include($framework->modules_dir().'/'.$module_dir.'/header.php');
		$module->init_for($framework, $module_config);
		return $module;
	}
	
	protected function init_for($framework, $config)
	{
		$this->framework=$framework;
		$this->apply_config($config);
	}
	
	protected function apply_config($config)
	{
		$this->config=$config;
	}
	
	public function module_dir()
	{
		return $this->framework->modules_dir().'/'.$this->config['dir'];
	}
	
	public function get_class($class_name)
	{
		if (!array_key_exists($class_name, $this->classes)) $this->classes[$class_name]=$this->load_class($class_name);
		return $this->classes[$class_name];
	}
	
	public function get_class_instance($class_name, ...$args)
	{
		$class=$this->get_class($class_name);
		return $class->newInstanceArgs($args);
	}
	
	protected function load_class($class_name)
	{
		$relative_address=$this->get_class_filename($class_name);
		$load=$this->load_file($relative_address);
		return $load['classes'][$class_name];
	}
	
	protected function get_class_filename($class_name)
	{
		if (in_array($class_name, $this->quick_classes)) return [$class_name, 'class'];
		die('INIMPLEMENTED YET: non-quick classes ('.$class_name.')');
	}
	
	protected function load_file($relative_address)
	{
		$composed_addres=$this->compose_relative_address($relative_address);
		if (!array_key_exists($composed_addres, $this->loaded))
		{
			$result=include($this->module_dir().'/'.$composed_addres);
			if (!empty($relative_address[1])) $result=$this->parse_load_result($result, $relative_address);
			$this->loaded[$composed_addres]=$result;
		}
		return $this->loaded[$composed_addres];
	}
	
	protected function compose_relative_address($address_data)
	{
		$result=$address_data[0];
		if (!empty($address_data[1])) $result.='.'.$address_data[1];
		return $result.'.php';
	}
	
	protected function parse_load_result($result, $address_data)
	{
		$filebase=$address_data[0];
		$filetype=$address_data[1];
		if ($filetype==='class') return ['classes'=>[$filebase=>$result]];
		die('INIMPLEMENTED YET: other filetypes');
	}
}