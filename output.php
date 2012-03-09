<?php

class Output extends Input
{

    function __construct()
    {
        Input :: __construct();
        $this->LogAdd('service', 'Input init.');
        $this->AddStage(3, array('Class' => 'Init', 'Method' => 'SwitchMethod', 'Params' => NULL));
        $this->AddStage(4, array('Class' => 'Init', 'Method' => 'Constructor', 'Params' => NULL));
        $this->AddStage(4, array('Class' => 'Init', 'Method' => 'LoadDefaultComponents', 'Params' => NULL));
        $this->AddStage(8, array('Class' => 'Init', 'Method' => 'OutputRender', 'Params' => NULL));
        $this->AddStage(8, array('Class' => 'Init', 'Method' => 'Output', 'Params' => NULL));
    }

    protected function Output()
    {
        // Выбор метода вывода информации
        $this->OutputToBrowser();
    }

    protected function OutputContent()
    {
        // вывод основного содержимого в переменную $Output
    }

    protected function OutputLog()
    {
        $this->LogAdd('output', 'Called OutputLog.');
        return $this->Log;
    }

    protected function OutputToFile()
    {

    }

    protected function OutputToBrowser()
    {
        echo $this->Output;
    }

    protected function SaveLog()
    {
        $this->db->i('logs')->v('NULL, ?, CURRENT_TIMESTAMP, ?, ?')->e(json_encode($this->Log), round($this->getMicrotime() - $this->Config['start_time'], 4), 'http://'.$this->_SERVER['HTTP_HOST'].$this->_SERVER['PHP_SELF'].'?'.$this->_SERVER['QUERY_STRING']);
    }

    protected function LoadTemplate($Name)
    {
        if (strpos($Name, '.') > 0)
        {
            $from = 'file';
        }
        else
        {
            $from = $this->Config['template_from'];
        }

        switch ($from)
        {
            case 'db': {
                    $array = $this->db->s('body', 'objects')->f('templates')->w('type = ?')->e($Name);
                    return $array;
                    break;
                }
            case 'file': {
                    if (CACHE)
                    {
                        $key = md5(TEMPLATE_PATH.'/'.$Name);
                        $cache = $this->cache->get($key);
                        $usecache = ($cache) ? true : false;

                        if ($usecache)
                        {
                            $result = $cache;
                        }
                        else
                        {
                            $result = file_get_contents(TEMPLATE_PATH.'/'.$Name);
                            $this->cache->add($key, $result, false, 120);
                        }
                    }
                    else
                    {
                        $result = file_get_contents(TEMPLATE_PATH.'/'.$Name);
                    }

                    return $result;
                    break;
                }

            default: {

                    break;
                }
        }
    }

    protected function SwitchMethod()
    {
        $this->CallToAction($this->Vars['method']);
    }

    protected function Constructor()
    {
        $this->InsertCSS('/css/bootstrap.min.css');
        $this->InsertCSS('/css/bootstrap-responsive.min.css');
        //$this->InsertCSS('/css/font-awesome.css');
        $this->Template = $this->LoadTemplate('main.html');
    }

    protected function OutputRender()
    {
        if ($this->Redirect == "")
        {
            $this->LogAdd('output', 'Begin render.');
            $this->Output = $this->Render($this->Template, $this->Content);
            $this->LogAdd('output', 'End render.');
        }
        else
        {
            header('Location: '.$this->Redirect);
        }
    }

    protected function LoadComponent($Name)
    {
fkk
        $Component = $this->LoadTemplate($Name);
        $this->Template = str_replace('{{{'.$Name.'}}}', $Component[0]['body'], $this->Template);

        return $Component[0]['body'];
    }

    protected function LoadDefaultComponents()
    {
        $IDs = $this->db->s('type')->f('templates')->w('render = 1')->e();

        foreach ($IDs as $id)
        {
            $this->LoadComponent($id['type']);
        }
    }

    protected function Render($Template, $Content)
    {
        $m = new Mustache();

        return $m->render($Template, $Content);
    }
}

?>