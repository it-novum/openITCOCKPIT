<?php
// Copyright (C) <2015>  <it-novum GmbH>
//
// This file is dual licensed
//
// 1.
//	This program is free software: you can redistribute it and/or modify
//	it under the terms of the GNU General Public License as published by
//	the Free Software Foundation, version 3 of the License.
//
//	This program is distributed in the hope that it will be useful,
//	but WITHOUT ANY WARRANTY; without even the implied warranty of
//	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//	GNU General Public License for more details.
//
//	You should have received a copy of the GNU General Public License
//	along with this program.  If not, see <http://www.gnu.org/licenses/>.
//

// 2.
//	If you purchased an openITCOCKPIT Enterprise Edition you can use this file
//	under the terms of the openITCOCKPIT Enterprise Edition license agreement.
//	License agreement and license key will be shipped with the order
//	confirmation.

class NestHelper extends AppHelper
{
    public $helpers = ['Form' => [
        'className' => 'AppForm']
    ];

    /**
     * Creats an HTML and JavaScript nestlist
     *
     * ### Options
     *
     * - `id`   of the main <ol>
     * - `wrapper_class` Of the main <div>
     * - `ol_class` Is the default background color
     * - `child_li_class`    Class of container children <li>
     * - `handle_class` container <div> of children
     *
     * @param array $nest The Container with all children as Hash::nest() array
     * @param array $options Array of options and HTML attributes.
     * @return string An `<div />` element.
     * @author Daniel Ziegler <daniel.ziegler@it-novum.com>
     * @since 3.0
     *
     */
    public function create($nest = array(), $options = array())
    {
        $_options = array(
            'id' => 'nestable',
            'wrapper_class' => 'dd',
            'ol_class' => 'dd-list',
            'child_li_class' => 'dd-item',
            'handle_class' => 'dd-handle',

        );
        $options = Hash::merge($_options, $options);

        $html = '<div class="' . $options['wrapper_class'] . ' dd-nodrag">';
        $html .= '<ol class="' . $options['ol_class'] . '" id="' . $options['id'] . '">';
        foreach ($nest as $parent) {
            $i = 0;
            array_walk_recursive($parent, function ($val, $key) use (&$i) {
                //debug($key);
                if ($key == 'id') {
                    $i++;
                }
            });
            $html .= '<li class="' . $options['child_li_class'] . '" data-id="' . $parent['Container']['id'] . '">';
            $html .= '<div class="' . $options['handle_class'] . '" parent-id="' . $parent['Container']['parent_id'] . '" containertype-id="' . $parent['Container']['containertype_id'] . '" >' . $this->icon($parent['Container']['containertype_id']) . $parent['Container']['name'];
            if ($parent['Container']['containertype_id'] == CT_NODE) {
                $html .= '<button class="btn btn-xs btn-default pull-right" title="delete" data-action="remove">
                            <i class="fa fa-trash txt-color-red" value="' . $parent['Container']['id'] . '"></i>
                          </button>';
            }
            $html .= '<span class="badge bg-color-blue txt-color-white pull-right">' . ($i - 1) . '</span>';
            $html .= '</div>';
            //Now we need to check for childrens and append them
            if (!empty($parent['children'])) {
                $html .= $this->fetchChildren($parent['children'], $options);
            }
            $html .= '</li>';
        }
        $html .= '</ol>';
        $html .= '</div>';
        return $html;
    }


    /**
     * fatches recrusiv children and return them as html nest
     * used by $this->create()
     *
     *
     * @param array $children as Hash::nest() array
     * @param array $options Array of options and HTML attributes.
     * @return string An `<ol />` element.
     * @author Daniel Ziegler <daniel.ziegler@it-novum.com>
     * @since 3.0
     *
     */
    private function fetchChildren($children = array(), $options = array())
    {
        $html = '<ol class="' . $options['ol_class'] . '">';
        foreach ($children as $child) {
            $i = 0;
            array_walk_recursive($child, function ($val, $key) use (&$i) {
                //debug($key);
                if ($key == 'id') {
                    $i++;
                }
            });
            $html .= '<li class="' . $options['child_li_class'] . '" data-id="' . $child['Container']['id'] . '">';
            $html .= '<div class="' . $options['handle_class'] . '" parent-id="' . $child['Container']['parent_id'] . '" containertype-id="' . $child['Container']['containertype_id'] . '" >' . $this->icon($child['Container']['containertype_id']) . $child['Container']['name'];
            if ($child['Container']['containertype_id'] == CT_NODE) {
                $html .= '<a href="#" data-toggle="modal" data-target="#delete_location_' . $child['Container']['id'] . '" class="txt-color-red padding-left-10 font-xs"><i class="fa fa-trash-o"></i>' . __('Delete') . '</a>';
            }
            if (sizeof($child['children']) > 0) {
                $html .= '<span class="badge bg-color-blueLight txt-color-white pull-right">' . ($i - 1) . '</span>';
            } else {
                $html .= '<i class="note pull-right">' . __('empty') . '</i>';
            }
            $html .= '</div>';
            //Now we need to check of the children (of the parent) has some childrens
            if (!empty($child['children'])) {
                $html .= $this->fetchChildren($child['children'], $options);
            }

            $html .= '</li>';
        }
        $html .= '</ol>';
        foreach ($children as $child) {
            if ($child['Container']['containertype_id'] == CT_NODE) {
                $html .= '<div class="modal fade" id="delete_location_' . $child['Container']['id'] . '" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                                            &times;
                                        </button>
                                        <h4 class="modal-title" id="myModalLabel">' . __('Do you really want to delete this node and all related objects') . '?</h4>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row">
                                            Make sure that you really do not use this node</br>
                                            and any related objects like: </br>
                                            <ul>
                                                <li>Hosts</li>
                                                <li>HostGroups</li>
                                                <li>Users</li>
                                                <li>Satellites</li>
                                                <li>Locations</li>
                                                <li>Services</li>
                                                <li>ServiceGroups</li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        ' . $this->Form->postLink(__('Delete'), ['controller' => 'containers', 'action' => 'delete', $child['Container']['id']], ['class' => 'btn btn-danger', 'data-dismiss' => 'modal']) . '
                                        <button type="button" class="btn btn-default" data-dismiss="modal">
                                            ' . __('Cancel') . '
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>';
            }
        }
        return $html;
    }


    /**
     * Return a icon for the different container type ids
     *
     * @param integer $containertype_id
     * @return string An `<i />` element.
     * @author Daniel Ziegler <daniel.ziegler@it-novum.com>
     * @since 3.0
     *
     */
    private function icon($containertype_id = 1)
    {
        switch ($containertype_id) {
            case 1:
                //Root
                return '<i class="fa fa-globe"></i> ';

            case 2:
                //Tenant
                return '<i class="fa fa-home"></i> ';

            case 3:
                //Location
                return '<i class="fa fa-location-arrow"></i> ';

            case 4:
                //Device group
                return '<i class="fa fa-cloud"></i> ';

            case 5:
                //Node
                return '<i class="fa fa-link"></i> ';

            case 6:
                //Contact group
                return '<i class="fa fa-users"></i> ';

            case 7:
                //Host group
                return '<i class="fa fa-sitemap"></i> ';

            case 8:
                //Service group
                return '<i class="fa fa-cogs"></i> ';

            case 9:
                //Service template group
                return '<i class="fa fa-pencil-square-o"></i> ';
        }
    }
}