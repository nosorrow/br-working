<?php
/*
 * Валидация
 *
 * Пример:
 * В контролера
 *
 * $validator = new \Libs\Validator
 *
 * $validator->make('checkin', 'пристигане' ,['required', 'min:2', 'max:15'])->
 *             make('checkout', 'заминаване' ,['min:2', 'max:15'])->run();
 *
 * if($validator->hasErrors()){
 *    /load view .... }
 *
 * Във View:
 * echo validation_error('checkin')
 *
 */

namespace Core\Libs;

use Core\Bootstrap\DiContainer;
use Core\Libs\Database\MySqlPDOConnection;

class Validator
{
    /**
     * @var
     */
    public static $instance;
    /**
     * @var array
     */
    private $_rule_data = array();
    /**
     * @var
     */
    public $closure;
    /**
     * @var array
     */
    public $_errors = array();
    /**
     * @var
     */
    public $ownMessages = array();
    /**
     * @var string
     */
    public $_prefix_tag = '<p>';
    /**
     * @var string
     */
    public $_postfix_tag = '</p>';
    /**
     * @var string
     */
    public $_alert_format = '<div class="alert alert-danger" role="alert">%s</div>';
    /**
     * @var
     */
    public $error_string;
    /**
     * @var
     */
    public $input;

    /**
     * @var
     */
    public $message;

    /**
     * Validator constructor.
     */
    private function __construct()
    {
        $dic = new DiContainer();
        $this->input = Request::getInstance();
        $this->message = $dic->get(Message::class);
    }


    /**
     * make
     *
     * @param $field
     * @param $label
     * @param array $rules
     * @return $this
     */
    //todo:: array of validation rules
    public function make($field, $label = null, $rules = null, $customMsg = null)
    {
        /*[
            'email'=>[
                'label'=>'email',
                'rules'=>['required', 'max:10'],
                'message'=>'Custom message Here'
            ]
        ]*/
        if (is_array($field)) {

            foreach ($field as $key => $value) {
                $this->_rule_data[] = [
                    'field' => $key,
                    'label' => $value['label'],
                    'rules' => $value['rules']
                ];

            }

        } else {

            $this->_rule_data[] = [
                'field' => $field,
                'label' => $label,
                'rules' => $rules,
            ];

        }

        if ($customMsg !== null) {
            $this->ownMessages($customMsg);

        }
        return $this;
    }

    public function ownMessages(array $messages)
    {
        // // $custoMsg = ['field.rule'=>'message']
        // return $message[$rule]

        foreach ($messages as $key => $val) {

            $position = strpos($key, '.');
            $field = substr($key, 0, $position);
            $rule = substr($key, ($position + 1));
            $this->ownMessages[$field][$rule] = $val;

        }

        return $this;
    }

    /**
     * run
     *
     * @return bool
     */
    public function run()
    {
        //не искам да валидирам празни данни
        if (empty($this->input->post) &&
            empty($this->input->get) &&
            empty($this->input->put)
        ) {
            return false;
        }

        if (count($this->_rule_data) > 0) {
            foreach ($this->_rule_data as $data) {
                $label = isset($data['label']) ? $data['label'] : $data['field'];
                $dataForValidation = $this->input->input($data['field']);
                foreach ($data['rules'] as $_rules) {

                    if (isClosure($_rules) == false) {

                        list($rule, $arg) = explode(':', $_rules);
                        // Валидация
                        // ako e стойностите за валидиране са в масив
                        if (is_array($dataForValidation)) {
                            foreach ($dataForValidation as $value) {
                                $run[] = $this->$rule($value, $arg);
                            }
                            if (in_array(false, $run)) {
                                $_run = false;
                            }
                        } else {
                            $_run = $this->$rule($this->input->input($data['field']), $arg);
                        }
                        // Ако валидацията не мине - пълни масив с грешки
                        if ($_run === false) {
                            // Ако полето е required
                            if ($rule == 'required') {
                                unset($this->_errors[$data['field']]);
                                $this->_errors[$data['field']][0] = $this->_msg($data['field'], 'required', $label, $arg);

                                break;
                            }
                            $this->_errors[$data['field']][] = $this->_msg($data['field'], $rule, $label, $arg);
                        }

                    } else {
                        $a = call_user_func_array($_rules, [$label, $this->input->input($data['field'])]);
                        if (is_string($a)) {

                            $this->_errors[$data['field']][] = $a;
                        }
                    }

                } //end foreach ( $data['rules'] as $rule => $arg )
            }
        }

        return (bool)!count($this->_errors);
    }

