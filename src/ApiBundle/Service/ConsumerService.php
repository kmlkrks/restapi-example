<?php

namespace ApiBundle\Service;

/**
 * Class ConsumerService
 * @package ApiBundle\Service
 * @author Kemal KARAKAŞ <kmlkarakas@gmail.com>
 */
class ConsumerService
{
    const CONSUMER_DEFAULT_NAME = 'default';

    const DEFAULT_CONSUMER_KEY = '92d63cdc643dfe73f3031004d250970d';

    /**
     * @var array
     */
    public $consumers = array(
        self::DEFAULT_CONSUMER_KEY => self::CONSUMER_DEFAULT_NAME
    );

    /**
     * @param string $consumerKey
     * @return boolean
     */
    public function checkConsumerKeyValid($consumerKey)
    {
        if (array_key_exists($consumerKey, $this->consumers)) {
            return true;
        }

        return false;
    }
} 