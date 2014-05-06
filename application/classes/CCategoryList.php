<?php
/*
 *   phpMyAuctioneer
 *   Copyright (C) 2006-2014 by Christian Lins <christian@lins.me>
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
 * Represents the category list
 */
class CCategoryList
{
    var $Database;

    function CCategoryList()
    {
        $this->Database = new CDatabase();
    }

    function Dispose()
    {
        $this->Database->Dispose();
    }

    function GetCategories($parent)
    {
        $this->Database->Query("SELECT * FROM phpmyauctioneer_category WHERE parent = $parent ORDER BY caption ASC");

        echo "<table>\n";

        while($q = $this->Database->GetResultArray())
        {
            echo "<row>\n";
            echo "<column class=\"yellow\">";
            echo "<link url=\"category&amp;id=" . $q['id'] . "\">" . $q['caption'] . "</link>";
            echo "</column>";
            echo "</row>\n";
        }
        echo "</table>\n";
    }
}
?>