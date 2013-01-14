<?php
namespace Flush\Core\Model;
use Flush\Exception\TypeException;
use Flush\Exception\InternalSecurityException;
use Flush\Type\BaseType;

/**
 * @author Lucas Ceballos
 * @since 10/12/2012
 * @version 0.0.1
 * 
 */


abstract class TypedModel extends BaseModel {
	
	public function __construct($idObj = null, $readOnly = false){
		$this->createAttributes();
		BaseType::disableValidations();
		parent::__construct($idObj, $readOnly);
		BaseType::enableValidations();
	}	
	
	protected function getDescriptor(){
		return Descriptor::get(get_class($this));
	}
	
	/**
	 * Metodo encargado de leer el descriptor y crear los objetos 
	 * correspondientes a cada atributo del objeto
	 *
	 * @return void
	 */
	protected final function createAttributes() {		
		foreach ( $this->descriptor as $attrName => $attrConfig) {
			if ( isset($attrConfig['Type']) ) {
				$typeClass = BaseType::PREFIX . $attrConfig['Type'];
				if ( class_exists($typeClass) ) {
					if ( isset($attrConfig['Constraints']['ReferenceValue']) ) {
						$attrConfig['Constraints']['ReferenceValue'] = &$this->$attrConfig['Constraints']['ReferenceValue'];
					}
					if ( $typeClass == BaseType::PREFIX . 'ObjectList') {
						$attrConfig['Constraints']['FromClass'] = get_class($this);
					}
					$arg = isset($attrConfig['Constraints']) ? $attrConfig['Constraints'] : null;
					$this->$attrName = new $typeClass($arg);
				}
			}
		}
	}	
	
} 

?>