    /**
     * _msg
     *
     * @param $rule
     * @param null $label
     * @param null $arg
     * @return mixed
     */
    private function _msg($field, $rule, $label = null, $arg = null)
    {

        switch ($rule) {
            case 'match':
                $arg = $this->_loopingAndGetName($arg)['label'];
                break;

            case 'different':
                $arg = $this->_loopingAndGetName($arg)['label'];
                break;

            // не искам да показва името на плето а неговата стойност / дата след 2016-05-20 /
            case 'before':
                $_a = $this->_loopingAndGetName($arg);
                $arg = $this->_get_argument($_a);
                break;

            case 'after':
                $_a = $this->_loopingAndGetName($arg);
                $arg = $this->_get_argument($_a);
                break;
        }

        if ($this->ownMessages[$field][$rule]) {

            $message[$rule] = $this->ownMessages[$field][$rule];

        } else {

            $message[$rule] = str_replace(['{label}', '{arg}'], [$label, $arg], $this->message->get('Validations')->line($rule));
        }
        return $message[$rule];
    }

    /**
     * Обхожда масива и връща името на полето
     * @param $arg
     * @return mixed
     */
    private function _loopingAndGetName($arg)
    {
        for ($i = 0; $i < count($this->_rule_data); $i++) {
            if (!in_array($arg, $this->_rule_data[$i])) {
                continue;

            } else {
                $argument = $this->_rule_data[$i];
            }
        }
        return $argument;
    }

    /**
     * @param $a
     * @return mixed
     */
    private function _get_argument($a)
    {
        if (!empty($this->input->input($a['field']))) {

            $arg = $this->input->input($a['field']);

        } elseif (!is_array($a)) {

            $arg = $a;

        } else {

            $arg = $a['label'];
        }

        return $arg;

    }

	/**
	 * true ако има грешки
	 * @param null $field
	 * @return bool
	 */
	public function hasErrors($field = null)
	{
		if (!$field) {
			if(!$this->_errors){
				$this->_errors =[];
			}

			return (bool)count($this->_errors);
		}

		if(!$this->_errors[$field]){
			$this->_errors[$field] =[];
		}
		return (bool)count($this->_errors[$field]);

	}

    /**
     * @param $field
     * @param $msg
     */
    public function set_error($field, $msg)
    {
        $this->_errors[$field][] = $msg;
    }

    /**
     * @param string $field
     * @param string $prefix
     * @param string $postfix
     * @param string $format
     * @return string
     * @throws \Exception
     */
    public function errors($field = '', $prefix = '', $postfix = '', $format = '')
    {

        if (!$prefix) {

            $prefix = $this->_prefix_tag;
        }

        if (!$postfix) {

            $postfix = $this->_postfix_tag;
        }

        if (!$format) {

            $format = $this->_alert_format;
        }

        if (empty($this->_errors)) {
            //не искам да форматира когато няма грешки
            return;
        }

        if ($field === '') {

            foreach ($this->_errors as $key => $values) {

                foreach ($values as $msg) {

                    $this->error_string .= $prefix . $msg . $postfix;
                }
            }
        } else {

            if (array_key_exists($field, $this->_errors)) {

                $msg = current($this->_errors[$field]);

                $this->error_string = $prefix . $msg . $postfix;

            }
        }

        return sprintf($format, $this->error_string);
    }


    /*
     * ------------------------  Validation Rules ---------------------------
     */

    /**
     * Alpha
     *
     * @param    string
     * @return    bool
     */
    public function alpha($str)
    {
        return ctype_alpha($str);
    }

