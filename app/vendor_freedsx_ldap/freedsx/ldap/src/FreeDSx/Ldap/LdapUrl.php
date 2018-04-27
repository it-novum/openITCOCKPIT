<?php
/**
 * This file is part of the FreeDSx LDAP package.
 *
 * (c) Chad Sikorra <Chad.Sikorra@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FreeDSx\Ldap;

use FreeDSx\Ldap\Entry\Attribute;
use FreeDSx\Ldap\Entry\Dn;
use FreeDSx\Ldap\Exception\InvalidArgumentException;
use FreeDSx\Ldap\Exception\UrlParseException;

/**
 * Represents a LDAP URL. RFC 4516.
 *
 * @see https://tools.ietf.org/html/rfc4516
 * @author Chad Sikorra <Chad.Sikorra@gmail.com>
 */
class LdapUrl
{
    use LdapUrlTrait;

    public const SCOPE_BASE = 'base';

    public const SCOPE_ONE = 'one';

    public const SCOPE_SUB = 'sub';

    /**
     * @var null|int
     */
    protected $port;

    /**
     * @var bool
     */
    protected $useSsl = false;

    /**
     * @var null|string
     */
    protected $host;

    /**
     * @var null|Dn
     */
    protected $dn;

    /**
     * @var null|string
     */
    protected $scope;

    /**
     * @var string[]
     */
    protected $attributes = [];

    /**
     * @var null|string
     */
    protected $filter;

    /**
     * @var LdapUrlExtension[]
     */
    protected $extensions = [];

    /**
     * @param null|string $host
     */
    public function __construct(?string $host = null)
    {
        $this->host = $host;
    }

    /**
     * @param null|string|Dn $dn
     * @return $this
     */
    public function setDn($dn)
    {
        $this->dn = $dn === null ? $dn : new Dn($dn);

        return $this;
    }

    /**
     * @return Dn
     */
    public function getDn() : ?Dn
    {
        return $this->dn;
    }

    /**
     * @return mixed
     */
    public function getHost() : ?string
    {
        return $this->host;
    }

    /**
     * @param null|string $host
     * @return $this
     */
    public function setHost(?string $host)
    {
        $this->host = $host;

        return $this;
    }

