import{r as e,o as s,a,c as t,w as l,n as i,i as r,b as o,d as n,e as d,f as u,g as c,F as f,h as m,j as p,t as _}from"./index-24f85b57.js";import{_ as x,a as g}from"./service.67be531f.js";import{_ as w}from"./u-image.1c81cd7d.js";import{d as v}from"./designApi.3136092f.js";import{i as h}from"./indexApi.97976835.js";import{_ as j}from"./_plugin-vue_export-helper.1b428a4d.js";import"./u-icon.86e87a54.js";const y=j({__name:"index",setup(j){const y=e([]),b=e([]);return s((async()=>{const e=await h.index();b.value=e.article,y.value=await v.diyIndex()})),(e,s)=>{const v=r,h=m(p("u-swiper"),x),j=m(p("w-service"),g),k=m(p("u-image"),w);return a(),t(v,{class:i(e.themeName)},{default:l((()=>[o(" 轮播图片 "),n(v,{class:"layout-banner-widget"},{default:l((()=>[n(v,{class:"diy-swiper"},{default:l((()=>{var e;return[n(v,{class:"backdrop"}),n(h,{list:null==(e=y.value)?void 0:e.banner,class:"swiper",mode:"round",height:"300","bg-color":"0"},null,8,["list"])]})),_:1})])),_:1}),o(" 推荐服务 "),n(j,{grid:"25%",list:y.value.nav},null,8,["list"]),o(" 最新资讯 "),n(v,{class:"layout-news-widget"},{default:l((()=>[n(v,{class:"title"},{default:l((()=>[d("最新资讯")])),_:1}),n(v,{class:"list"},{default:l((()=>[(a(!0),u(f,null,c(b.value,((s,i)=>(a(),t(v,{key:i,class:"item",onClick:a=>e.$go("/pages/article/detail?id="+s.id)},{default:l((()=>[n(k,{"lazy-load":!0,"border-radius":"4",width:"240rpx",height:"180rpx",src:s.image,style:{"flex-shrink":"0"}},null,8,["src"]),n(v,{class:"flex flex-col justify-between px-20"},{default:l((()=>[n(v,{class:"truncate-line-1 font-xl font-weight-medium color-main"},{default:l((()=>[d(_(s.title),1)])),_:2},1024),n(v,{class:"truncate-line-2 font-xs color-text"},{default:l((()=>[d(_(s.intro),1)])),_:2},1024),n(v,{class:"flex justify-between"},{default:l((()=>[n(v,{class:"font-xs color-muted"},{default:l((()=>[d(_(s.create_time),1)])),_:2},1024),n(v,{class:"font-xs color-muted"},{default:l((()=>[d(_(s.browse)+"人浏览",1)])),_:2},1024)])),_:2},1024)])),_:2},1024)])),_:2},1032,["onClick"])))),128))])),_:1})])),_:1})])),_:1},8,["class"])}}},[["__scopeId","data-v-d24e99c7"]]);export{y as default};