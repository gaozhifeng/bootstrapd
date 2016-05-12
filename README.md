# 关于：
> **bootstrapd** 是快速轻量的RESTful框架。

* 提供优秀快速的基础架构与丰富的功能类库
* 路由精巧美观的url设计，让请求回归原生，同时支持多项自动加载
* 支持系统错误处理与异常捕获，自定义异常处理
* 系统日志分类与性能分析
* 目录结构设计为应用、公共、配置、类库、运行数据、服务端脚本、静态资源、入口结构
* 统一的请求入口与响应输出对入参安全与输出安全进行有效处理


# 目录结构：
**src 源文件目录**

app                 应用  
　　|- default        - 默认  
　　|- resouce       资源  
　　|- model         模型  
　　|- template      模板  
common              公共  
　　|- exception      - 异常  
config              配置  
library             库  
　　|- interface      - 接口  
　　|- plugin         - 插件  
　　|- util           - 工具  
runtime             运行  
　　|- cache          - 缓存  
　　|- data           - 数据  
　　|- log            - 日志  
　　|- session        - 会话  
server              服务  
　　|- crontab        - 任务  
　　|- monitor        - 监控  
static              模板  
　　|- default        - 默认  
　　　　|- images        图片  
　　　　|- style         样式  
　　　　|- js            脚本  
webroot             入口  

**test PHPUnit目录**

common              公共PHPUnit Case

-------------------
*© bootstrapd*