    /**
     * @param int|null $port
     * @return $this
     */
    public function setPort(?int $port)
    {
        $this->port = $port;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getPort() : ?int
    {
        return $this->port;
    }

    /**
     * @return null|string
     */
    public function getScope() : ?string
    {
        return $this->scope;
    }

    /**
     * @param null|string $scope
     * @return $this
     */
    public function setScope(?string $scope)
    {
        $scope = $scope === null ? $scope : strtolower($scope);
        if ($scope !== null && !in_array($scope, [self::SCOPE_BASE, self::SCOPE_ONE, self::SCOPE_SUB])) {
            throw new InvalidArgumentException(sprintf(
                'The scope "%s" is not valid. It must be one of: %s, %s, %s',
                $scope,
                self::SCOPE_BASE,
                self::SCOPE_ONE,
                self::SCOPE_SUB
            ));
        }
        $this->scope = $scope;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getFilter() : ?string
    {
        return $this->filter;
    }

    /**
     * @param null|string $filter
     * @return $this
     */
    public function setFilter(?string $filter)
    {
        $this->filter = $filter;

        return $this;
    }

    /**
     * @return LdapUrlExtension[]
     */
    public function getExtensions() : array
    {
        return $this->extensions;
    }

    /**
     * @param LdapUrlExtension[] ...$extensions
     * @return $this
     */
    public function setExtensions(LdapUrlExtension ...$extensions)
    {
        $this->extensions = $extensions;

        return $this;
    }

    /**
     * @return Attribute[]
     */
    public function getAttributes() : array
    {
        return $this->attributes;
    }

    /**
     * @param string[]|Attribute[] ...$attributes
     * @return $this
     */
    public function setAttributes(...$attributes)
    {
        $attr = [];
        foreach ($attributes as $attribute) {
            $attr[] = $attribute instanceof Attribute ? $attribute : new Attribute($attribute);
        }
        $this->attributes = $attr;

        return $this;
    }


    /**
     * @param bool $useSsl
     * @return LdapUrl
     */
    public function setUseSsl(bool $useSsl)
    {
        $this->useSsl = $useSsl;

        return $this;
    }

    /**
     * @return bool
     */
    public function getUseSsl() : bool
    {
        return $this->useSsl;
    }

    /**
     * Get the string representation of the URL.
     *
     * @return string
     */
    public function toString() : string
    {
        $url = ($this->useSsl ? 'ldaps' : 'ldap').'://'.$this->host;

        if ($this->host !== null && $this->port !== null) {
            $url .= ':'.$this->port;
        }

        return $url.'/'.self::encode($this->dn).$this->getQueryString();
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->toString();
    }

    /**
     * Given a string LDAP URL, get its object representation.
     *
     * @param string $ldapUrl
     * @return LdapUrl
     * @throws UrlParseException
     */
    public static function parse(string $ldapUrl) : LdapUrl
    {
        $pieces = self::explodeUrl($ldapUrl);

        $url = new LdapUrl($pieces['host'] ?? null);
        $url->setUseSsl($pieces['scheme'] === 'ldaps');
        $url->setPort($pieces['port'] ?? null);
        $url->setDn((isset($pieces['path']) && $pieces['path'] !== '/') ? self::decode(ltrim($pieces['path'], '/')) : null);

        $query = explode('?', $pieces['query'] ?? '');
        if (!empty($query)) {
            $url->setAttributes(...($query[0] === '' ? [] : explode(',', $query[0])));
            $url->setScope(isset($query[1]) && $query[1] !== '' ? $query[1] : null);
            $url->setFilter(isset($query[2]) && $query[2] !== '' ? self::decode($query[2]) : null);

            $extensions = [];
            if (isset($query[3]) && $query[3] !== '') {
                $extensions = array_map(function ($ext) {
                    return LdapUrlExtension::parse($ext);
                }, explode(',', $query[3]));
            }
            $url->setExtensions(...$extensions);
        }

        return $url;
    }

    /**
     * @param string $url
     * @return array
     * @throws UrlParseException
     */
    protected static function explodeUrl(string $url) : array
    {
        $pieces = parse_url($url);

        if ($pieces === false || !isset($pieces['scheme'])) {
            # We are on our own here if it's an empty host, as parse_url will not treat it as valid, though it is valid
            # for LDAP URLs. In the case of an empty host a client should determine what host to connect to.
            if (!preg_match('/^(ldaps?)\:\/\/\/(.*)$/', $url, $matches)) {
                throw new UrlParseException(sprintf('The LDAP URL is malformed: %s', $url));
            }
            $query = null;
            $path = null;

            # Check for query parameters but no path...
            if (strlen($matches[2]) > 0 && $matches[2][0] === '?') {
                $query = substr($matches[2], 1);
            # Check if there are any query parameters and a possible path...
            } elseif (strpos($matches[2], '?') !== false) {
                $parts = explode('?', $matches[2], 2);
                $path = $parts[0];
                $query = isset($parts[1]) ? $parts[1] : null;
            # A path only...
            } else {
                $path = $matches[2];
            }

            $pieces = [
                'scheme' => $matches[1],
                'path' => $path,
                'query' => $query,
            ];
        }
        $pieces['scheme'] = strtolower($pieces['scheme']);

        if (!($pieces['scheme'] === 'ldap' || $pieces['scheme'] === 'ldaps')) {
            throw new UrlParseException(sprintf('The URL scheme "%s" is not valid. It must be "ldap" or "ldaps".', $pieces['scheme']));
        }

        return $pieces;
    }

    /**
     * Generate the query part of the URL string representation. Only generates the parts actually used.
     *
     * @return string
     */
    protected function getQueryString() : string
    {
        $query = [];

        if (!empty($this->attributes)) {
            $query[0] = implode(',', array_map(function ($v) {
                /** @var $v Attribute */
                return self::encode($v->getName());
            }, $this->attributes));
        }
        if ($this->scope !== null) {
            $query[1] = self::encode($this->scope);
        }
        if ($this->filter !== null) {
            $query[2] = self::encode($this->filter);
        }
        if (!empty($this->extensions)) {
            $query[3] = implode(',', $this->extensions);
        }

        if (empty($query)) {
            return '';
        }

        end($query);
        $last = key($query);
        reset($query);

        # This is so we stop at the last query part that was actually set, but also capture cases where the first and
        # third were set but not the second.
        $url = '';
        for ($i = 0; $i <= $last; $i++) {
            $url .= '?';
            if (isset($query[$i])) {
                $url .= $query[$i];
            }
        }

        return $url;
    }
}
