<?php


namespace App\Services\Contracts;

interface APIInterface
{
    /**
     * Returns API base URL.
     *
     * @return string
     */
    public function baseUrl();

    /**
     * Returns the number of items to return per page.
     *
     * @return void
     */
    public function getPerPage();

    /**
     * Set the number of items  to return per page.
     *
     * @param  int $perPage
     * @return $this
     */
    public function setPerPage(int $perPage): static;

    /**
     * Send a GET request.
     *
     * @param string $url
     * @param array $parameter
     * @return array
     */
    public function _get(?string $url = null, $parameter = []): array;

    /**
     * Send a header request.
     *
     * @param string $url
     * @param array $parameter
     * @return array
     */
    public function _head(?string $url = null, array $parameter = []): array;

    /**
     * Send a Delete Request.
     *
     * @param string $url
     * @param array $parameter
     * @return mixed
     */
    public function _delete(?string $url = null, array $parameter = []): array;

    /**
     * Send a PUT  request.
     *
     * @param string $url
     * @param array $parameter
     * @return array
     */
    public function _put(?string $url = null, array $parameter = []): array;

    /**
     * Send a PATCH request.
     *
     * @param string $url
     * @param array $parameter
     * @return array
     */
    public function _patch(?string $url = null, array $parameter = []): array;

    /**
     * Send a POST request.
     *
     * @param string $url
     * @param array $parameter
     * @return array
     */
    public function _post(?string $url = null, array $parameter = []): array;

    /**
     * Send an OPTION request.
     *
     * @param string $url
     * @param array $parameter
     * @return array
     */
    public function _options(?string $url = null, array $parameter = []): array;
}
