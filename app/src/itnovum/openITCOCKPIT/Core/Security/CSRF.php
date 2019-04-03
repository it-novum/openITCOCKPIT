<?php
// Copyright (C) <2018>  <it-novum GmbH>
//
// This file is dual licensed
//
// 1.
//  This program is free software: you can redistribute it and/or modify
//  it under the terms of the GNU General Public License as published by
//  the Free Software Foundation, version 3 of the License.
//
//  This program is distributed in the hope that it will be useful,
//  but WITHOUT ANY WARRANTY; without even the implied warranty of
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//  GNU General Public License for more details.
//
//  You should have received a copy of the GNU General Public License
//  along with this program.  If not, see <http://www.gnu.org/licenses/>.
//
// 2.
//  If you purchased an openITCOCKPIT Enterprise Edition you can use this file
//  under the terms of the openITCOCKPIT Enterprise Edition license agreement.
//  License agreement and license key will be shipped with the order
//  confirmation.

namespace itnovum\openITCOCKPIT\Core\Security;


class CSRF {

    /**
     * @var \SessionComponent
     */
    protected $Session;

    /**
     * @var \CookieComponent
     */
    protected $Cookie;

    /**
     * strtotime compatible string
     * Set time how long a token is valide
     * @var string
     */
    protected $csrfExpires = '+30 minutes';

    /**
     * Maximum number of tokens stored in session
     * @var int
     */
    protected $csrfLimit = 150;

    /**
     * Remove CSRF tokens after use
     * Can cause issues for Single Page applications
     * @var boolean
     */
    protected $csrfUseOnce = false;

    /**
     * Token length
     * @var int
     */
    protected $length = 16;

    /**
     * Token storage from and for $_SESSION
     * @var array
     */
    protected $tokens = [];

    /**
     * CSRF constructor.
     * @param \SessionComponent $Session
     * @param \CookieComponent $Cookie
     */
    public function __construct(\SessionComponent $Session, \CookieComponent $Cookie) {
        $this->Session = $Session;
        $this->Cookie = $Cookie;

        $tokens = $this->readTokens();
        $this->tokens = $this->expireTokens($tokens);
    }

    /**
     * @return array
     */
    protected function readTokens() {
        $csrf = $this->Session->read('_csrf');

        $tokens = [];
        if (isset($csrf['csrfTokens'])) {
            $tokens = $csrf['csrfTokens'];
            if (!is_array($tokens)) {
                $tokens = [];
            }
        }

        return $tokens;
    }

    /**
     * @param array $tokens
     * @return array
     */
    protected function expireTokens($tokens) {
        $now = time();
        foreach ($tokens as $nonce => $expires) {
            if ($expires < $now) {
                unset($tokens[$nonce]);
            }
        }
        $overflow = count($tokens) - $this->csrfLimit;
        if ($overflow > 0) {
            //Remove oldest tokens
            asort($tokens);
            $tokens = array_slice($tokens, $overflow + 1, null, true);
        }
        return $tokens;
    }

    /**
     * @return string
     */
    public function generateToken() {
        if ($this->length < 16) {
            throw new \SecurityException('Token length needs to be >= 16!');
        }

        return hash('sha512', openssl_random_pseudo_bytes($this->length));
    }

    /**
     * @param \Controller $Controller
     * @return bool
     */
    public function validateCsrfToken(\Controller $Controller) {
        $requestToken = $Controller->request->data('_csrfToken');
        if ($requestToken === null) {
            //Try to read from Cookie
            $requestToken = $this->Cookie->read('_csrfToken');
        }

        if (!$requestToken) {
            throw new \SecurityException('Missing CSRF token');
        }

        if (!isset($this->tokens[$requestToken])) {
            throw new \SecurityException('CSRF token mismatch');
        }

        if ($this->tokens[$requestToken] < time()) {
            throw new \SecurityException('CSRF token expired');
        }

        if (isset($this->tokens[$requestToken])) {

            if ($this->csrfUseOnce) {
                unset($this->tokens[$requestToken]);
            }
            return true;
        }

        $this->blackhole();
    }

    /**
     * @return array
     */
    public function storeTokens() {

        $newToken = $this->generateToken();
        $this->tokens[$newToken] = strtotime($this->csrfExpires);

        $csrf = [
            'latestToken' => $newToken,
            'csrfTokens'  => $this->tokens
        ];

        $this->Cookie->write('_csrfToken', $newToken);
        $this->Session->write('_csrf', $csrf);
    }

    public function generateTokenIfNonExists(){
        if(empty($this->tokens)){
            $this->storeTokens();
        }
    }

    public function blackhole() {
        $this->storeTokens();
        throw new \BadRequestException(__d('cake_dev', 'The request has been black-holed'));
    }

}
