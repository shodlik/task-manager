<?php namespace  vendor;

class Controller extends View
{
    public $layout="main";

    public function render($view,$data=[]){
        $content = $this->getViews($view,$data);
        $layout = $this->getLayout( $content);
        return $layout;
    }

    public function redirect($url){
        $url = "Location: {$this->cureentDomain()}{$url}";
        header($url);
    }

    public function getLayout($content){
        $layout_file = __DIR__ . "/../views/layout/{$this->layout}.php";
        if(file_exists($layout_file))
            $layoutContent = require_once($layout_file);
        else
            echo "Error: Layout not found!!!";die;
        return $layoutContent;
    }

    public function getViews($view,$data){
        $class = self::GetClassName();
        $class = str_replace("controller\\","",$class);
        $class = lcfirst(str_replace("Controller","",$class));
        $view_file =  __DIR__ . "/../views/{$class}/{$view}.php";
        if(file_exists($view_file))
            $viewContent = $this->renderPhpFile($view_file,$data);
        else{
            echo "Error: View file not found!!!";die;
        }
        return $viewContent;
    }

    public static function GetClassName() {
        return get_called_class();
    }

    public function isAdmin(){
        if(Base::isGuest()){
            View::setAlert("warning", 'Запрещенное действие');
            $this->redirect('/site/index');
            die;
        }
    }

    public function renderPhpFile($_file_, $_params_ = [])
    {
        $_obInitialLevel_ = ob_get_level();
        ob_start();
        ob_implicit_flush(false);
        extract($_params_, EXTR_OVERWRITE);
        try {
            require $_file_;
            return ob_get_clean();
        } catch (\Exception $e) {
            while (ob_get_level() > $_obInitialLevel_) {
                if (!@ob_end_clean()) {
                    ob_clean();
                }
            }
            throw $e;
        } catch (\Throwable $e) {
            while (ob_get_level() > $_obInitialLevel_) {
                if (!@ob_end_clean()) {
                    ob_clean();
                }
            }
            throw $e;
        }
    }
}