    /**
     * alpha_numeric
     *
     * @param $str
     * @return bool
     */
    public function alpha_numeric($str)
    {
        return ctype_alnum((string)$str);
    }

    /**
     * Alpha-numeric with underscores and dashes
     *
     * @param    string
     * @return    bool
     */
    public function alpha_dash($str)
    {
        return (bool)preg_match('#^[a-z0-9_-]+$#i', $str);
    }

    /**
     * @param $str
     * @param $date
     * @return bool
     */
    public function after($str, $date)
    {
        if ($this->date($date) === false) {

            if (!empty($this->input->input($date))) {

                return (bool)(strtotime($str) > strtotime($this->input->input($date)));

            } else {

                return false;
            }

        } else {

            $date = strtotime($date);

            $str = strtotime($str);

            return (bool)($str > $date);
        }

    }

    /**
     * @param $str
     * @param $date
     * @return bool
     */
    public function before($str, $date)
    {
        if ($this->date($date) === false) {

            if (!empty($this->input->input($date))) {

                return (bool)(strtotime($str) < strtotime($this->input->input($date)));

            } else {

                return false;
            }

        } else {

            $date = strtotime($date);

            $str = strtotime($str);

            return (bool)($str < $date);
        }
    }

    /**
     * valid_date
     *
     * @param $date
     * @return bool
     */
    public function date($date)
    {
        if (false === strtotime($date)) {
            return false;
        } else {
            list($year, $month, $day) = explode('-', $date);

            if (false === checkdate($month, $day, $year)) {
                return false;
            }
        }

        return true;
    }


    /**
     * differs
     *
     * @param $str
     * @param $field
     * @return bool
     */
    public function different($str, $field)
    {
        return $str !== $this->input->input($field) ? true : false;

    }

    /**
     * email
     *
     * @param $str
     * @return bool
     */
    public function email($str)
    {
        /*if (function_exists('idn_to_ascii') && $atpos = strpos($str, '@')) {
             $str = substr($str, 0, ++$atpos) . idn_to_ascii(substr($str, $atpos));
         }*/

        if (function_exists('idn_to_ascii') && preg_match('#\A([^@]+)@(.+)\z#', $str, $matches)) {
            $domain = is_php('5.4')
                ? idn_to_ascii($matches[2], 0, INTL_IDNA_VARIANT_UTS46)
                : idn_to_ascii($matches[2]);
            $str = $matches[1] . '@' . $domain;
        }
        return (bool)filter_var($str, FILTER_VALIDATE_EMAIL);
    }

    /**
     * Exact Length
     *
     * @param    string
     * @param    string
     * @return    bool
     */
    public function exact($str, $val)
    {
        if (!is_numeric($val)) {
            return FALSE;
        }

        return (mb_strlen($str) === (int)$val);
    }

    /**
     * Greater
     *
     * @param    string
     * @param    int
     * @return    bool
     */
    public function greater($str, $val)
    {
        return is_numeric($str) ? ($str > $val) : false;
    }

    /**
     * greater_equal
     *
     * @param    string
     * @param    int
     * @return    bool
     */
    public function greater_equal($str, $val)
    {
        return is_numeric($str) ? ($str >= $val) : false;
    }

    /**
     * Value should be within an array of values
     * ['in:5,6,8']
     *
     * @param    string
     * @param    string
     * @return    bool
     */
    public function in($value, $list)
    {
        return (bool)in_array($value, explode(',', $list), true);
    }

    /**
     * Integer
     *
     * @param    string
     * @return    bool
     */
    public function integer($str)
    {
        return (bool)preg_match('#^[\-+]?[0-9]+$#', $str);
    }

    /**
     * is_numeric
     *
     * @param $val
     * @return bool
     */
    public function is_numeric($val)
    {
        return (bool)is_numeric($val);
    }

    /**
     * less
     *
     * @param $str
     * @param $val
     * @return bool
     */
    public function less($str, $val)
    {
        return is_numeric($str) ? (bool)($str < $val) : false;
    }

