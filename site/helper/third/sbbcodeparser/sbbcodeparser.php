<?php
/**
 * @package LimeTicket Support System
 * @author LimeSurvey GmbH / Freestyle Joomla
 * @copyright (C) 2019 LimeSurvey GmbH  /  Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

/**
 * SSBBCodeParser
 * 
 * BBCode parser classes.
 *
 * @copyright (C) 2011 Sam Clarke (samclarke.com)
 * @license http://www.gnu.org/licenses/lgpl.html LGPL version 3 or higher
 *
 * @TODO: Add inline/block to tags and forbid adding block elements to inline ones.
 * Maybe split the inline elemnt and put the block element inbetween?
 * @TODO: Have options for limiting nesting of tags
 * @TODO: Have whitespace trimming options for tags
 */

/*
 * This library is free software: you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation, either
 * version 3 of the License, or (at your option) any later version.
 * 
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 * 
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library.  If not, see <http://www.gnu.org/licenses/>.
 */

include('classes/exception.php');
require('classes/exception/missingendtag.php');
require('classes/exception/invalidnesting.php');

require('classes/node.php');
require('classes/node/text.php');
require('classes/node/container.php');
require('classes/node/container/tag.php');
require('classes/node/container/document.php');

require('classes/bbcode.php');
