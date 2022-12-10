<h1 align="center">WaitAdmin管理后台</h1>

## 目录结构
```
├─📂 server
│  ├─📂 addons       // 插件目录
│  │  ├─📂 curd      // CURD插件
│  │  ├─📂 ...       // 其它插件
│  │
│  ├─📂 app               // 应用目录
│  │  ├─📂 api            // 接口应用
│  │  │  ├─📂 controller  // 控制器
│  │  │  ├─📂 service     // 服务层
│  │  │  ├─📂 validate    // 验证层
│  │  │  ├─📂 view        // 视图层
│  │  ├─📂 backend        // 后台应用
│  │  ├─📂 common         // 公共应用
│  │  ├─📂 frontend       // 前端应用
│  │
│  ├─📂 public            // WEB入口
│  │  ├─📂 install        // 安装程序目录
│  │  ├─📂 locals         // 本地存储目录
│  │  ├─📂 static         // 静态文件目录
│  │  ├─📂 storage        // 资源存储目录
│  │  ├─📄 index.php      // PHP入口文件
│  │
│  ├─📂 extend            // 扩展目录
│  ├─📂 route             // 路由目录
│  ├─📂 runtime           // 运行目录
│  ├─📂 vendor            // 第三方依赖
│  ├─📄 .env              // 项目环境配置文件
│  ├─📄 .example.env      // 环境配置模板文件

```