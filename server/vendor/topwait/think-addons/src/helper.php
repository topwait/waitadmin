<?php
declare(strict_types=1);

use Symfony\Component\VarExporter\VarExporter;
use think\Console;
use think\facade\Config;
use think\facade\Event;
use think\facade\Route;
use think\facade\App;
use think\facade\Db;
use think\helper\Str;
use think\addons\Service;

define('DS', DIRECTORY_SEPARATOR);

/**
 * 插件控制台类载入
 */
Console::starting(function (Console $console) {
    $console->addCommands([
        'addons:config' => '\\think\\addons\\command\\SendConfig'
    ]);
});

/**
 * 插件类库自动载入
 */
spl_autoload_register(function ($class) {
    $class = ltrim($class, '\\');
    $rootPath  = app()->getRootPath();
    $namespace = 'addons';
    if (strpos($class, $namespace) === 0) {
        $class = substr($class, strlen($namespace));
        $path  = '';
        if (($pos = strripos($class, '\\')) !== false) {
            $path = str_replace('\\', '/', substr($class, 0, $pos)) . '/';
            $class = substr($class, $pos + 1);
        }
        $path .= str_replace('_', '/', $class) . '.php';
        $rootPath .= $namespace . $path;

        if (file_exists($rootPath)) {
            include $rootPath;
            return true;
        }

        return false;
    }

    return false;
});

if (!function_exists('httpType')) {
    /**
     * 获取HTTP类型
     *
     * @author windy
     * @return string (http:// 或 https://)
     */
    function httpType() {
        $domain = request()->domain();
        if (strpos($domain, 'https://') === 0) {
            return 'https://';
        }

        return 'http://';
    }
}

if (!function_exists('httpDomain')) {
    /**
     * 不带协议的域名
     *
     * @author windy
     * @return string|string[]
     */
    function httpDomain() {
        $domain = request()->domain();
        if (strpos($domain, 'https://') === 0) {
            return str_replace('https://', '', $domain);
        }
        return str_replace('http://', '', $domain);
    }
}

if (!function_exists('hook')) {
    /**
     * 处理插件钩子事件
     *
     * @param string $event 钩子名称
     * @param array|null $params 传入参数
     * @param bool $once 是否只返回一个结果
     * @return mixed
     */
    function hook(string $event, $params = null, bool $once = false)
    {
        $result = Event::trigger($event, $params, $once);
        return join('', $result);
    }
}

if (!function_exists('addons_url')) {
    /**
     * 插件显示内容里生成访问插件的url
     *
     * @param string $url 地址格式：插件名/模块/控制器/方法 或者只有方法
     * @param array $param
     * @param bool|string $suffix 生成的URL后缀
     * @param bool|string $domain 域名
     * @return bool|string
     */
    function addons_url(string $url = '', $param = [], $suffix = true, $domain = false)
    {
        $request = app('request');
        if (!is_array($param)) {
            parse_str((string) $param, $params);
            $param = $params;
        }

        if (empty($url)) {
            $addons     = $request->addon;
            $controller = $request->controller();
            $module     = explode('.', $controller)[0];
            $controller = $module . '/' . explode('.', $controller)[1];
            $action     = $request->action();
        } else {
            $url = Str::studly($url);
            $url = parse_url($url);
            if (isset($url['scheme'])) {
                $addons     = strtolower($url['scheme']);
                $module     = lcfirst(array_pop($route));
                $controller = trim($url['host']);
                $action     = trim($url['path'], '/');
            } else {
                $route      = explode('/', $url['path']);
                $addons     = $request->addon;
                $module     = lcfirst($request->param('module', 'frontend'));
                $action     = array_pop($route);
                $controller = array_pop($route) ?: $request->controller();
                $controller = substr_replace($controller, '', 0, strlen($module)+1);
            }

            // 解析URL带的参数
            $controller = Str::snake((string) $controller);
            if (isset($url['query'])) {
                parse_str($url['query'], $query);
                $param = array_merge($query, $param);
            }
        }

        return Route::buildUrl("@addons/{$addons}/{$controller}/{$action}", $param)->suffix($suffix)->domain($domain);
    }
}

