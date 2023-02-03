import { useUserStore } from '@/stores/userStore'
import PagesJSON from '@/pages.json'

export default {
    go(url) {
        return uni.navigateTo({url: url})
    },
    intercept() {
        const list = ['navigateTo', 'redirectTo', 'reLaunch', 'switchTab']
        list.forEach((item) => {
            uni.addInterceptor(item, {
                invoke(e) {
                    // 提取页面路径
                    const url = e.url.split('?')[0]
                    const currentRoute = routes().find((item) => {
                        return url === item.path
                    })

                    // 登录页面拦截
                    if (currentRoute?.auth && !useUserStore.isLogin) {
                        uni.navigateTo({
                            url: '/pages/login/login'
                        })
                        return false
                    }
                    
                    return e
                },
                fail(err) {
                   throw {errMsg: err}
                }
            })
        })
    }
}

function routes() {
    const mainsPages = __getPagesRoutes(PagesJSON.pages)
    const subPackage = __getSubPackagesRoutes(PagesJSON)
    return mainsPages.concat(subPackage)
}

function __getPagesRoutes(pages, rootPath = null) {
    const includes = ['name', 'auth', 'path', 'aliasPath']    
    const routes = []
    for (let i = 0; i < pages.length; i++) {
        const item = pages[i]
        const route = {}
        for (let j = 0; j < includes.length; j++) {
            const key = includes[j]
            let value = item[key]
            if (key === 'path') {
                value = rootPath ? `/${rootPath}/${value}` : `/${value}`
            }
            if (key === 'aliasPath' && i == 0 && rootPath == null) {
                route[key] = route[key] || '/'
            } else if (value !== undefined) {
                route[key] = value
            }
        }
        routes.push(route)
    }
    return routes
}

function __getSubPackagesRoutes(pagesJson) {
    const { subPackages } = pagesJson
    let routes = []
    if (subPackages == null || subPackages.length == 0) {
        return []
    }
    for (let i = 0; i < subPackages.length; i++) {
        const subPages = subPackages[i].pages
        const root = subPackages[i].root
        const subRoutes = __getPagesRoutes(subPages, root)
        routes = routes.concat(subRoutes)
    }
    return routes
}
