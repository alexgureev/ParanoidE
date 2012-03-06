<?php

class Plugins extends Config
{

    function __construct()
    {
        Config :: __construct();
        $this->LogAdd('service', 'Config init.');
        $this->AddStage(0, array('Class' => 'Init', 'Method' => 'Cache', 'Params' => NULL));
        $this->AddStage(2, array('Class' => 'Init', 'Method' => 'IncludePlugins', 'Params' => NULL));
    }

    protected function Cache()
    {
        if (CACHE)
        {
            $this->cache = new Cache();
            ($this->cache->s) ? $this->LogAdd('CACHE', 'CACHE: Initiazed.') : false;
        }
    }

    protected function PluginsList()
    {
        //BUG: $this->db->s()->f('plugins')->w()->o('`priority` ASC')->e();
        return $this->db->s()->f('plugins')->w()->o('priority DESC')->e();
    }

    protected function & CreateObjArray($type, $args = array())
    {
        $reflection = new ReflectionClass($type);
        $output = call_user_func_array(array(&$reflection, 'newInstance'), $args);
        return $output;
    }

    protected function IncludePlugins()
    {
        $list = $this->PluginsList();
        for ($i = 0; $i <= sizeof($list) - 1; $i++)
        {
            if ($list[$i]['status'] == 1)
            {
                if (is_file(PLUGIN_PATH.$list[$i]['path']))
                {
                    $this->LogAdd('include', 'Included: '.PLUGIN_PATH.$list[$i]['path']);

                    include_once (PLUGIN_PATH.$list[$i]['path']);

                    if ($list[$i]['variable'])
                    {
                        $this->{$list[$i]['variable']} = $this->CreateObjArray($list[$i]['class'], array());
                        $this->{$list[$i]['variable']}->Initialize();
                    }
                    else
                    {
                        $this->Plugins[$list[$i]['class']] = $this->CreateObjArray($list[$i]['class'], array());
                        $this->Plugins[$list[$i]['class']]->Initialize();
                    }



                    /*
                     * array get_class_methods (string имя_класса)
                     * array get_class_vars (string имя_класса)
                     * array get_object_vars (object имя_обьекта)
                     * bool method_exists (object имя_обьекта. string имя_метода)
                     * string get_class(object имя_объекта);
                     * string get_parent_class (object имя_обьекта);
                     * bool is_subclass_of (object объект, string имя_класса)
                     * array get_declared_classes()
                     */
                }
            }
        }
    }

    public function InstallPlugin($Name)
    {
        /*
         * 1. Спросить у пользователя об установке
         * 2. Скачать/разархивировать файл
         * 2. Сделать инклуд файла
         * 3. Выставить разрешения на запрашиваемые действия
         * 4. Выполнить функцию Plugin->Install()
         * 5. Добавить: options, таблицу, добавить в т.plugins данные
         */
    }

    public function UnInstallPlugin($Name)
    {

    }

    public function ActivatePlugin($Name)
    {

    }

    public function DeActivatePlugin($Name)
    {

    }
}

?>