if (!function_exists('addons_path')) {
    /**
     * 获取插件基础目录
     *
     * @return string
     * @author windy
     */
    function addons_path(string $name='') {
        $path = root_path() . 'addons' . DS;
        if (trim($name)) {
            return $path . $name . DS;
        }
        return $path;
    }
}

if (!function_exists('get_addons_instance')) {
    /**
     * 获取插件的单例
     *
     * @param string $name 插件名
     * @return mixed|null
     */
    function get_addons_instance(string $name)
    {
        static $_addons = [];
        if (isset($_addons[$name])) {
            return $_addons[$name];
        }
        $class = get_addons_class($name);
        if (class_exists($class)) {
            $_addons[$name] = new $class(app());
            return $_addons[$name];
        } else {
            return null;
        }
    }
}

if (!function_exists('get_addons_config')) {
    /**
     * 获取插件的配置
     *
     * @param $name (插件名称)
     * @return array
     */
    function get_addons_config($name)
    {
        $addon = get_addons_instance($name);
        if (!$addon) {
            return [];
        }

        return $addon->getConfig($name);
    }
}

if (!function_exists('get_addons_class')) {
    /**
     * 获取插件类的类名
     *
     * @param string $name    插件名
     * @param string $type   返回命名空间类型
     * @param null $class    当前类名
     * @param string $module 模块名
     * @return string
     */
    function get_addons_class(string $name, string $type = 'hook', $class = null, string $module = 'index'): string
    {
        $name = trim($name);

        // 处理多级控制器情况
        if (!is_null($class) && strpos($class, '.')) {
            $class = explode('.', $class);
            $class[count($class) - 1] = Str::studly(end($class));
            $class = implode('\\', $class);
        } else {
            $class = Str::studly(is_null($class) ? $name : $class);
        }

        switch ($type) {
            case 'controller':
                if ($module) {
                    $namespace = '\\addons\\' . $name . '\\' . $module . '\\controller\\' . $class;
                } else {
                    $namespace = '\\addons\\' . $name . '\\controller\\' . $class;
                }
                break;
            default:
                $namespace = '\\addons\\' . $name . '\\Plugin';
        }

        return class_exists($namespace) ? $namespace : '';
    }
}

if (!function_exists('get_addons_list')) {
    /**
     * 获取本地插件列表
     *
     * @author windy
     * @return array
     */
    function get_addons_list()
    {
        $service = new Service(App::instance());
        $addonsPath = $service->getAddonsPath();
        foreach (scandir($addonsPath) as $name) {
            if (in_array($name, ['.', '..'])) {
                continue;
            }

            if (is_file($addonsPath . $name)) {
                continue;
            }

            $addonDir = $addonsPath . $name . DS;
            if (!is_dir($addonDir)) {
                continue;
            }

            if (!is_file($addonDir . 'Plugin' . '.php')) {
                continue;
            }

            $info = get_addons_info($name);
            if (!isset($info['name'])) {
                continue;
            }

            $info['url'] = isset($info['url']) && $info['url'] ? (string) addons_url($info['url']):'';
            $list[$name] = $info;
            return $list;
        }
    }
}

if (!function_exists('get_addons_info')) {
    /**
     * 读取插件基础信息
     *
     * @param string $name 插件名
     * @return array
     */
    function get_addons_info(string $name): array
    {
        $addon = get_addons_instance($name);
        if (!$addon) {
            return [];
        }

        return $addon->getInfo();
    }
}

