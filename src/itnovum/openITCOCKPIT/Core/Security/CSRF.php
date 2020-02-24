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
    protected $csrfUseOnce = true;

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
     */
    public function __construct(\SessionComponent $Session) {
        $this->Session = $Session;

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
    private function _generateToken() {
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
     * Generate a new Token stores it to $_SESSION and return the current token
     * @return string
     */
    public function generateToken() {
        $newToken = $this->_generateToken();
        $this->tokens[$newToken] = strtotime($this->csrfExpires);

        $csrf = [
            'latestToken' => $newToken,
            'csrfTokens'  => $this->tokens
        ];

        $this->Session->write('_csrf', $csrf);

        return $newToken;
    }


    public function blackhole() {
        throw new \BadRequestException(__d('cake_dev', 'The request has been black-holed'));
    }

}
