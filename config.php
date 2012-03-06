<?php

class Config extends Core
{

    function __construct()
    {
        Core :: __construct();
        $this->LogAdd('service', 'Default config read. Core init.');
    }

    protected function SetDefaultCfg($Config)
    {
        $this->Config = array_merge($this->Config, $Config);
    }

    protected function SetVariable($Value)
    {
        $this->Test = $Value;
    }

    protected function get_options()
    {
        $options = $this->db->s('option_name', 'option_value', 'type', 'plugin')->f('options')->w('autoload = 1')->e();

        for ($i = 0; $i <= sizeof($options) - 1; $i++)
        {
            if ($options[$i]['type'] == "JSON")
            {
                $this->Config[$options[$i]['option_name']] = json_decode($options[$i]['option_value']);
                settype($this->Config[$options[$i]['option_name']], 'array');
            }
            else
            {
                $this->Config[$options[$i]['option_name']] = $options[$i]['option_value'];
            }
        }

        $this->LogAdd('variables', 'Options loaded.');
    }

    protected function get_option($name)
    {
        $option = $this->db->s('option_value')->f('options')->w('option_name = ?')->e($name);

        $this->Config[$option['option_name']] = $option['option_value'];
        $this->LogAdd('variables', 'Option "'.$name.'" loaded.');
    }

    protected function add_option($name, $value, $load)
    {
        if (!$this->db->s('id')->f('options')->w('option_name = ?')->e($name))
        {
            $option = $this->insert('options', '`id`, `option_name`, `option_value`, `autoload`', 'NULL, "'.mysql_real_escape_string($name).'", "'.mysql_real_escape_string($value).'", "'.mysql_real_escape_string($load).'"');
            $this->LogAdd('variables', 'Option "'.$name.'" added.');
        }
        else
        {
            $this->LogAdd('variables', 'Option "'.$name.'" NOT added.');
        }
    }

    protected function set_option($name, $value, $load)
    {
        $id = $this->db->s('id')->f('options')->w('option_name = ?')->e($name);

        if ($id == 0)
        {
            $this->add_option($name, $value, $load);
        }
        else
        {
            if ($name != "")
            {
                $set .= '`option_name` = "'.$name.'", ';
            }
            if ($value != "")
            {
                $set .= '`option_value` = "'.$value.'", ';
            }
            $set .= '`autoload` = "'.$load.'"';
            $this->update('options', $set, '`id` = "'.$id.'"');
            $this->LogAdd('variables', 'Option "'.$name.'" found and set.');
        }
        $this->Config[$name] = $value;
    }

    protected function unset_option($name)
    {
        $this->delete('options', '`option_name` = "'.$name.'"');
        unset($this->Config[$name]);
    }
}

?>