if (!function_exists('set_addons_info')) {
    /**
     * 设置插件信息
     *
     * @param $name   (插件名称)
     * @param $array  (插件数据)
     * @return bool
     * @throws Exception
     * @author windy
     */
    function set_addons_info($name, $array)
    {
        $service = new Service(App::instance());
        $addonsPath = $service->getAddonsPath();

        // 插件列表
        $file  = $addonsPath . $name . DIRECTORY_SEPARATOR . 'service.ini';
        $addon = get_addons_instance($name);
        $array = $addon->setInfo($name, $array);
        $array['status'] ? $addon->enabled() : $addon->disabled();
        if (!isset($array['name']) || !isset($array['title']) || !isset($array['version'])) {
            throw new Exception("Failed to write plugin config");
        }
        $res = array();
        foreach ($array as $key => $val) {
            if (is_array($val)) {
                $res[] = "[$key]";
                foreach ($val as $k => $v)
                    $res[] = "$k = " . (is_numeric($v) ? $v : $v);
            } else
                $res[] = "$key = " . (is_numeric($val) ? $val : $val);
        }

        if ($handle = fopen($file, 'w')) {
            fwrite($handle, implode("\n", $res) . "\n");
            fclose($handle);
        } else {
            throw new Exception("File does not have write permission");
        }
        return true;
    }
}

if (!function_exists('set_addons_config')) {
    /**
     * 设置插件配置
     *
     * @param $name  (插件名称)
     * @param $array (配置数组)
     * @return bool
     * @throws Exception
     */
    function set_addons_config($name, $array)
    {
        $service = new Service(App::instance());
        $addonsPath = $service->getAddonsPath();

        $file = $addonsPath . $name . DIRECTORY_SEPARATOR . 'config.php';
        if (!is_really_writable($file)) {
            throw new \Exception(lang("addons.php File does not have write permission"));
        }

        if ($handle = fopen($file, 'w')) {
            fwrite($handle, "<?php\n\n" . "return " . VarExporter::export($array) . ";");
            fclose($handle);
        } else {
            throw new Exception(lang("File does not have write permission"));
        }

        return true;
    }
}

if (!function_exists('refresh_addons_config')) {
    /**
     * 刷新插件配置
     *
     * @return bool
     * @throws @\Symfony\Component\VarExporter\Exception\ExceptionInterface
     */
    function refresh_addons_config()
    {
        $file = app()->getRootPath() . 'config' . DS . 'addons.php';
        $config = autoload_addons_config(true);

        if (!$config['autoload']) {
            return false;
        }

        if (!is_really_writable($file)) {
            throw new Exception(lang("addons.js File does not have write permission"));
        }

        if ($handle = fopen($file, 'w')) {
            fwrite($handle, "<?php\n\n" . "return " . VarExporter::export($config) . ";");
            fclose($handle);
        } else {
            throw new Exception(lang('File does not have write permission'));
        }

        return true;
    }
}

if (!function_exists('autoload_addons_config')) {
    /**
     * 自动加载插件配置
     *
     * @param bool $chunk (true=清空手动配置的钩子)
     * @return array
     */
    function autoload_addons_config($chunk = false)
    {
        // 读取插件的配置
        $config = (array)Config::get('addons');
        if ($chunk) {
            $config['hooks'] = [];
        }

        // 读取插件目录及钩子列表
        $route = [];
        $base = get_class_methods("\\think\\Addons");
        $base = array_merge($base, ['init','initialize','install', 'uninstall', 'enabled', 'disabled']);

        $url_domain_deploy = Config::get('route.route_domain_deploy');
        $addons = get_addons_list();
        $domain = [];
        foreach ($addons as $name => $addon) {
            if (!$addon['install']) continue;
            if (!$addon['status']) continue;
            // 读取出所有的公共方法函数
            $methods = (array)get_class_methods("\\addons\\" . $name . "\\" . 'Plugin');
            // 跟插件基类方法比对得差异
            $hooks = array_diff($methods, $base);
            // 循环将钩子方法写入配置中
            foreach ($hooks as $hook) {
                $hook = Str::studly($hook);
                if (!isset($config['hooks'][$hook])) {
                    $config['hooks'][$hook] = [];
                }
                // 兼容手动配置项
                if (is_string($config['hooks'][$hook])) {
                    $config['hooks'][$hook] = explode(',', $config['hooks'][$hook]);
                }
                if (!in_array($name, $config['hooks'][$hook])) {
                    $config['hooks'][$hook][] = $name;
                }
            }

            $conf = get_addons_config($addon['name']);
            if ($conf) {
                $conf['rewrite'] = isset($conf['rewrite']) && is_array($conf['rewrite']) ? $conf['rewrite'] : [];
                $rule = $conf['rewrite'] ? $conf['rewrite']['value'] : [];
                if ($url_domain_deploy && isset($conf['domain']) && $conf['domain']) {
                    $domain[] = [
                        'addons' => $addon['name'],
                        'domain' => $conf['domain']['value'],
                        'rule'   => $rule
                    ];
                } else {
                    $route = array_merge($route, $rule);
                }
            }
        }

        $config['route'] = $route;
        $config['route'] = array_merge($config['route'], $domain);
        return $config;
    }
}

