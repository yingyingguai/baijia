<?php
$this->registerCssFile('@web/datatables/media/css/dataTables.jqueryui.css');

$this->registerJsFile('@web/datatables/media/js/jquery.dataTables.min.js',[
    'depends'=>\yii\web\JqueryAsset::className()
]);
echo <<<HTML
<table id="example" class="table table-striped table-bordered" >
       <thead>
           <tr>
               <th>Code</th>
               <th>Name</th>
               <th>Price</th>
           </tr>
       </thead>
       <tbody>
           <tr>
               <td>001</td><td>name1</td><td>2323</td>
           </tr>
           <tr>
               <td>002</td><td>name2</td><td>4612</td>
           </tr>
           <tr>
               <td>003</td><td>name3</td><td>4612</td>
           </tr>
           <tr>
               <td>004</td><td>name4</td><td>4612</td>
           </tr>
           <tr>
               <td>005</td><td>name5</td><td>4612</td>
           </tr>
           <tr>
               <td>006</td><td>name6</td><td>4612</td>
           </tr>
           <tr>
               <td>007</td><td>name7</td><td>4612</td>
           </tr>
           <tr>
               <td>008</td><td>name8</td><td>4612</td>
           </tr>
           <tr>
               <td>009</td><td>name9</td><td>4612</td>
           </tr>
           <tr>
               <td>010</td><td>name10</td><td>4612</td>
           </tr>
           <tr>
               <td>011</td><td>name11</td><td>4612</td>
           </tr>
       </tbody>
</table>
HTML;
$js = <<<JS
  $(function () {
        $('#example').DataTable({
            columns:[
                {data:"firstname"},
                {data:"lastname"},
                {data:"phone"}
            ]
        });
    });
JS;
$this->registerJs($js);

