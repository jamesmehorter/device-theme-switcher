<?php
	// Bail if this file is being accessed directly
	defined( 'ABSPATH' ) OR exit;

	/**
	 * Reusable Singleton Class
	 *
	 * This class is meant to be extended
	 * 
	 * Example: 
	 * ```
	 * <?php
	 *
	 * /**
	 * 	* My Class..
	 * *\/
	 * class DTS_MyClass extends DTS_Singleton {
	 * 		public function foo () {
	 * 			echo 'foo';
	 * 		}
	 * }
	 *
	 * // Fetch the instance of our class
	 * $dts_myclass_instance = DTS::MyClass->factory();
	 *
	 * // outputs 'foo'
	 * $dts_myclass_instance->foo();
	 * 
	 * ?>
	 * ```
	 */
	abstract class DTS_Singleton {

		/**
		 * Internally-stored instance of the class
		 *
		 * DO NOT CALL THIS PROPERTY DIRECTLY, 
		 * use the MyClass::factory() method below instead
		 *
		 * By using an array, we can store singltetons for many classes
		 * This makes it possible to be a base class, meant to be extended
		 * @var array
		 */
		protected static $_instance = array();

		/**
		 * All extended child classes 
		 */
		protected function  __construct() { }

		/**
		 * Returns new or existing Singleton instance
		 *
		 * Call this method instead of using 'new MyClass ;'
		 * 
		 * Example:
		 *
		 * class DTS_MyClass extends DTS_Singleton {
		 * 		public function __construct {
		 * 			$this->doMethod();
		 * 		}
		 * 		public function doMethod {
		 * 			// this runs on instantiation
		 * 		}
		 * 		public function doAnotherMethod {
		 * 		}
		 * }
		 * 
		 * $myClassInstance = DTS_MyClass::factory;
		 * $myClassInstance->doAnotherMethod;
		 *  
		 * @return Singleton
		 */
		final static function factory() {
			// Reference the class being instantiated
			$class = get_called_class();

			// Is there already an instance of the class?
			if ( ! isset( self::$_instance[ $class ] ) ) {

				// If not, instantiate a new instance
				self::$_instance[ $class ] = new $class();
			}

			// Return the single instance of the class
			return self::$_instance[ $class ];

		} // function factory

	} // class DTS_Singleton


	// EOF