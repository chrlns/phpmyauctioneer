<?php
/*
 *   phpMyAuctioneer
 *   Copyright (C) 2006,2013  by Christian Lins <christian@lins.me>
 *
 *   This program is free software: you can redistribute it and/or modify
 *   it under the terms of the GNU Affero General Public License as
 *   published by the Free Software Foundation, either version 3 of the
 *   License, or (at your option) any later version.
 *
 *   This program is distributed in the hope that it will be useful,
 *   but WITHOUT ANY WARRANTY; without even the implied warranty of
 *   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *   GNU Affero General Public License for more details.
 *
 *   You should have received a copy of the GNU Affero General Public License
 *   along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

header("Content-Type: text/xml");

$Session = $HTTP_GET_VARS['session'];
if(empty($Session))
    $Session = $HTTP_POST_VARS['session'];

$Username = $HTTP_GET_VARS['user'];
if(empty($Username))
    $Username = $HTTP_POST_VARS['user'];

echo "<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"no\"?>\n";

if(substr_count(getenv("HTTP_USER_AGENT"), "IE") > 0 || substr_count(getenv("HTTP_USER_AGENT"), "Internet Explorer") > 0)
    echo "<?xml-stylesheet type=\"text/xsl\" href=\"style/style.php?session=" . $Session . "&amp;user=" . $Username . "\" ?>\n";
else // Nur das & vs. &amp; macht den Unterschied
    echo "<?xml-stylesheet type=\"text/xsl\" href=\"style/style.php?session=" . $Session . "&user=" . $Username . "\" ?>\n";

require_once("classes/CPage.php");
require_once("classes/CSystem.php");

$System = new CSystem($HTTP_GET_VARS, $HTTP_POST_VARS);
$Page   = new CPage($System);
?>
<page> <logo url="images/logo.png" />
<menu>
	<?php
	$Page->Menu->GetEntries();
	?>
</menu>

<content> <?php
// Inhalt der Seite einlesen
$Page->GetContent();
?> </content> <text class="small">Copyright &amp;copy; 2006,2013 by
Christian Lins. The source of this system is released under AGPL.</text>
</page>
<?php
$System->Dispose();
$Page->Dispose();
?>
