<?php
    require_once("db_connect.php");
    require_once ("Tools.php");

    $data = array();

    $user_search = $_POST["search_string"];
    $where_list = array();
    $query_user_search = "SELECT * FROM survey";
    $clean_search = str_replace(',', ' ', $user_search);
    $search_words = explode(' ', $clean_search);

    $final_search_words = array();

    if (count($search_words) > 0)
    {
        foreach($search_words as $word)
        {
            if(!empty($word))
            {
                $final_search_words[] = $word;
            }
        }
    }

    foreach ($final_search_words as $word)
    {
        $where_list[] = " name LIKE '%$word%'";
    }
    $where_clause = implode(' OR ', $where_list);
    if (!empty($where_clause))
    {
        $query_user_search .=" WHERE $where_clause";
    }
    $res_query = mysqli_query($link, $query_user_search);

    while ($row = mysqli_fetch_array($res_query))
    {
        $id = $row['id'];
        $name = $row['name'];
        $desc = $row['description'];
        $date_created = $row['date_created'];

        $desc_final = "";
        if (strlen($desc) > 100)
        {
            $desc_final = substr($desc, 0, 60) . "...";
        }
        else
        {
            $desc_final = $desc;
        }
        $desc_final = trim($desc_final);

        $now = date("y-m-d h:i:s", time());
        $now = Tools::date_to_array($now);
        $date_created = Tools::date_to_array($date_created);
        $delta_time = Tools::count_delta_time($now, $date_created);

        $data[] = array(
          "id" => $id,
          "name" => $name,
          "desc" => $desc_final,
          "delta_time" => $delta_time
        );
    }

    echo json_encode($data);