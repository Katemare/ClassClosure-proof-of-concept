<?php

/*

This example demonstrates 1) usefulness of having a Closure-like class to represent anonymous classes; 2) technical capability of current PHP7 to handle these operations with anonymous classes. Therefore, it's the matter of having the required syntax because internal mechanisms are already in place and functional.

This is a framework capable of running consecutive versions of the same extension simultaneously without name collisions. It's based on anonymous classes: no names - no name collisions! Namespacing can't help in this case because these are versions of the same extensions, and namespace shouldn't be changed between minor versions.

Anonymous classes are differentiated by their place in code (so classes from two copies of a file in different locations are treated as different classes); and by the storage structure of the framework. They are addressed by the module that owns them.

The example Framework is configured like this:
- Zoo module contains Animal class with eat() function.
- AlienZoo module is designed to rely on Zoo module. It inherits the Animal class to its Coeurl class.
- "Zoo_beta" folder contains a "later version" (not a new release!) of the Zoo module. The Animal class has a slightly different implementation.
- "AlienZoo_beta" folder also contains a "later version" of AlienZoo module. configured to work with "Zoo_beta" In this example, it's incompatible to the obsolete Zoo version, but it doesn't have to be the case. I just don't want to involve Interfaces in this example (and it would preferably require anonymous traits and interfaces, outside of current technical capacity).
- ZooKeeper is a module that uses both Zoo and "Zoo_beta" by instantiating objects of Animal class and executing "eat" function. It can also test AlienZoo and Coeurl species, but currently it's unavailable due to a bug.

In frameworks's files there are comments containing the preferred syntax, such as being able to work with anonymous class directly - class() { } - and not by instantiating it - new ReflectionClass(new class() {...}) . The temporary object is created just to to gain access to the new class! It's unnecessary.

An object representing a class is not unheard of and is widely used in languages like JavaScript and Ruby. There are design patterns involving this feature.

This very example is born of a practical need. Our website is a creative/gaming community with a custom engine. The game is often updated, both technically and gameplay-wise. Admins want to test new versions prior to upgrading the game. For now, it can only be done by having a sub-domain with a differently configured engine. It doesn't allow for many real usage situations. It does isolate possible problems from the production... But when the closed testing is done, we would like to have an additional live stage of trying out updates. This would prevent many issues and allow us to iterate game design decisions before committing to them. Of course, this is impossible due to name collisions...

*/

include('Framework.php');

$framework=new Framework
([
	'modules'=>
	[
		'zoo'=>
		[
			'dir'=>'Zoo',
		],
		'alien_zoo'=>
		[
			'dir'=>'AlienZoo',
		],
		'zoo_beta'=>
		[
			'dir'=>'Zoo_beta',
		],
		'alien_zoo_beta'=>
		[
			'dir'=>'AlienZoo_beta',
			'zoo_key'=>'zoo_beta'
		],
		'zoo_master'=>
		[
			'dir'=>'ZooMaster',
			'zoo_list'=>['alien_zoo', 'alien_zoo_beta'],
			'test_species'=>'Coeurl',
		]
	]
]);

$framework->get_module('zoo_master')->test_zoos();