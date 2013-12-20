<?php
/**
 * OpenEyes
 *
 * (C) Moorfields Eye Hospital NHS Foundation Trust, 2008-2011
 * (C) OpenEyes Foundation, 2011-2012
 * This file is part of OpenEyes.
 * OpenEyes is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.
 * OpenEyes is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License along with OpenEyes in a file titled COPYING. If not, see <http://www.gnu.org/licenses/>.
 *
 * @package OpenEyes
 * @link http://www.openeyes.org.uk
 * @author OpenEyes <info@openeyes.org.uk>
 * @copyright Copyright (c) 2008-2011, Moorfields Eye Hospital NHS Foundation Trust
 * @copyright Copyright (c) 2011-2012, OpenEyes Foundation
 * @license http://www.gnu.org/licenses/gpl-3.0.html The GNU General Public License V3.0
 */
?>

<h1 class="badge admin">Reports</h1>

<div class="box content admin-content">
	<div class="row">
		<aside class="large-3 column sidebar admin">
			<?php $this->renderPartial('reports_sidebar')?>
		</aside>
		<div class="large-9 column">
			<?php $this->renderPartial('_report')?>
		</div>
	</div>
	<div class="row">
		<div class="colorband category_treatment"<?php if(!$this->title){ ?> style="display: none;"<?php } ?>></div>
		<div id="display_actions_footer" class="display_actions footer"<?php if (!$this->title){?> style="display: none;"<?php }?>></div>
	</div>
</div>
