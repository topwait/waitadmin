import { defineStore } from 'pinia'
import IndexApi from '@/api/IndexApi'
import toolUtil from '@/utils/toolUtil'

export const useAppStore = defineStore({
    id: 'appStore',
    state: () => {
        return {
            config: {
                h5: {},
                login: {},
                tabBar: []
            }
        }
    },
    getters: {
        h5ConfigVal: (state) => state.config.h5 || {},
        loginConfigVal: (state) => state.config.login || {},
        tabBarConfigVal: (state) => state.config.tabBar || []
    },
    actions: {
        async getSysConfig() {
            this.config = await IndexApi.config()
            toolUtil.setTabBar()
        },
        h5Intercepts() {
            // #ifdef H5
            const { status, close_url } = this.h5ConfigVal
            if (status === 0) {
                if (close_url) {
                    location.href = close_url
                    return
                }
                uni.reLaunch({ url: '/pages/empty/empty' })
            }
            // #endif
        }
    }
})
