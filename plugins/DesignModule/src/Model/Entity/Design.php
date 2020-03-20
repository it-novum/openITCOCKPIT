<?php
declare(strict_types=1);

namespace DesignModule\Model\Entity;

use Cake\ORM\Entity;

/**
 * Design Entity
 *
 * @property int $id
 * @property string $page_header
 * @property string $header-btn
 * @property string $page-sidebar
 * @property string $nav-title
 * @property string $nav-menu
 * @property string $nav-menu-hover
 * @property string $nav-tabs
 * @property string $nav-tabs-hover
 * @property string $page-content
 * @property string $page-content-wrapper
 * @property string $panel-hdr
 * @property string $panel
 * @property string $breadcrumb-links
 * @property int $logo-in-header
 */
class Design extends Entity
{
    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        'page_header' => true,
        'header-btn' => true,
        'page-sidebar' => true,
        'nav-title' => true,
        'nav-menu' => true,
        'nav-menu-hover' => true,
        'nav-tabs' => true,
        'nav-tabs-hover' => true,
        'page-content' => true,
        'page-content-wrapper' => true,
        'panel-hdr' => true,
        'panel' => true,
        'breadcrumb-links' => true,
        'logo-in-header' => true,
    ];
}
