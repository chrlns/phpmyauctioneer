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

class CPageSell
{
    var $Database;
    var $System;

    function CPageSell($system)
    {
        $this->Database = new CDatabase();
        $this->System = $system;
    }

    function Dispose()
    {
        $this->Database->Dispose();
    }

    function GetContent()
    {
        $this->Database->Query("SELECT * FROM phpmyauctioneer_page WHERE name = 'sell'");

        $result = $this->Database->GetResultArray();
        $code = $result['code'];

        $this->Database->Query("SELECT * FROM phpmyauctioneer_category ORDER BY caption ASC");

        $categories = "<option>Bitte wählen</option>";
        while($q = $this->Database->GetResultArray())
            $categories .= "<option value=\"" . $q['id'] . "\">" . $q['caption'] . "</option>\n";

        $categories = "<select name=\"auction_category\" text=\"Kategorie\">" . $categories . "</select>";

        $code = str_replace("<% FORM_CATEGORIES %>", $categories, $code);

        echo $code;
    }
}

?>