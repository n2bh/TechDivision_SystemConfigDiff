<?php
/**
 * TechDivision_SystemConfigDiff_Helper_Config
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 *
 * @category   TechDivision
 * @package    TechDivision_SystemConfigDiff
 * @subpackage Block
 * @copyright  Copyright (c) 1996-2011 TechDivision GmbH (http://www.techdivision.com)
 * @license	   http://www.gnu.org/licenses/gpl.html GPL, version 3
 * @version    ${release.version}
 * @link       http://www.techdivision.com
 * @since      File available since Release 0.1.2
 * @author     Florian Sydekum <fs@techdivision.com>
 */

/**
 * Config helper implementation.
 *
 * @category   TechDivision
 * @package    TechDivision_SystemConfigDiff
 * @subpackage Block
 * @copyright  Copyright (c) 2010 <info@techdivision.com> - TechDivision GmbH
 * @license    http://opensource.org/licenses/osl-3.0.php
 *             Open Software License (OSL 3.0)
 * @author     Florian Sydekum <fs@techdivision.com>
 */
class TechDivision_SystemConfigDiff_Helper_Config
    extends TechDivision_SystemConfigDiff_Helper_Config_Abstract
{
    /**
     * The module alias of this module
     */
    const MODULE_ALIAS = "techdivision_systemconfigdiff";

    /**
     * The registered differs
     * @var array
     */
    protected $_differs;

    /**
     * Returns the differs defined in config.xml
     *
     * @return array The differs
     */
    public function getDiffers()
    {
        // Check if differs are initiated already.
        if (!$this->_differs) {
            $this->_differs = array();

            /** @var Mage_Core_Model_Config $config */
            $config = Mage::app()->getConfig();

            // Get all differs defined in config.xml
            $differConfig = $config->getNode('global')->differs;

            // Iterate over all differ configs
            foreach ($differConfig->children() as $name => $config) {
                // Try to initialize the differs
                try {
                    // Get model class name
                    $modelClassName = (string) $config->class;
                    // Initialize a new differ instance
                    $this->_differs[$name] = Mage::getModel($modelClassName);
                }
                catch (Exception $e) {
                    Mage::log($e->getMessage());
                    Mage::throwException('Can not initialize differ "%s".', $modelClassName);
                }
            }
        }

        // Return differs
        return $this->_differs;
    }

    /**
     * Returns the module alias of this module
     *
     * @return string
     */
    public function getModuleAlias()
    {
        return self::MODULE_ALIAS;
    }
}