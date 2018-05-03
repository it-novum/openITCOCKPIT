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

use FreeDSx\Ldap\Exception\FilterParseException;
use FreeDSx\Ldap\Search\Filter\AndFilter;
use FreeDSx\Ldap\Search\Filter\FilterInterface;
use FreeDSx\Ldap\Search\Filter\MatchingRuleFilter;
use FreeDSx\Ldap\Search\Filter\OrFilter;
use FreeDSx\Ldap\Search\Filter\SubstringFilter;

/**
 * Parses LDAP filter strings. RFC 4515.
 *
 * @author Chad Sikorra <Chad.Sikorra@gmail.com>
 */
class FilterParser
{
    protected const MATCHING_RULE = '/^([a-zA-Z0-9\.]+)?(\:dn)?(\:([a-zA-Z0-9\.]+))?$/';

    /**
     * @var string
     */
    protected $filter = '';

    /**
     * @var int
     */
    protected $length = 0;

    /**
     * @var int
     */
    protected $depth = 0;

    /**
     * @var null|array
     */
    protected $containers;

    /**
     * @param string $filter
     */
    public function __construct(string $filter)
    {
        $this->filter = $filter;
        $this->length = strlen($filter);
    }

    /**
     * @param string $filter
     * @return FilterInterface
     * @throws FilterParseException
     */
    public static function parse(string $filter) : FilterInterface
    {
        if ($filter === '') {
            throw new FilterParseException('The filter cannot be empty.');
        }

        return (new self($filter))->parseFilterString(0, true)[1];
    }

    /**
     * @param int $startAt
     * @param bool $isRoot
     * @return array
     * @throws FilterParseException
     */
    protected function parseFilterString(int $startAt, bool $isRoot = false) : array
    {
        if ($this->isAtFilterContainer($startAt)) {
            [$endsAt, $filter] = $this->parseFilterContainer($startAt, $isRoot);
        } else {
            [$endsAt, $filter] = $this->parseComparisonFilter($startAt, $isRoot);
        }
        if ($isRoot && $endsAt !== $this->length) {
            throw new FilterParseException(sprintf(
                'Unexpected value at end of the filter: %s',
                substr($this->filter, $endsAt)
            ));
        }

        return [$endsAt, $filter];
    }

