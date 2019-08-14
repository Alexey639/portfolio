<form METHOD="post">
    <table class="table"><?php

        $rules = $this->model->rules();
        $errors  = $this->model->error;
        foreach ($this->schema as $index => $field) {
            $hasError =  isset($errors[$field['name']]);
            $type = in_array($field['name'], $rules['hidden']) ? 'hidden' : 'text';

            echo '<tr '.($type=='hidden'?'style="display:none"':'').''.($hasError?'class=error"':'').'>
    <td>' . $field['name'] . '</td>' .
                '<td><input class="form-control '.($hasError?' is-invalid"':'').'" type="' . $type . '" name="' . $field['name'] . '" value="' . (isset($this->messages) && $this->messages[0] && isset($this->messages[0][$field['name']]) ? $this->messages[0][$field['name']] : '') . '">' .($hasError?'<div class="invalid-feedback">
        '.$errors[$field['name']]['message'].'
      </div>':''). '</td>' .
                '</tr>';
        }

        ?>
        <tr>
            <td><input type="submit" value="сохранить" class="btn btn-success"></td>
        </tr>
    </table>
</form>