    /**
     * less_equal
     *
     * @param $str
     * @param $val
     * @return bool
     */
    public function less_equal($str, $val)
    {
        return is_numeric($str) ? (bool)($str <= $val) : false;
    }

    /**
     * max
     * @param $str
     * @param $max
     * @return bool
     */
    public function max($str, $max)
    {
        return (mb_strlen($str ?? "") <= $max);
    }

    /**
     * min
     *
     * @param $str
     * @param $min
     * @return bool
     */
    public function min($str, $min)
    {
        return (mb_strlen($str ?? "") >= $min);
    }

    /**
     * само букви и интервал
     * @param $str
     * @return bool
     */
    public function name($str)
    {
        return (bool)(preg_match('#^[a-zа-я\s]+$#iu', $str));
    }

    /**
     * url
     *
     * @param $str
     * @return bool
     */
    public function url($str)
    {
        return (filter_var($str, FILTER_VALIDATE_URL) !== false);
    }

    /**
     * @param $str
     * @param $field
     * @return bool
     */
    public function match($str, $field)
    {
        return $str === $this->input->input($field) ? true : false;

    }

    /**
     * regex
     *
     * @param $str
     * @param $regex
     * @return bool
     */
    public function regex($str, $regex)
    {
        return (bool)preg_match($regex, $str);
    }

    /**
     * regex_not
     *
     * Забранява символите в рег. израз
     * 'regex_not:#[{}$@&()=<>]#i'
     *
     * @param $str
     * @param $regex
     * @return bool
     */
    public function regex_not($str, $regex)
    {
        return (bool)!preg_match($regex, $str);
    }

    /**
     * required
     *
     * @param $str
     * @return bool
     */
    public static function required($str)
    {
        return is_array($str) ? (bool)count($str) : (trim($str) !== '');
    }

    /**
     * unique
     *
     * Проверява за уникален запис
     * в База Данни пр. (unique:table.col)
     *
     * @param $str
     * @param $field
     * @return bool
     */
    public function unique($str, $field)
    {

        list($table, $col) = explode('.', $field);

        try {
            $db = MySqlPDOConnection::getInstance()->getConnection();

            $sql = "SELECT COUNT(*) FROM {$table} WHERE {$col}= :str";

            $sth = $db->prepare($sql);
            $sth->bindParam(':str', $str, \PDO::PARAM_STR);
            $sth->execute();

            $result = $sth->fetch(\PDO::FETCH_NUM);

            if ($result[0] != 0) {

                return false;
            }

        } catch (\PDOException $e) {

            echo "Some Error with DB: " . $e->getMessage();
        }

    }

    /**
     *
     * Проверява за уникален запис
     * в База Данни с изключение
     * пр. (unique_except:table.col.exc-col.exc-col-id)
     *
     * @param $str
     * @param $field
     * @return bool
     */
    public function unique_except($str, $field)
    {
        list($table, $col, $exc_col, $exc_col_val) = explode('.', $field);

        try {
            $db = MySqlPDOConnection::getInstance()->getConnection();

            $sql = "SELECT COUNT(*) FROM {$table} WHERE {$col}= :str AND {$exc_col} != ($exc_col_val)";
            $sth = $db->prepare($sql);
            $sth->bindParam(':str', $str, \PDO::PARAM_STR);
            $sth->execute();

            $result = $sth->fetch(\PDO::FETCH_NUM);

            if ($result[0] != 0) {

                return false;
            }

        } catch (\PDOException $e) {

            echo "Some Error with DB: " . $e->getMessage();
        }

    }


    /**
     * __call
     *
     * @param $a
     * @param $b
     * @throws \Exception
     */
    public function __call($a, $b)
    {
        throw new \Exception('Missing Validation rule: ' . $a, 500);
    }

    /**
     * singleton
     *
     * @return Validator
     */
    public static function getInstance()
    {
        if (self::$instance == null) {

            self::$instance = new self();
        }
        return self::$instance;
    }

}