    /**
     * @param int $pos
     * @return bool
     */
    protected function isAtFilterContainer(int $pos) : bool
    {
        if (!$this->startsWith(FilterInterface::PAREN_LEFT, $pos)) {
            return false;
        }

        foreach (FilterInterface::OPERATORS as $compOp) {
            if ($this->startsWith($compOp, $pos + 1)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Parse a filter container. Always returns the ending position followed by the filter container.
     *
     * @param int $startAt
     * @param bool $isRoot
     * @return array
     * @throws FilterParseException
     */
    protected function parseFilterContainer(int $startAt, bool $isRoot) : array
    {
        if (!$this->containers) {
            $this->parseContainerDepths();
        }
        $this->depth += $isRoot ? 0 : 1;
        if (!isset($this->containers[$this->depth])) {
            throw new FilterParseException(sprintf(
                'The container at position %s is unrecognized. Perhaps there\'s an unmatched "(".'.
                $startAt
            ));
        }
        $endAt = $this->containers[$this->depth]['endAt'];
        $operator = substr($this->filter, $startAt + 1, 1);

        if ($operator === FilterInterface::OPERATOR_NOT) {
            return [$endAt, $this->getNotFilterObject($startAt, $endAt)];
        }

        $startAt += 2;
        $filter = $operator === FilterInterface::OPERATOR_AND ? new AndFilter() : new OrFilter();
        while ($endAt !== ($startAt + 1)) {
            [$startAt, $childFilter] = $this->parseFilterString($startAt);
            $filter->add($childFilter);
        }

        return [$endAt, $filter];
    }

    /**
     * Parse a comparison operator. Always returns the ending position followed by the filter.
     *
     * @param int $startAt
     * @param bool $isRoot
     * @return array
     * @throws FilterParseException
     */
    protected function parseComparisonFilter(int $startAt, bool $isRoot = false) : array
    {
        $parenthesis = $this->validateComparisonFilter($startAt, $isRoot);
        $endAt = !$parenthesis && $isRoot ? $this->length : $this->nextClosingParenthesis($startAt) + 1;

        $attributeEndsAfter = null;
        $filterType = null;
        $valueStartsAt = null;
        for ($i = $startAt; $i < $endAt; $i++) {
            foreach (FilterInterface::FILTERS as $op) {
                if ($this->filter[$i] === $op) {
                    $filterType = $op;
                } elseif (($i + 1) < $endAt && $this->filter[$i].$this->filter[$i + 1] === $op) {
                    $filterType = $op;
                }
                if ($filterType) {
                    break;
                }
            }
            if ($filterType) {
                $attributeEndsAfter = $i - $startAt;
                $valueStartsAt = $i + strlen($filterType);
                break;
            }
        }
        $this->validateParsedFilter($filterType, $startAt, $valueStartsAt, $endAt);

        $attribute = substr($this->filter,$startAt + ($parenthesis ? 1 : 0), $attributeEndsAfter - ($parenthesis ? 1 : 0));
        $value = substr($this->filter, $valueStartsAt, $endAt - $valueStartsAt - ($parenthesis ? 1 : 0));

        if ($attribute === '') {
            throw new FilterParseException(sprintf(
                'The attribute is missing in the filter near position %s.',
                $startAt
            ));
        }

        return [$endAt, $this->getComparisonFilterObject($filterType, $attribute, $value)];
    }

    /**
     * Validates some initial filter logic and determines if the filter is wrapped in parenthesis (validly).
     *
     * @param int $startAt
     * @param bool $isRoot
     * @return bool
     * @throws FilterParseException
     */
    protected function validateComparisonFilter(int $startAt, bool $isRoot): bool
    {
        $parenthesis = true;

        # A filter without an opening parenthesis is only valid if it is the root. And it cannot have a closing parenthesis.
        if ($isRoot && !$this->startsWith(FilterInterface::PAREN_LEFT, $startAt)) {
            $parenthesis = false;
            $pos = false;
            try {
                $pos = $this->nextClosingParenthesis($startAt);
            } catch (FilterParseException $e) {}
            if ($pos !== false) {
                throw new FilterParseException(sprintf('The ")" at char %s has no matching parenthesis', $pos));
            }
        # If this is not a root filter, it must start with an opening parenthesis.
        } elseif (!$isRoot && !$this->startsWith(FilterInterface::PAREN_LEFT, $startAt)) {
            throw new FilterParseException(sprintf(
                'The character "%s" at position %s was unexpected. Expected "(".',
                $this->filter[$startAt],
                $startAt
            ));
        }

        return $parenthesis;
    }

    /**
     * Validate some basic aspects of the filter after it is parsed.
     *
     * @param string|null $filterType
     * @param int|null $startsAt
     * @param int|null $startValue
     * @param int $endAt
     * @throws FilterParseException
     */
    protected function validateParsedFilter(?string $filterType, ?int $startsAt, ?int $startValue, $endAt): void
    {
        if ($filterType === null) {
            throw new FilterParseException(sprintf(
                'Expected one of "%s" in the filter after position %s, but received none.',
                implode(', ', FilterInterface::FILTERS),
                $startsAt
            ));
        }
        if ($startValue === null || $startValue === $endAt -1) {
            throw new FilterParseException(sprintf(
                'Expected a value after "%s" at position %s, but got none.',
                $filterType,
                $startValue
            ));
        }
    }

    /**
     * @param string $operator
     * @param string $attribute
     * @param string $value
     * @return FilterInterface
     */
    protected function getComparisonFilterObject(string $operator, string $attribute, string $value) : FilterInterface
    {
        if ($operator === FilterInterface::FILTER_LTE) {
            return Filters::lessThanOrEqual($attribute, $this->unescapeValue($value));
        } elseif ($operator === FilterInterface::FILTER_GTE) {
            return Filters::greaterThanOrEqual($attribute, $this->unescapeValue($value));
        } elseif ($operator === FilterInterface::FILTER_APPROX) {
            return Filters::approximate($attribute, $this->unescapeValue($value));
        } elseif ($operator === FilterInterface::FILTER_EXT) {
            return $this->getMatchingRuleFilterObject($attribute, $this->unescapeValue($value));
        }

       if ($value === '*') {
           return Filters::present($attribute);
       }

       if (preg_match('/\*/', $value)) {
            return $this->getSubstringFilterObject($attribute, $value);
       } else {
            return Filters::equal($attribute, $this->unescapeValue($value));
       }
    }

    /**
     * @param string $attribute
     * @param string $value
     * @return MatchingRuleFilter
     * @throws FilterParseException
     */
    protected function getMatchingRuleFilterObject(string $attribute, string $value) : MatchingRuleFilter
    {
        if (!preg_match(self::MATCHING_RULE, $attribute, $matches)) {
            throw new FilterParseException(sprintf('The matching rule is not valid: %s', $attribute));
        }
        $matchRule = new MatchingRuleFilter(null, null, $value);

        # RFC 4511, 4.5.1.7.7: If the matchingRule field is absent, the type field MUST be present [..]
        if (empty($matches[4]) && empty($matches[1])) {
            throw new FilterParseException(sprintf(
               'If the matching rule is absent the attribute type must be present, but it is not: %s',
               $attribute
            ));
        }

        if (!empty($matches[1])) {
            $matchRule->setAttribute($matches[1]);
        }
        if (!empty($matches[2])) {
            $matchRule->setUseDnAttributes(true);
        }
        if (!empty($matches[4])) {
            $matchRule->setMatchingRule($matches[4]);
        }

        return $matchRule;
    }

    /**
     * @param string $attribute
     * @param string $value
     * @return SubstringFilter
     */
    protected function getSubstringFilterObject(string $attribute, string $value) : SubstringFilter
    {
        $filter = new SubstringFilter($attribute);
        $substrings = preg_split('/\*/', $value, -1, PREG_SPLIT_OFFSET_CAPTURE | PREG_SPLIT_NO_EMPTY);

        $contains = [];
        foreach ($substrings as $substring) {
            $substring[0] = $this->unescapeValue($substring[0]);
            if ($substring[1] === 0) {
                $filter->setStartsWith($substring[0]);
            } elseif (($substring[1] + strlen($substring[0])) === strlen($value)) {
                $filter->setEndsWith($substring[0]);
            } else {
                $contains[] = $substring[0];
            }
        }
        $filter->setContains(...$contains);

        return $filter;
    }

    /**
     * @param int $startAt
     * @param int $endAt
     * @return FilterInterface
     * @throws FilterParseException
     */
    protected function getNotFilterObject(int $startAt, int $endAt): FilterInterface
    {
        if ($this->isAtFilterContainer($startAt + 2)) {
            throw new FilterParseException(sprintf(
                'The "not" filter at position %s cannot contain multiple filters.',
                $startAt
            ));
        }
        $info = $this->parseComparisonFilter($startAt + 2);
        if (($info[0] + 1) !== $endAt) {
            throw new FilterParseException(sprintf(
                'The value after the "not" filter value was unexpected: %s',
                substr($this->filter, $info[0] + 1, $endAt - ($info[0] + 1))
            ));
        }

        return Filters::not($info[1]);
    }

    /**
     * @param string $char
     * @param int $pos
     * @return bool
     */
    protected function startsWith(string $char, int $pos) : bool
    {
        return isset($this->filter[$pos]) && $this->filter[$pos] === $char;
    }

    /**
     * This seems like a potential minefield. But the general idea is to loop through the complete filter to get the
     * start and end positions of each container along the way. The container index marks the depth, with the lowest (0)
     * being the furthest out and the higher numbers being closer inside. We skip positions inside the for loop when a
     * comparison filter is encountered to only capture beginning and end parenthesis of containers.
     *
     * Each container encountered has its end point marked by detecting the closing parenthesis then we loop through known
     * detected containers starting at the highest level that have no endpoints marked yet and working our way down.
     *
     * @throws FilterParseException
     */
    protected function parseContainerDepths() : void
    {
        $this->containers = [];

        $child = null;
        for ($i = 0; $i < $this->length; $i++) {
            # Detect an unescaped left parenthesis
            if ($this->filter[$i] === FilterInterface::PAREN_LEFT) {
                # Is the parenthesis followed by an (ie. |, &, !) operator? If so it can contain children ...
                if (isset($this->filter[$i + 1]) && in_array($this->filter[$i + 1], FilterInterface::OPERATORS)) {
                    $child = $child === null ? 0 : $child + 1;
                    $this->containers[$child] = ['startAt' => $i, 'endAt' => null];

                    $i += 2;
                    # Container inside the container ...
                    if ($this->isAtFilterContainer($i)) {
                        $i--;
                    # Comparison filter inside the container...
                    } elseif (isset($this->filter[$i]) && $this->filter[$i] === FilterInterface::PAREN_LEFT) {
                        $i = $this->nextClosingParenthesis($i);
                    # An empty container is not allowed...
                    } elseif (isset($this->filter[$i]) && $this->filter[$i] === FilterInterface::PAREN_RIGHT) {
                        throw new FilterParseException(sprintf(
                            'The filter container near position %s is empty.',
                            $i
                        ));
                    # Any other conditions possible? This shouldn't happen unless the filter is malformed..
                    } else {
                        throw new FilterParseException(sprintf(
                            'Unexpected value after "%s" at position %s: %s',
                            $this->filter[$i - 1] ?? '',
                            $i + 1,
                            $this->filter[$i + 1] ?? ''
                        ));
                    }
                # If there is no operator this is a standard comparison filter, just find the next closing parenthesis
                } else {
                    $i = $this->nextClosingParenthesis($i + 1);
                }
            # We have reached a closing parenthesis of a container, work backwards from those defined to set the ending.
            } elseif ($this->filter[$i] === FilterInterface::PAREN_RIGHT) {
                $matchFound = false;
                foreach (array_reverse($this->containers, true) as $ci => $info) {
                    if ($info['endAt'] === null) {
                        $this->containers[$ci]['endAt'] = $i + 1;
                        $matchFound = true;
                        break;
                    }
                }
                if (!$matchFound) {
                    throw new FilterParseException(sprintf(
                        'The closing ")" at position %s has no matching parenthesis',
                        $i
                    ));
                }
            }
        }

        foreach ($this->containers as $info) {
            if ($info['endAt'] === null) {
                throw new FilterParseException('The filter has an unmatched "(".');
            }
        }
    }

    /**
     * @param int $startAt
     * @return int
     * @throws FilterParseException
     */
    protected function nextClosingParenthesis(int $startAt)
    {
        for ($i = $startAt; $i < $this->length; $i++) {
            # Look for the char, but ignore it if it is escaped
            if ($this->filter[$i] === FilterInterface::PAREN_RIGHT) {
                return $i;
            }
        }

        throw new FilterParseException(sprintf(
            'Expected a matching ")" after position %s, but none was found',
            $startAt
        ));
    }

    /**
     * @param string $value
     * @return string
     */
    protected function unescapeValue(string $value)
    {
        return preg_replace_callback('/\\\\([0-9A-Fa-f]{2})/', function ($matches) {
            return hex2bin($matches[1]);
        }, $value);
    }
}
