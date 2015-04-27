<?php
/*
 *   phpMyAuctioneer
 *   Copyright (C) 2006-2015  by Christian Lins <christian@lins.me>
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

require_once("CDatabase.php");
require_once("CSystem.php");

class CPageStatic
{
    var $Database;
    var $System;

    function CPageStatic($system)
    {
        $this->Database = new CDatabase();
        $this->System = $system;
    }

    function GetContent()
    {
        $file = file("content/" . $this->System->Content . ".xml");
        if(empty($file)) {
            echo "<text>Die gewünschte Seite " . $this->System->Content . " konnte leider nicht gefunden werden!</text>";
        } else {
            if($this->System->Authentification < $result['authentification'])
            {
                $file = file("content/auth_requ.xml");
            }
            	
            foreach($file as $line) {
                echo $line;
            }
        }
    }

    function Dispose()
    {
        $this->Database->Dispose();
    }
}

?>