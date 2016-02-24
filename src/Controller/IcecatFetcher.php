<?php

namespace haringsrob\Icecat\Controller;

use haringsrob\Icecat\Model\Icecat;

/**
 * Class IcecatFetcher
 *
 * This class acts as a communication helper to fetch data from icecat.
 */
class IcecatFetcher extends Icecat
{
    /**
     * The icecat username.
     *
     * @var string
     */
    private $username;

    /**
     * The plain text password.
     * @var string
     */
    private $password;

    /**
     * Errors we have gotten.
     *
     * @var array
     */
    public $errors;

    /**
     * Sets the username to be used in the fetching process.
     *
     * @param string $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     * Gets the username.
     *
     * @return string $username
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Sets the password to be used in the fetching process.
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * Gets the password.
     *
     * @return string $password
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Returns if there were any errors.
     *
     * @return array|bool
     */
    public function hasErrors()
    {
        if (is_array($this->errors)) {
            return $this->errors['error'];
        }
        return false;
    }

    /**
     * Connects with the server and reads out the data.
     *
     * @return SimpleXML Object|bool
     */
    public function getBaseData()
    {
        // Our base return.
        $return = false;
        // Loop all our urls, if we get a result, return it.
        foreach ($this->getUrls() as $url) {
            $options = array(
                'http' => array(
                    'header' => "Authorization: Basic " . base64_encode($this->username . ":" . $this->password),
                ),
            );
            $context = stream_context_create($options);
            $data = file_get_contents($url, false, $context);
            $xml = simplexml_load_string($data);
            // Check for errors.
            if (!empty($xml->Product['ErrorMessage'])) {
                $this->errors['error'] = array(
                    'message' => $xml->Product['ErrorMessage']->__toString(),
                    'code' => $xml->Product['Code']->__toString(),
                    'type' => 'error',
                );
                return false;
            } elseif ($xml && !empty($xml)) {
                $this->icecat_data = $xml;
                return true;
            } else {
                $this->errors['error'] = array(
                    'message' => 'Empty response.',
                    'code' => 2,
                    'type' => 'error',
                );
                return false;
            }
        }
        // No loop, no data.
        $this->errors['error'] = array(
            'message' => 'No valid urls.',
            'code' => 2,
            'type' => 'error',
        );
        return false;
    }
}
