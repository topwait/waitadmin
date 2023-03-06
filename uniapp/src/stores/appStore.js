import { defineStore } from 'pinia'
import { getSysConfigApi } from '@/api/indexApi'
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
            this.config = await getSysConfigApi()
            toolUtil.setTabBar()
        },
        h5Intercepts() {
            // #ifdef H5
            const { status, close_url } = this.h5ConfigVal
            if (status === 1) {
                if (close_url) return (location.href = close_url)
                uni.reLaunch({ url: '/pages/empty/empty' })
            }
            // #endif
        }
    }
})
