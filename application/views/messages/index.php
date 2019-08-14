<table class="table"><?php


foreach ($this->messages as $index => $message) {
    echo  '<tr>
    <td>'.$message['fname'].'</td>'.
    '<td>'.$message['email'].'</td>'.
    '<td>'.$message['msage'].'</td>'.
    '<td><a href="/message/edit/id/'.$message['id'].'">править</a></td>'.
        '</tr>'
    ;
}

?>