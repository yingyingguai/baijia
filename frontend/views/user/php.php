<?php


date("Y-m-d,H:m:s", strtotime("-1 day"));

//


    $sql1 =  "insert into 'user'  ('name','tel','content','date')VALUES 
                ('小王','132465','高中毕业','2007-05-06')";

        $sql2 = "update 'user'set date = date()";


        $sql3= "delete from 'user' where name='张三'";


        $count= "select name from code where score<60  ";