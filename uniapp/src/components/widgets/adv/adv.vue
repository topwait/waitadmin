<template>
    <view class="layout-adv-widget">
        <u-swiper
            :list="advList"
            :height="200"
            :title="false"
            :border-radius="14"
            indicator-pos="none"
            @click="onJump"
        />
    </view>
</template>

<script setup>
import { computed } from 'vue'
import toolUtil from '@/utils/toolUtil'

const props = defineProps({
    list: {
        type: Array,
        default: () => []
    }
})

const advList = computed(() => {
    let data = []
    props.list.forEach((item) => {
        let title = ''
        if (item.title !== undefined) {
            title = item.title
        } else if (item.name !== undefined) {
            title = item.name
        } else if (item.text !== undefined) {
            title = item.text
        }
        data.push({
            title: title,
            image: item.image,
            link: item.link
        })
    })
    return data
})

const onJump = (index) => {
    const url = advList.value[index].link
    if (toolUtil.tarBarList().includes(url.trim('/'))) {
        uni.switchTab({ url: url })
    } else {
        uni.navigateTo({ url: url })
    }
}
</script>

<style lang="scss" scoped>
.layout-adv-widget {
    padding: 0 20rpx;
    box-sizing: border-box;
}
</style>
