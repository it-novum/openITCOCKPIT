<?php
/**
 * This file is part of the FreeDSx LDAP package.
 *
 * (c) Chad Sikorra <Chad.Sikorra@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FreeDSx\Ldap\Protocol\Factory;

use FreeDSx\Ldap\Operation\LdapResult;
use FreeDSx\Ldap\Operation\Request\AddRequest;
use FreeDSx\Ldap\Operation\Request\BindRequest;
use FreeDSx\Ldap\Operation\Request\CompareRequest;
use FreeDSx\Ldap\Operation\Request\DeleteRequest;
use FreeDSx\Ldap\Operation\Request\ExtendedRequest;
use FreeDSx\Ldap\Operation\Request\ModifyDnRequest;
use FreeDSx\Ldap\Operation\Request\ModifyRequest;
use FreeDSx\Ldap\Operation\Request\RequestInterface;
use FreeDSx\Ldap\Operation\Request\SearchRequest;
use FreeDSx\Ldap\Operation\Response\AddResponse;
use FreeDSx\Ldap\Operation\Response\BindResponse;
use FreeDSx\Ldap\Operation\Response\CompareResponse;
use FreeDSx\Ldap\Operation\Response\DeleteResponse;
use FreeDSx\Ldap\Operation\Response\ExtendedResponse;
use FreeDSx\Ldap\Operation\Response\ModifyDnResponse;
use FreeDSx\Ldap\Operation\Response\ModifyResponse;
use FreeDSx\Ldap\Operation\Response\ResponseInterface;
use FreeDSx\Ldap\Operation\Response\SearchResultDone;


/**
 * For a specific request and result code/diagnostic, get the response object if possible.
 *
 * @author Chad Sikorra <Chad.Sikorra@gmail.com>
 */
class ResponseFactory
{
    /**
     * @param RequestInterface $request
     * @param int $resultCode
     * @param string $diagnostic
     * @return ResponseInterface|null
     */
    public static function get(RequestInterface $request, int $resultCode, string $diagnostic = '') : ?ResponseInterface
    {
        $response = null;

        switch ($request) {
            case $request instanceof BindRequest:
                $response = new BindResponse(new LdapResult($resultCode, '', $diagnostic));
                break;
            case $request instanceof SearchRequest:
                $response = new SearchResultDone($resultCode, '', $diagnostic);
                break;
            case $request instanceof AddRequest:
                $response = new AddResponse($resultCode, $request->getEntry()->getDn()->toString(), $diagnostic);
                break;
            case $request instanceof CompareRequest:
                $response = new CompareResponse($resultCode, $request->getDn()->toString(), $diagnostic);
                break;
            case $request instanceof DeleteRequest:
                $response = new DeleteResponse($resultCode, $request->getDn()->toString(), $diagnostic);
                break;
            case $request instanceof ModifyDnRequest:
                $response = new ModifyDnResponse($resultCode, $request->getDn()->toString(), $diagnostic);
                break;
            case $request instanceof ModifyRequest:
                $response = new ModifyResponse($resultCode, $request->getDn()->toString(), $diagnostic);
                break;
            case $request instanceof ExtendedRequest:
                $response = new ExtendedResponse(new LdapResult($resultCode, '', $diagnostic));
                break;
        }

        return $response;
    }
}