if (!function_exists('install_addons_sql')) {
    /**
     * 安装插件数据库
     *
     * @author windy
     * @param string $name (插件名称)
     * @return bool
     * @throws Exception
     */
    function install_addons_sql(string $name)
    {
        $service = new Service(App::instance());
        $addonsPath = $service->getAddonsPath();
        $sqlFile = $addonsPath . $name . DS . 'install.sql';
        if (is_file($sqlFile)) {
            $gz = fopen($sqlFile, 'r');
            $sql = '';
            while(1) {
                $sql .= fgets($gz);
                if(preg_match('/.*;$/', trim($sql))) {
                    $sql = preg_replace('/(\/\*(\s|.)*?\*\/);/','',$sql);
                    $sql = str_replace('__PREFIX__', config('database.connections.mysql.prefix'),$sql);
                    if(strpos($sql,'CREATE TABLE')!==false || strpos($sql,'INSERT INTO')!==false || strpos($sql,'ALTER TABLE')!==false || strpos($sql,'DROP TABLE')!==false){
                        try {
                            Db::execute($sql);
                        } catch (\Exception $e) {
                            throw new Exception($e->getMessage());
                        }
                    }
                    $sql = '';
                }
                if(feof($gz)) break;
            }
        }
        return true;
    }
}

if (!function_exists('uninstall_addons_sql')) {
    /**
     * 卸载插件数据库
     *
     * @author windy
     * @param $name (插件名称)
     * @return bool
     * @throws Exception
     */
    function uninstall_addons_sql($name)
    {
        $service = new Service(App::instance());
        $addonsPath = $service->getAddonsPath();
        $sqlFile = $addonsPath . $name . DS . 'uninstall.sql';
        if (is_file($sqlFile)) {
            $sql = file_get_contents($sqlFile);
            $sql = str_replace('__PREFIX__', config('database.connections.mysql.prefix'),$sql);
            $sql = explode("\r\n",$sql);
            foreach ($sql as $k=>$v){
                if(strpos(strtolower($v),'drop table')!==false){
                    try {
                        Db::execute($v);
                    } catch (\Exception $e) {
                        throw new Exception($e->getMessage());
                    }
                }

            }
        }
        return true;
    }
}

if (!function_exists('get_source_assets_dir')) {
    /**
     * 获取插件原始资源目录
     *
     * @author windy
     * @param string $name (插件名称)
     * @return string
     */
    function get_source_assets_dir(string $name)
    {
        $path = app()->getRootPath() . 'addons/' . $name . DS . 'public' . DS;
        return str_replace('\\', '/', $path);
    }
}

if (!function_exists('get_target_assets_dir')) {
    /**
     * 获取插件目标资源目录
     *
     * @author windy
     * @param string $name (插件名称)
     * @return string
     */
    function get_target_assets_dir(string $name)
    {
        $assetsDir = app()->getRootPath() . "public".DS."static".DS."addons".DS."{$name}".DS;
        if (!is_dir($assetsDir)) {
            mkdir($assetsDir, 0755, true);
        }
        return $assetsDir;
    }
}

if (!function_exists('is_really_writable')) {
    /**
     * 是否有写入权限
     *
     * @param string $dir
     * @return bool
     */
    function is_really_writable(string $dir)
    {
        if (DIRECTORY_SEPARATOR == '/' AND @ ini_get("safe_mode") == FALSE) {
            return is_writable($dir);
        }

        if (!is_file($dir) OR ($fp = @fopen($dir, "r+")) === FALSE) {
            return false;
        }

        fclose($fp);
        return true;
    }
}