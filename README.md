Wavlink 官方网站
================
WAVLINK品牌是立足中国，放眼全球的一个超新品牌，并且苦心经营数十载。官方网站采用thinkPHP为框架，严格遵循PSR-2\PSR-3等规范，代码严谨，
几乎捕捉到所有的异常信息，对用户输入采用严格验证，二次扣扩展灵活，采用低耦合开发模式。
本系统对睿因科技有限公司提供终身服务，本程序采用的编程思路可以被用于二次开发利用。

### 系统特色
规范：遵循PSR-2、PSR-3及PSR-4规范，Composer及单元测试支持；
严谨：异常严谨的错误检测和安全机制，详细的日志信息，为你的开发保驾护航；
灵活：减少核心依赖，扩展更灵活、方便，支持命令行指令扩展；
架构：分布式开发架构，支持数据库读写分离等
CMS架构：
1. 多语言（通过请求头来获得语言）
2. 采用redis做缓存
3. 多终端显示兼容（通过判断用户的设备来得知应该使用哪个模板进行渲染）

### 应用介绍
应用从3.2.3升级到5.0.23，再从5.0.23升级到5.1,中间经历了几次大的程序结构修改，但都严格遵循开发规范，但是不支持直接无缝升级，后续更新到更高版本，会及时手动升级。
本次更新主要体现在：
1. 所有异常都在控制器被捕获
2. 所有的异常抛出都在Model层的数据逻辑层操作
3. 重视析构函数，将全文需要用到的模型层，验证层在析构函数中注入
4. 严格编写控制器，将不存在的请求拒绝访问
5. 模板格式规范化，内容采用本地化开发风格
6. 优化缓存系统
7. 将session 放进redis
