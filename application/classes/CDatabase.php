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

include("db.inc");

class CDatabase
{
    var $QueryID;
    var $ConnectionID;

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
        //$pdo = new PDO('mysql:host=localhost;dbname=testdb;charset=utf8', 'username', 'password');
        $this->ConnectionID = mysql_pconnect(DATABASE_HOST, DATABASE_USER, DATABASE_PASSWORD);
        if(!$this->ConnectionID || !mysql_select_db(DATABASE_NAME, $this->ConnectionID)) {
            echo "<text>CDatabase::Connect: " . mysql_error() . "</text>";
            echo "<text>" . DATABASE_NAME . "</text>";
        }
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
