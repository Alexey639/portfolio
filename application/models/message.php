<?php

class Message
{
    public $error;
    private $clean;

    /**
     * Message constructor.
     */
    public function __construct($db)
    {
        /** @var PDO $db */
        $this->db = $db;
    }

    public function all()
    {
        $result = $this->db->query('SELECT * FROM messages');
        $list = $result->fetchAll();
        return $list;
    }

    public function one($id)
    {
        $result = $this->db->query('SELECT * FROM messages WHERE id=' . intval($id));
        $list = $result->fetchAll();
        return $list;
    }

    public function delete($id)
    {
        $result = $this->db->query('DELETE  FROM messages WHERE id=' . intval($id));
        $list = $result->fetchAll();
        return $list;
    }

    public function describe()
    {
        $result = $this->db->query('PRAGMA table_info(messages)');
        $list = $result->fetchAll();
        return $list;
    }

    public function rules()
    {
        return ['all' => array(
            // format ['name', 'type', 'label',  'default'],
            ['id', 'int', 'ID'],
            ['fname', 'text', 'имя'],
            ['email', 'email', 'Email'],
            ['msage', 'text', 'сообщение'],
            ['datetime', 'text', 'дата  создания', function ($i) {
                return time();
            }],
        ), 'required' => ['msage'],
            'hidden' => ['id']];

    }

    public function load($post, $new = false)
    {
        $rules = $this->rules();

            foreach ($rules['required'] as $index => $item) {
                if ($new && $item[0] == 'id')
                    continue;
                if (!isset($post[$item])) {
                    $this->error [$item] = ['message' => 'не заполнено поле ' . $item];
                    continue;
                }


            }
        foreach ($rules['all'] as $index => $item) {

            //значение по умолчанию
            if (isset($item[3])) {
                $this->clean[$item[0]] = call_user_func_array($item[3], [$post[$item[0]]]);
                continue;
            }
            //ид
            if ($new && $item[0] == 'id')
                continue;
            if (isset($post[$item[0]]))
                switch ($item[1]) {
                    case  'int':
                        $valid = (int)$post[$item[0]];
                        if ($valid > 0)
                            $this->clean[$item[0]] = $valid;
                        else
                            $this->error[$item[0]] = ['message' => 'не правильно заполнено поле ' . $item[0]];
                        break;
                    case  'email':
                        $valid = filter_var($post[$item[0]], FILTER_VALIDATE_EMAIL);
                        if ($valid || empty($post[$item[0]]))
                            $this->clean[$item[0]] = $valid;
                        else
                            $this->error[$item[0]] = ['message' => 'не правильно заполнено поле ' . $item[0]];
                        break;
                    default:
                        $this->clean[$item[0]] = $post[$item[0]];
                }


        }
        return empty($this->error);

    }

    public function save($insert = true)
    {
        $this->clean['id'] = $insert ? $this->clean['id'] : null;
        foreach ($this->clean as $index => $item) {
            $v = (is_null($item) ? 'NULL' : (is_int($item) ? $item : '"' . SQLite3::escapeString($item) . '"'));
            $set[] = $index . ' = ' . $v;
            $values[] = $v;
        }
        if ($insert)
            $sql = 'UPDATE  messages SET ' . implode(',', $set) . ' WHERE id= ' . $this->clean['id'];
        else {
            $sql = 'INSERT INTO messages (' . (implode(',', array_keys($this->clean))) . ') VALUES( ' . implode(',', $values) . ')';
        }
        $result = $this->db->prepare($sql);
        return $result->execute();
    }

    public function format($name, $value, $format = 'd.m.Y H:i:s')
    {
        if (($name == 'datetime') && !empty($value))
            return date($format, (int)$value);
        else return $value;

    }

    public function labels()
    {
        return array_column($this->rules()['all'], 2, 0);

    }

    public function label($name, $value)
    {
        $labels = $this->labels();
        return $labels[$name] ?? $name;


    }

}