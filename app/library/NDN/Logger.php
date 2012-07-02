<?php
/**
 * Logger.php
 * NDN_Logger
 *
 * Extends the Phalcon_Logger to perform logging operations
 *
 * @author      Nikos Dimopoulos <nikos@NDN.net>
 * @since       6/24/12
 * @category    Library
 * @license     MIT - https://github.com/NDN/phalcon-angular-harryhogfootball/blob/master/LICENSE
 *
 */

class NDN_Logger extends Phalcon_Logger
{
    /**
     * Constructor. Overrides default behavior to initialize the logger
     * from the config
     *
     * @param Phalcon_Config $config
     * @param array          $options
     * @throws NDN_Exception
     */
    public function __construct(Phalcon_Config $config, $options = null)
    {
        if (empty($config->logger->adapter)) {
            throw new NDN_Exception(
                'Cannot instantiate Logger: Adapter config setting missing'
            );
        }

        if (empty($config->logger->file)) {
            throw new NDN_Exception(
                'Cannot instantiate Logger: File config setting missing'
            );
        }

        $adapter = $config->logger->adapter;
        $file    = ROOT_PATH . $config->logger->file;
        $format  = !empty($config->logger->format) ?
                    $config->logger->format        : '';

        parent::__construct($adapter, $file, $options);

        if ($format) {
            $this->setFormat($format);
        }
    }
}