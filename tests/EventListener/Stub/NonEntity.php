<?php


namespace App\Tests\EventListener\Stub;


class NonEntity
{
    /**
     * @var string
     */
    protected $token;

    /**
     * @var string
     */
    protected $logo;

    /**
     * @return string
     */
    public function getToken(): string
    {
        return $this->token;
    }

    /**
     * @param string $token
     *
     * @return NonEntity
     */
    public function setToken(string $token): NonEntity
    {
        $this->token = $token;

        return $this;
    }

    /**
     * @return string
     */
    public function getLogo(): string
    {
        return $this->logo;
    }

    /**
     * @param string $logo
     *
     * @return NonEntity
     */
    public function setLogo(string $logo): NonEntity
    {
        $this->logo = $logo;

        return $this;
    }
}
