<?php
    $a = array(
        "key1" => array(
            "key2" => array("value1", "value2", "value3")
        ),
        "key1_new" => array(
            "key2" => array("value1_new", "value2_new", "value3_new")
        )
    );

    $a = "question_radio8913";
    if (strpos($a, "question_radio") !== false)
    {
        echo "Includes";
    }
    else
    {
        echo "Missing";
    }

