<?php
/*
** Zabbix
** Copyright (C) 2001-2020 Zabbix SIA
**
** This program is free software; you can redistribute it and/or modify
** it under the terms of the GNU General Public License as published by
** the Free Software Foundation; either version 2 of the License, or
** (at your option) any later version.
**
** This program is distributed in the hope that it will be useful,
** but WITHOUT ANY WARRANTY; without even the implied warranty of
** MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
** GNU General Public License for more details.
**
** You should have received a copy of the GNU General Public License
** along with this program; if not, write to the Free Software
** Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
**/


/**
 * @var CView $this
 */
$table = (new CTableInfo())
	->makeVerticalRotation()
	->setHeadingColumn(0);

$headings[] = _('Hosts');
foreach ($data['items_by_name'] as $name => $hostid_to_itemid) {
	$headings[] = (new CColHeader($name))
		->addClass('vertical_rotation')
		->setTitle($name);
}
if ($data['hidden_cnt']) {
	$headings[] = (new CColHeader('...'))
		->addClass('vertical_rotation')
		->setTitle(_n('%s item hidden', '%s items hidden', $data['hidden_cnt'], $data['hidden_cnt']));
}

$table->setHeader($headings);

foreach ($data['db_hosts'] as $hostid => $host) {
	$name = (new CLinkAction($host['name']))->setMenuPopup(CMenuPopupHelper::getHost($hostid));
	$row = [(new CColHeader($name))->addClass(ZBX_STYLE_NOWRAP)];

	foreach ($data['items_by_name'] as $name => $hostid_to_itemid) {
		if (!array_key_exists($host['hostid'], $hostid_to_itemid)) {
			$row[] = new CCol();
		}
		else {
			$itemid = $hostid_to_itemid[$host['hostid']];
			$item = $data['visible_items'][$itemid];
			$row[] = getItemDataOverviewCell($item, $item['trigger']);
		}
	}

	if ($data['hidden_cnt']) {
		$row[] = new CCol();
	}

	$table->addRow($row);
}

echo $table;
