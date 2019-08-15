<table class="table"><?php

    $message = $this->messages[0];
    $model = $this->model;
    $rules = $model->rules();
    foreach ($rules['all'] as $index => $f) {
        echo '<tr>
    <td>' . $model->label($f[0], $message[$f[0]]) . '</td>' .
            '<td>' . $model->format($f[0], $message[$f[0]]) . '</td>' .

            '</tr>';
    }
    echo '<tr>' . '<td><a class="btn btn-primary" href="/message/edit/id/' . $message['id'] . '">править</a></td>' .
        '<td><a  class="btn btn-danger"  href="/message/delete/id/' . $message['id'] . '" class="text-danger">удалить</a></td>' . '</tr>';

    ?>
</table>
<a href="/message/create" class="btn btn-success">добавить</a>
<a href="/message/index" class="btn btn-success">список</a>