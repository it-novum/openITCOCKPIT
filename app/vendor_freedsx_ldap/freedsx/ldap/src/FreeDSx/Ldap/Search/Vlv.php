<?php
/**
 * This file is part of the FreeDSx LDAP package.
 *
 * (c) Chad Sikorra <Chad.Sikorra@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FreeDSx\Ldap\Search;

use FreeDSx\Ldap\Control\Control;
use FreeDSx\Ldap\Control\Sorting\SortingControl;
use FreeDSx\Ldap\Controls;
use FreeDSx\Ldap\Control\Vlv\VlvControl;
use FreeDSx\Ldap\Control\Vlv\VlvResponseControl;
use FreeDSx\Ldap\Entry\Entries;
use FreeDSx\Ldap\Exception\ProtocolException;
use FreeDSx\Ldap\LdapClient;
use FreeDSx\Ldap\Operation\Request\SearchRequest;
use FreeDSx\Ldap\Operation\Response\SearchResponse;
use FreeDSx\Ldap\Search\Filter\GreaterThanOrEqualFilter;

/**
 * Provides a simple wrapper around a VLV (Virtual List View) search operation.
 *
 * @author Chad Sikorra <Chad.Sikorra@gmail.com>
 */
class Vlv
{
    /**
     * @var LdapClient
     */
    protected $client;

    /**
     * @var SearchRequest
     */
    protected $search;

    /**
     * @var VlvResponseControl|null
     */
    protected $control;

    /**
     * @var int
     */
    protected $before;

    /**
     * @var int
     */
    protected $after;

    /**
     * @var int|null
     */
    protected $offset = 1;

    /**
     * @var GreaterThanOrEqualFilter
     */
    protected $filter;

    /**
     * @var SortingControl
     */
    protected $sort;

    /**
     * @var bool
     */
    protected $asPercentage = false;

    /**
     * @param LdapClient $client
     * @param SearchRequest $search
     * @param SortingControl|\FreeDSx\Ldap\Control\Sorting\SortKey|string $sort
     * @param int $before
     * @param int $after
     */
    public function __construct(LdapClient $client, SearchRequest $search, $sort, int $after = 100, int $before = 0)
    {
        $this->client = $client;
        $this->search = $search;
        $this->sort = $sort instanceof SortingControl ? $sort : Controls::sort($sort);
        $this->before = $before;
        $this->after = $after;
    }

    /**
     * As a percentage the moveTo, moveForward, moveBackward, and position methods work with numbers 0 - 100 and should
     * be interpreted as percentages.
     *
     * @param bool $asPercentage
     * @return $this
     */
    public function asPercentage(bool $asPercentage = true)
    {
        $this->asPercentage = $asPercentage;

        return $this;
    }

    /**
     * Request to start at a specific offset/percentage of entries.
     *
     * @param int $offset
     * @return $this
     */
    public function startAt(int $offset)
    {
        $this->offset = $offset;

        return $this;
    }

    /**
     * Move backward the specified number or percentage from the current position.
     *
     * @param int $size
     * @return $this
     */
    public function moveBackward(int $size)
    {
        $this->offset = ($this->offset - $size < 0) ? 0 : $this->offset - $size;

        return $this;
    }

    /**
     * Move forward the specified number or percentage from the current position.
     *
     * @param int $size
     * @return $this
     */
    public function moveForward(int $size)
    {
        $this->offset = ($this->asPercentage && ($this->offset + $size) > 100) ? 100 : $this->offset + $size;

        return $this;
    }

    /**
     * Moves the starting entry of the list to a specific position/percentage of the total list. An alias for startAt().
     *
     * @param int $position
     * @return Vlv
     */
    public function moveTo(int $position)
    {
        return $this->startAt($position);
    }

    /**
     * Retrieve the following number of entries after the position specified.
     *
     * @param int $after
     * @return $this
     */
    public function afterPosition(int $after)
    {
        $this->after = $after;

        return $this;
    }

    /**
     * Retrieve the following number of entries before the position specified.
     *
     * @param int $before
     * @return $this
     */
    public function beforePosition(int $before)
    {
        $this->before = $before;

        return $this;
    }

    /**
     * Get the servers entry offset of the current list.
     *
     * @return int|null
     */
    public function listOffset() : ?int
    {
        return $this->control ? $this->control->getOffset() : null;
    }

    /**
     * Get the severs estimate, from the last request, that indicates how many entries are in the list.
     *
     * @return int|null
     */
    public function listSize() : ?int
    {
        return $this->control ? $this->control->getCount() : null;
    }

    /**
     * Get the current position in the list. When as percentage was specified this will be expressed as a percentage.
     * Use listOffset for a specific entry offset position.
     *
     * @return int|null
     */
    public function position() : ?int
    {
        $pos = $this->control ? $this->control->getOffset() : null;

        if ($this->asPercentage && $pos !== null) {
            $pos = round($this->control->getOffset() / ($this->control->getCount() / 100));
        }

        return $pos;
    }

    /**
     * Whether or not we are at the end of the list.
     *
     * @todo Is there a better way to determine if we are already at the end of the list based on last received?
     * @return bool
     */
    public function isAtEndOfList() : bool
    {
        if ($this->control === null) {
            return false;
        }

        return $this->control->getOffset() === $this->control->getCount();
    }

    /**
     * Whether or not we are currently at the start of the list.
     *
     * @todo Is there a better way to determine if we are already at the start of the list based on last received?
     * @return bool
     */
    public function isAtStartOfList() : bool
    {
        if ($this->control === null) {
            return false;
        }

        return $this->control->getOffset() === 1;
    }

    /**
     * @return Entries
     * @throws ProtocolException
     */
    public function getEntries() : Entries
    {
        return $this->send();
    }

    /**
     * @return Entries
     * @throws ProtocolException
     */
    protected function send()
    {
        $contextId = $this->control ? $this->control->getContextId() : null;
        $message = $this->client->send($this->search, $this->createVlvControl($contextId), $this->sort);
        $this->control = $message->controls()->get(Control::OID_VLV_RESPONSE);

        if (!$this->control) {
            throw new ProtocolException('Expected a VLV response control, but received none.');
        }
        /** @var SearchResponse $response */
        $response = $message->getResponse();

        return $response->getEntries();
    }

    /**
     * @param $contextId
     * @return VlvControl
     */
    protected function createVlvControl($contextId) : VlvControl
    {
        if ($this->filter) {
            return Controls::vlvFilter($this->before, $this->after, $this->filter, $contextId);
        }
        # An offset of 1 and a content size of zero starts from the beginning entry (server uses its assumed count).
        $count = $this->control ? $this->control->getCount() : 0;

        # In percentage mode start off with an assumed count of 100, as the formula the server uses should give us the
        # expected result
        if (!$this->control && $this->asPercentage) {
            $count = 100;
        }

        $offset = $this->offset;
        # Final checks to make sure if we are using a percentage that valid values are used.
        if ($this->asPercentage && $this->offset > 100) {
            $offset = 100;
        } elseif ($this->asPercentage && $this->offset < 0) {
            $offset = 0;
        }

        if ($this->asPercentage && $this->control) {
            $offset = round(($this->control->getCount() / 100) * $offset);
        }

        return Controls::vlv($this->before, $this->after, $offset, $count, $contextId);
    }
}
