<?php
/**
 * Created by PhpStorm.
 * User: Misha
 * Date: 8/15/2019
 * Time: 4:09 PM
 */

class Tools
{
    function date_to_array($date_string)
    {
        $date_string = explode(" ", $date_string);
        $ymd = $date_string[0];
        $ymd = explode("-", $ymd);
        $his = $date_string[1];
        $his = explode( ":", $his);

        $date_string = array(
            "year" => $ymd[0],
            "month" => $ymd[1],
            "day" => $ymd[2],
            "hour" => $his[0],
            "minute" => $his[1],
            "second" => $his[2]
        );

        return $date_string;
    }

    function count_delta_time($latter, $earlier)
    {
        if ($latter["year"] > $earlier["year"])
        {
            $delta = $latter["year"] - $earlier["year"];
            return "$delta years ago";
        }
        else if ($latter["month"] > $earlier["month"])
        {
            $delta = $latter["month"] - $earlier["month"];
            return "$delta months ago";
        }
        else if ($latter["day"] > $earlier["day"])
        {
            if(($delta = $latter["day"] - $earlier["day"]) == 1)
            {
                return "Yesterday";
            }
            else
            {
                return "$delta days ago";
            }
        }
        else if ($latter["hour"] > $earlier["hour"])
        {
            $delta = $latter["hour"] - $earlier["hour"];
            return "$delta hours ago";
        }
        else if ($latter["minute"] > $earlier["minute"])
        {
            $delta = $latter["minute"] - $earlier["minute"];
            return "$delta minutes ago";
        }
        else if ($latter["second"] > $earlier["second"])
        {
            $delta = $latter["second"] - $earlier["second"];
            return "$delta seconds ago";
        }
        else
        {
            return "Just now";
        }
    }
}