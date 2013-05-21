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

$Session = $_GET['session'];
if(empty($Session)) {
    $Session = $_POST['session'];
}
$Username = $_GET['user'];
if(empty($Username)) {
    $Username = $_POST['user'];
}

echo "<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"no\"?>\n";
echo "<?xml-stylesheet type=\"text/xsl\" href=\"style/style.php?session=" . $Session . "&amp;user=" . $Username . "\" ?>\n";

require_once("classes/CPage.php");
require_once("classes/CSystem.php");

$System = new CSystem($_GET, $_POST);
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
?> </content> <text class="small">Copyright &#169; 2006,2013 by
Christian Lins. The source of this system is released under AGPL.</text>
</page>
<?php
$System->Dispose();
$Page->Dispose();
?>
