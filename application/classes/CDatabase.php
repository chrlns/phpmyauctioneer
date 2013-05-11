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

// Diese Klasse stellt Funktionen zur Anbindung an die MySQL-Datenbank bereit
class CDatabase
{
    // Globale Verbindungsparameter
    var $Host     = "localhost";
    var $Database = "netvadenet43";
    var $User     = "netvadenet43";
    var $Password = "74KS9Jok";
    var $QueryID;
    var $ConnectionID;

    // Konstruktor
    function CDatabase()
    {
        $this->Connect();
    }

    function Dispose()
    {
        mysql_close($this->ConnectionID);
    }

    function Connect()
    {
        $this->ConnectionID = mysql_pconnect($this->Host, $this->User, $this->Password);
        mysql_select_db($this->Database);
    }

    function Query($query)
    {
        $this->QueryID = mysql_query($query);
        	
        return $this->QueryID;
    }

    function GetResultArray()
    {
        return mysql_fetch_array($this->QueryID);
    }

    function GetFirst()
    {
        $result = mysql_fetch_row($this->QueryID);
        return $result[0];
    }
}
?>
