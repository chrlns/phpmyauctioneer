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

require_once("CDatabase.php");

/**
 * Reads navigation elements from the database
 */
class CMenu
{
    var $Database;
    var $System;

    function CMenu($system)
    {
        $this->Database = new CDatabase();
        $this->System = $system;
    }

    function GetEntries()
    {
        $this->Database->Query("SELECT * FROM phpmyauctioneer_menu WHERE min_auth_level <= " . $this->System->Authentification . " ORDER BY position ASC");

        while($q = $this->Database->GetResultArray())
        {
            echo "\n<menuentry class=\"menu\" url=\"" . $q['name'] . "\" text=\"" . $q['title'] . "\"/>";
        }
    }
}
?>
