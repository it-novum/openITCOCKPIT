<?php

/**
 * Custom CakeResponse for Service calls
 * @package default
 */
class ServiceResponse extends CakeResponse
{
    /**
     * Constructor
     *
     * @param string $code One of Types::CODE_*, or an array containing 'code' and 'data' keys
     * @param array  $data data to return
     */
    public function __construct($code, array $data = [])
    {
        if (is_array($code)) {
            $body = Set::merge([
                'code' => Types::CODE_SUCCESS,
                'data' => [],
            ], $code);
        } else {
            $body = [
                'code' => $code,
                'data' => $data,
            ];
        }
        $options = [
            'type' => 'json',
            'body' => json_encode($body),
        ];
        parent::__construct($options);
    }
}