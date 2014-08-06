<?php

namespace Puffer\Storages;

use Symfony\Component\HttpFoundation\Session\Session as SymfonySession;

class Session implements StorageInterface
{

    const PREFIX = 'Puffer';

    protected $session;

    public function __construct()
    {
        if (NULL === $this->session) {
            $this->session = new SymfonySession;
            $this->session->start();
        }
    }

    public function set($name, $value)
    {
        return $this->session->set(self::sanitize($name), $value);
    }

    public function get($name)
    {
        if (!$this->has($name)) {
            return FALSE;
        }

        return $this->session->get(self::sanitize($name));
    }

    public function has($name)
    {
        return $this->session->has(self::sanitize($name));
    }

    public function remove($name)
    {
        if (!$this->has($name)) {
            return FALSE;
        }

        return $this->session->remove(self::sanitize($name));
    }

    protected static function sanitize($name)
    {
        if (!is_string($name)) {
            throw new \InvalidArgumentException('Please pass string as a name, no other types allowed.');
        }

        return self::PREFIX . '_' . str_replace(array('/', '\\', ' '), '_', trim($name));
    }

}
