const { spawn } = require('child_process')
const readline = require('readline')

class PublishClientScript {
    constructor() {
        if (PublishClientScript.instance) {
            return PublishClientScript.instance
        }
        PublishClientScript.instance = this
    }

    promptUser(question) {
        return new Promise((resolve) => {
            const rl = readline.createInterface({
                input: process.stdin,
                output: process.stdout
            })
            rl.question(question, (res) => {
                resolve(res)
                rl.close()
            })
        })
    }

    async runClient() {
        // eslint-disable-next-line no-console
        console.error('请选择你需要打包的客户端（回复数字后回车） ')
        // eslint-disable-next-line no-console
        console.error('0.取消')
        // eslint-disable-next-line no-console
        console.error('1.微信小程序')
        // eslint-disable-next-line no-console
        console.error('2.公众号或者H5')
        const runClientRes = await this.promptUser('请输入打包的客户端：')
        switch (runClientRes) {
            case '0':
                break
            case '1':
                await this.runNpmScript('build:mp-weixin')
                break
            case '2':
                await this.runNpmScript('build:h5')
                break
            default:
                await this.runClient()
                break
        }
    }

    runNpmScript(scriptName) {
        return new Promise((resolve, reject) => {
            const isWindows = process.platform === 'win32'
            const command = isWindows ? 'cmd.exe' : 'npm'
            const args = isWindows
                ? ['/c', 'npm', 'run', scriptName]
                : ['run', scriptName]

            const runProcess = spawn(command, args)

            runProcess.stdout.on('data', (data) => {
                // eslint-disable-next-line no-console
                console.log(data.toString())
            })

            runProcess.stderr.on('data', (data) => {
                // eslint-disable-next-line no-console
                console.error(data.toString())
            })

            runProcess.on('close', (code) => {
                if (code !== 0) {
                    reject(
                        new Error(
                            `运行错误，请查看以下报错信息寻找解决方法: ${error.message}`
                        )
                    )
                } else {
                    resolve()
                }
            })
        })
    }

    async run(targetVersion) {
        const currentVersion = process.versions.node

        if (currentVersion < targetVersion) {
            throw new Error(
                `你的当前node版本为(${currentVersion})，需要安装目标版本为 ${targetVersion} 以上！！`
            )
        }

        await this.runClient()
    }

    static getInstance() {
        if (!PublishClientScript.instance) {
            PublishClientScript.instance = new PublishClientScript()
        }
        return PublishClientScript.instance
    }
}

(async () => {
    const publish = PublishClientScript.getInstance()
    try {
        await publish.run('16.16.0')
    } catch (error) {
        // eslint-disable-next-line no-console
        console.error(error.message)
    }
})()
