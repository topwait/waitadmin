import{h as e,j as t,a as r,c as i,w as n,m as a,d as s,b as l,n as o,O as u,_ as d,$ as f,i as c,e as h,t as p,p as m,I as g,a0 as y,a1 as b,a2 as v,r as S}from"./index-78660ce3.js";import{_}from"./u-icon.a3f2a7ad.js";import{_ as w}from"./_plugin-vue_export-helper.1b428a4d.js";function x(e,t,r){}const q={methods:{dispatch(e,t,r){let i=this.$parent||this.$root,n=i.$options.name;for(;i&&(!n||n!==e);)i=i.$parent,i&&(n=i.$options.name);i&&i[t](r)},broadcast(e,t,r){x.call(this,e,t,r)}}};const O=w({name:"u-input",emits:["update:modelValue","input","change","blur","focus","click","touchstart"],mixins:[q],props:{value:{type:[String,Number],default:""},modelValue:{type:[String,Number],default:""},type:{type:String,default:"text"},inputAlign:{type:String,default:""},placeholder:{type:String,default:"请输入内容"},disabled:{type:Boolean,default:!1},maxlength:{type:[Number,String],default:140},placeholderStyle:{type:String,default:"color: #c0c4cc;"},confirmType:{type:String,default:"done"},customStyle:{type:Object,default:()=>({})},fixed:{type:Boolean,default:!1},focus:{type:Boolean,default:!1},passwordIcon:{type:Boolean,default:!0},border:{type:Boolean,default:!1},borderColor:{type:String,default:"#dcdfe6"},autoHeight:{type:Boolean,default:!0},selectOpen:{type:Boolean,default:!1},height:{type:[Number,String],default:""},clearable:{type:[Boolean,String]},cursorSpacing:{type:[Number,String],default:0},selectionStart:{type:[Number,String],default:-1},selectionEnd:{type:[Number,String],default:-1},trim:{type:Boolean,default:!0},showConfirmbar:{type:Boolean,default:!0},adjustPosition:{type:Boolean,default:!0},backgroundColor:{type:String},padding:{type:String}},data:()=>({defaultValue:"",inputHeight:70,textareaHeight:100,validateState:!1,focused:!1,showPassword:!1,lastValue:"",uForm:{inputAlign:"",clearable:""}}),watch:{valueCom(e,t){this.defaultValue=e,e!=t&&"select"==this.type&&this.handleInput({detail:{value:e}})}},computed:{valueCom(){return this.modelValue},inputAlignCom(){return this.inputAlign||this.uForm.inputAlign||"left"},clearableCom(){return"boolean"==typeof this.clearable?this.clearable:"boolean"!=typeof this.uForm.clearable||this.uForm.clearable},inputMaxlength(){return Number(this.maxlength)},getStyle(){let e={};return e.minHeight=this.height?this.height+"rpx":"textarea"==this.type?this.textareaHeight+"rpx":this.inputHeight+"rpx",e=Object.assign(e,this.customStyle),e},getCursorSpacing(){return Number(this.cursorSpacing)},uSelectionStart(){return String(this.selectionStart)},uSelectionEnd(){return String(this.selectionEnd)}},created(){this.defaultValue=this.valueCom},mounted(){let e=this.$u.$parent.call(this,"u-form");e&&Object.keys(this.uForm).map((t=>{this.uForm[t]=e[t]}))},methods:{handleInput(e){let t=e.detail.value;this.trim&&(t=this.$u.trim(t)),this.$emit("input",t),this.$emit("update:modelValue",t),this.defaultValue=t,setTimeout((()=>{this.dispatch("u-form-item","onFieldChange",t)}),40)},handleBlur(e){setTimeout((()=>{this.focused=!1}),100),this.$emit("blur",e.detail.value),setTimeout((()=>{this.dispatch("u-form-item","onFieldBlur",e.detail.value)}),40)},onFormItemError(e){this.validateState=e},onFocus(e){this.focused=!0,this.$emit("focus")},onConfirm(e){this.$emit("confirm",e.detail.value)},onClear(e){this.$emit("input",""),this.$emit("update:modelValue","")},inputClick(){this.$emit("click")}}},[["render",function(h,p,m,g,y,b){const v=d,S=f,w=e(t("u-icon"),_),x=c;return r(),i(x,{class:o(["u-input",{"u-input--border":m.border,"u-input--error":y.validateState}]),style:a({padding:m.padding?m.padding:`0 ${m.border?20:0}rpx`,borderColor:m.borderColor,textAlign:b.inputAlignCom,backgroundColor:m.backgroundColor}),onClick:u(b.inputClick,["stop"])},{default:n((()=>["textarea"==m.type?(r(),i(v,{key:0,class:"u-input__input u-input__textarea",style:a([b.getStyle]),value:y.defaultValue,placeholder:m.placeholder,placeholderStyle:m.placeholderStyle,disabled:m.disabled,maxlength:b.inputMaxlength,fixed:m.fixed,focus:m.focus,autoHeight:m.autoHeight,"selection-end":b.uSelectionEnd,"selection-start":b.uSelectionStart,"cursor-spacing":b.getCursorSpacing,"show-confirm-bar":m.showConfirmbar,"adjust-position":m.adjustPosition,onInput:b.handleInput,onBlur:b.handleBlur,onFocus:b.onFocus,onConfirm:b.onConfirm},null,8,["style","value","placeholder","placeholderStyle","disabled","maxlength","fixed","focus","autoHeight","selection-end","selection-start","cursor-spacing","show-confirm-bar","adjust-position","onInput","onBlur","onFocus","onConfirm"])):(r(),i(S,{key:1,class:"u-input__input",type:"password"==m.type?"text":m.type,style:a([b.getStyle]),value:y.defaultValue,password:"password"==m.type&&!y.showPassword,placeholder:m.placeholder,placeholderStyle:m.placeholderStyle,disabled:m.disabled||"select"===m.type,maxlength:b.inputMaxlength,focus:m.focus,confirmType:m.confirmType,"cursor-spacing":b.getCursorSpacing,"selection-end":b.uSelectionEnd,"selection-start":b.uSelectionStart,"show-confirm-bar":m.showConfirmbar,"adjust-position":m.adjustPosition,onFocus:b.onFocus,onBlur:b.handleBlur,onInput:b.handleInput,onConfirm:b.onConfirm},null,8,["type","style","value","password","placeholder","placeholderStyle","disabled","maxlength","focus","confirmType","cursor-spacing","selection-end","selection-start","show-confirm-bar","adjust-position","onFocus","onBlur","onInput","onConfirm"])),s(x,{class:"u-input__right-icon u-flex"},{default:n((()=>[b.clearableCom&&""!=b.valueCom&&y.focused?(r(),i(x,{key:0,class:"u-input__right-icon__clear u-input__right-icon__item",onClick:b.onClear},{default:n((()=>[s(w,{size:"32",name:"close-circle-fill",color:"#c0c4cc"})])),_:1},8,["onClick"])):l("v-if",!0),m.passwordIcon&&"password"==m.type?(r(),i(x,{key:1,class:"u-input__right-icon__clear u-input__right-icon__item"},{default:n((()=>[s(w,{size:"32",name:y.showPassword?"eye-fill":"eye",color:"#c0c4cc",onClick:p[0]||(p[0]=e=>y.showPassword=!y.showPassword)},null,8,["name"])])),_:1})):l("v-if",!0),"select"==m.type?(r(),i(x,{key:2,class:o(["u-input__right-icon--select u-input__right-icon__item",{"u-input__right-icon--select--reverse":m.selectOpen}])},{default:n((()=>[s(w,{name:"arrow-down-fill",size:"26",color:"#c0c4cc"})])),_:1},8,["class"])):l("v-if",!0)])),_:1})])),_:1},8,["class","style","onClick"])}],["__scopeId","data-v-671342db"]]);function A(){return A=Object.assign||function(e){for(var t=1;t<arguments.length;t++){var r=arguments[t];for(var i in r)Object.prototype.hasOwnProperty.call(r,i)&&(e[i]=r[i])}return e},A.apply(this,arguments)}var F=/%[sdj%]/g,j=function(){};function P(e){if(!e||!e.length)return null;var t={};return e.forEach((function(e){var r=e.field;t[r]=t[r]||[],t[r].push(e)})),t}function E(){for(var e=arguments.length,t=new Array(e),r=0;r<e;r++)t[r]=arguments[r];var i=1,n=t[0],a=t.length;if("function"==typeof n)return n.apply(null,t.slice(1));if("string"==typeof n){for(var s=String(n).replace(F,(function(e){if("%%"===e)return"%";if(i>=a)return e;switch(e){case"%s":return String(t[i++]);case"%d":return Number(t[i++]);case"%j":try{return JSON.stringify(t[i++])}catch(r){return"[Circular]"}break;default:return e}})),l=t[i];i<a;l=t[++i])s+=" "+l;return s}return n}function C(e,t){return null==e||(!("array"!==t||!Array.isArray(e)||e.length)||!(!function(e){return"string"===e||"url"===e||"hex"===e||"email"===e||"pattern"===e}(t)||"string"!=typeof e||e))}function I(e,t,r){var i=0,n=e.length;!function a(s){if(s&&s.length)r(s);else{var l=i;i+=1,l<n?t(e[l],a):r([])}}([])}function T(e,t,r,i){if(t.first){var n=new Promise((function(t,n){var a=function(e){var t=[];return Object.keys(e).forEach((function(r){t.push.apply(t,e[r])})),t}(e);I(a,r,(function(e){return i(e),e.length?n({errors:e,fields:P(e)}):t()}))}));return n.catch((function(e){return e})),n}var a=t.firstFields||[];!0===a&&(a=Object.keys(e));var s=Object.keys(e),l=s.length,o=0,u=[],d=new Promise((function(t,n){var d=function(e){if(u.push.apply(u,e),++o===l)return i(u),u.length?n({errors:u,fields:P(u)}):t()};s.length||(i(u),t()),s.forEach((function(t){var i=e[t];-1!==a.indexOf(t)?I(i,r,d):function(e,t,r){var i=[],n=0,a=e.length;function s(e){i.push.apply(i,e),++n===a&&r(i)}e.forEach((function(e){t(e,s)}))}(i,r,d)}))}));return d.catch((function(e){return e})),d}function k(e){return function(t){return t&&t.message?(t.field=t.field||e.fullField,t):{message:"function"==typeof t?t():t,field:t.field||e.fullField}}}function B(e,t){if(t)for(var r in t)if(t.hasOwnProperty(r)){var i=t[r];"object"==typeof i&&"object"==typeof e[r]?e[r]=A({},e[r],{},i):e[r]=i}return e}function $(e,t,r,i,n,a){!e.required||r.hasOwnProperty(e.field)&&!C(t,a||e.type)||i.push(E(n.messages.required,e.fullField))}"undefined"!=typeof process&&process.env;var N={email:/^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/,url:new RegExp("^(?!mailto:)(?:(?:http|https|ftp)://|//)(?:\\S+(?::\\S*)?@)?(?:(?:(?:[1-9]\\d?|1\\d\\d|2[01]\\d|22[0-3])(?:\\.(?:1?\\d{1,2}|2[0-4]\\d|25[0-5])){2}(?:\\.(?:[0-9]\\d?|1\\d\\d|2[0-4]\\d|25[0-4]))|(?:(?:[a-z\\u00a1-\\uffff0-9]+-*)*[a-z\\u00a1-\\uffff0-9]+)(?:\\.(?:[a-z\\u00a1-\\uffff0-9]+-*)*[a-z\\u00a1-\\uffff0-9]+)*(?:\\.(?:[a-z\\u00a1-\\uffff]{2,})))|localhost)(?::\\d{2,5})?(?:(/|\\?|#)[^\\s]*)?$","i"),hex:/^#?([a-f0-9]{6}|[a-f0-9]{3})$/i},D={integer:function(e){return D.number(e)&&parseInt(e,10)===e},float:function(e){return D.number(e)&&!D.integer(e)},array:function(e){return Array.isArray(e)},regexp:function(e){if(e instanceof RegExp)return!0;try{return!!new RegExp(e)}catch(t){return!1}},date:function(e){return"function"==typeof e.getTime&&"function"==typeof e.getMonth&&"function"==typeof e.getYear},number:function(e){return!isNaN(e)&&"number"==typeof+e},object:function(e){return"object"==typeof e&&!D.array(e)},method:function(e){return"function"==typeof e},email:function(e){return"string"==typeof e&&!!e.match(N.email)&&e.length<255},url:function(e){return"string"==typeof e&&!!e.match(N.url)},hex:function(e){return"string"==typeof e&&!!e.match(N.hex)}};var R="enum";var V={required:$,whitespace:function(e,t,r,i,n){(/^\s+$/.test(t)||""===t)&&i.push(E(n.messages.whitespace,e.fullField))},type:function(e,t,r,i,n){if(e.required&&void 0===t)$(e,t,r,i,n);else{var a=e.type;["integer","float","array","regexp","object","method","email","number","date","url","hex"].indexOf(a)>-1?D[a](t)||i.push(E(n.messages.types[a],e.fullField,e.type)):a&&typeof t!==e.type&&i.push(E(n.messages.types[a],e.fullField,e.type))}},range:function(e,t,r,i,n){var a="number"==typeof e.len,s="number"==typeof e.min,l="number"==typeof e.max,o=t,u=null,d="number"==typeof t,f="string"==typeof t,c=Array.isArray(t);if(d?u="number":f?u="string":c&&(u="array"),!u)return!1;c&&(o=t.length),f&&(o=t.replace(/[\uD800-\uDBFF][\uDC00-\uDFFF]/g,"_").length),a?o!==e.len&&i.push(E(n.messages[u].len,e.fullField,e.len)):s&&!l&&o<e.min?i.push(E(n.messages[u].min,e.fullField,e.min)):l&&!s&&o>e.max?i.push(E(n.messages[u].max,e.fullField,e.max)):s&&l&&(o<e.min||o>e.max)&&i.push(E(n.messages[u].range,e.fullField,e.min,e.max))},enum:function(e,t,r,i,n){e[R]=Array.isArray(e[R])?e[R]:[],-1===e[R].indexOf(t)&&i.push(E(n.messages[R],e.fullField,e[R].join(", ")))},pattern:function(e,t,r,i,n){if(e.pattern)if(e.pattern instanceof RegExp)e.pattern.lastIndex=0,e.pattern.test(t)||i.push(E(n.messages.pattern.mismatch,e.fullField,t,e.pattern));else if("string"==typeof e.pattern){new RegExp(e.pattern).test(t)||i.push(E(n.messages.pattern.mismatch,e.fullField,t,e.pattern))}}};function L(e,t,r,i,n){var a=e.type,s=[];if(e.required||!e.required&&i.hasOwnProperty(e.field)){if(C(t,a)&&!e.required)return r();V.required(e,t,i,s,n,a),C(t,a)||V.type(e,t,i,s,n)}r(s)}var M={string:function(e,t,r,i,n){var a=[];if(e.required||!e.required&&i.hasOwnProperty(e.field)){if(C(t,"string")&&!e.required)return r();V.required(e,t,i,a,n,"string"),C(t,"string")||(V.type(e,t,i,a,n),V.range(e,t,i,a,n),V.pattern(e,t,i,a,n),!0===e.whitespace&&V.whitespace(e,t,i,a,n))}r(a)},method:function(e,t,r,i,n){var a=[];if(e.required||!e.required&&i.hasOwnProperty(e.field)){if(C(t)&&!e.required)return r();V.required(e,t,i,a,n),void 0!==t&&V.type(e,t,i,a,n)}r(a)},number:function(e,t,r,i,n){var a=[];if(e.required||!e.required&&i.hasOwnProperty(e.field)){if(""===t&&(t=void 0),C(t)&&!e.required)return r();V.required(e,t,i,a,n),void 0!==t&&(V.type(e,t,i,a,n),V.range(e,t,i,a,n))}r(a)},boolean:function(e,t,r,i,n){var a=[];if(e.required||!e.required&&i.hasOwnProperty(e.field)){if(C(t)&&!e.required)return r();V.required(e,t,i,a,n),void 0!==t&&V.type(e,t,i,a,n)}r(a)},regexp:function(e,t,r,i,n){var a=[];if(e.required||!e.required&&i.hasOwnProperty(e.field)){if(C(t)&&!e.required)return r();V.required(e,t,i,a,n),C(t)||V.type(e,t,i,a,n)}r(a)},integer:function(e,t,r,i,n){var a=[];if(e.required||!e.required&&i.hasOwnProperty(e.field)){if(C(t)&&!e.required)return r();V.required(e,t,i,a,n),void 0!==t&&(V.type(e,t,i,a,n),V.range(e,t,i,a,n))}r(a)},float:function(e,t,r,i,n){var a=[];if(e.required||!e.required&&i.hasOwnProperty(e.field)){if(C(t)&&!e.required)return r();V.required(e,t,i,a,n),void 0!==t&&(V.type(e,t,i,a,n),V.range(e,t,i,a,n))}r(a)},array:function(e,t,r,i,n){var a=[];if(e.required||!e.required&&i.hasOwnProperty(e.field)){if(C(t,"array")&&!e.required)return r();V.required(e,t,i,a,n,"array"),C(t,"array")||(V.type(e,t,i,a,n),V.range(e,t,i,a,n))}r(a)},object:function(e,t,r,i,n){var a=[];if(e.required||!e.required&&i.hasOwnProperty(e.field)){if(C(t)&&!e.required)return r();V.required(e,t,i,a,n),void 0!==t&&V.type(e,t,i,a,n)}r(a)},enum:function(e,t,r,i,n){var a=[];if(e.required||!e.required&&i.hasOwnProperty(e.field)){if(C(t)&&!e.required)return r();V.required(e,t,i,a,n),void 0!==t&&V.enum(e,t,i,a,n)}r(a)},pattern:function(e,t,r,i,n){var a=[];if(e.required||!e.required&&i.hasOwnProperty(e.field)){if(C(t,"string")&&!e.required)return r();V.required(e,t,i,a,n),C(t,"string")||V.pattern(e,t,i,a,n)}r(a)},date:function(e,t,r,i,n){var a=[];if(e.required||!e.required&&i.hasOwnProperty(e.field)){if(C(t)&&!e.required)return r();var s;if(V.required(e,t,i,a,n),!C(t))s="number"==typeof t?new Date(t):t,V.type(e,s,i,a,n),s&&V.range(e,s.getTime(),i,a,n)}r(a)},url:L,hex:L,email:L,required:function(e,t,r,i,n){var a=[],s=Array.isArray(t)?"array":typeof t;V.required(e,t,i,a,n,s),r(a)},any:function(e,t,r,i,n){var a=[];if(e.required||!e.required&&i.hasOwnProperty(e.field)){if(C(t)&&!e.required)return r();V.required(e,t,i,a,n)}r(a)}};function W(){return{default:"Validation error on field %s",required:"%s is required",enum:"%s must be one of %s",whitespace:"%s cannot be empty",date:{format:"%s date %s is invalid for format %s",parse:"%s date could not be parsed, %s is invalid ",invalid:"%s date %s is invalid"},types:{string:"%s is not a %s",method:"%s is not a %s (function)",array:"%s is not an %s",object:"%s is not an %s",number:"%s is not a %s",date:"%s is not a %s",boolean:"%s is not a %s",integer:"%s is not an %s",float:"%s is not a %s",regexp:"%s is not a valid %s",email:"%s is not a valid %s",url:"%s is not a valid %s",hex:"%s is not a valid %s"},string:{len:"%s must be exactly %s characters",min:"%s must be at least %s characters",max:"%s cannot be longer than %s characters",range:"%s must be between %s and %s characters"},number:{len:"%s must equal %s",min:"%s cannot be less than %s",max:"%s cannot be greater than %s",range:"%s must be between %s and %s"},array:{len:"%s must be exactly %s in length",min:"%s cannot be less than %s in length",max:"%s cannot be greater than %s in length",range:"%s must be between %s and %s in length"},pattern:{mismatch:"%s value %s does not match pattern %s"},clone:function(){var e=JSON.parse(JSON.stringify(this));return e.clone=this.clone,e}}}var z=W();function H(e){this.rules=null,this._messages=z,this.define(e)}H.prototype={messages:function(e){return e&&(this._messages=B(W(),e)),this._messages},define:function(e){if(!e)throw new Error("Cannot configure a schema with no rules");if("object"!=typeof e||Array.isArray(e))throw new Error("Rules must be an object");var t,r;for(t in this.rules={},e)e.hasOwnProperty(t)&&(r=e[t],this.rules[t]=Array.isArray(r)?r:[r])},validate:function(e,t,r){var i=this;void 0===t&&(t={}),void 0===r&&(r=function(){});var n,a,s=e,l=t,o=r;if("function"==typeof l&&(o=l,l={}),!this.rules||0===Object.keys(this.rules).length)return o&&o(),Promise.resolve();if(l.messages){var u=this.messages();u===z&&(u=W()),B(u,l.messages),l.messages=u}else l.messages=this.messages();var d={};(l.keys||Object.keys(this.rules)).forEach((function(t){n=i.rules[t],a=s[t],n.forEach((function(r){var n=r;"function"==typeof n.transform&&(s===e&&(s=A({},s)),a=s[t]=n.transform(a)),(n="function"==typeof n?{validator:n}:A({},n)).validator=i.getValidationMethod(n),n.field=t,n.fullField=n.fullField||t,n.type=i.getType(n),n.validator&&(d[t]=d[t]||[],d[t].push({rule:n,value:a,source:s,field:t}))}))}));var f={};return T(d,l,(function(e,t){var r,i=e.rule,n=!("object"!==i.type&&"array"!==i.type||"object"!=typeof i.fields&&"object"!=typeof i.defaultField);function a(e,t){return A({},t,{fullField:i.fullField+"."+e})}function s(r){void 0===r&&(r=[]);var s=r;if(Array.isArray(s)||(s=[s]),!l.suppressWarning&&s.length&&H.warning("async-validator:",s),s.length&&i.message&&(s=[].concat(i.message)),s=s.map(k(i)),l.first&&s.length)return f[i.field]=1,t(s);if(n){if(i.required&&!e.value)return s=i.message?[].concat(i.message).map(k(i)):l.error?[l.error(i,E(l.messages.required,i.field))]:[],t(s);var o={};if(i.defaultField)for(var u in e.value)e.value.hasOwnProperty(u)&&(o[u]=i.defaultField);for(var d in o=A({},o,{},e.rule.fields))if(o.hasOwnProperty(d)){var c=Array.isArray(o[d])?o[d]:[o[d]];o[d]=c.map(a.bind(null,d))}var h=new H(o);h.messages(l.messages),e.rule.options&&(e.rule.options.messages=l.messages,e.rule.options.error=l.error),h.validate(e.value,e.rule.options||l,(function(e){var r=[];s&&s.length&&r.push.apply(r,s),e&&e.length&&r.push.apply(r,e),t(r.length?r:null)}))}else t(s)}n=n&&(i.required||!i.required&&e.value),i.field=e.field,i.asyncValidator?r=i.asyncValidator(i,e.value,s,e.source,l):i.validator&&(!0===(r=i.validator(i,e.value,s,e.source,l))?s():!1===r?s(i.message||i.field+" fails"):r instanceof Array?s(r):r instanceof Error&&s(r.message)),r&&r.then&&r.then((function(){return s()}),(function(e){return s(e)}))}),(function(e){!function(e){var t,r,i,n=[],a={};for(t=0;t<e.length;t++)r=e[t],i=void 0,Array.isArray(r)?n=(i=n).concat.apply(i,r):n.push(r);n.length?a=P(n):(n=null,a=null),o(n,a)}(e)}))},getType:function(e){if(void 0===e.type&&e.pattern instanceof RegExp&&(e.type="pattern"),"function"!=typeof e.validator&&e.type&&!M.hasOwnProperty(e.type))throw new Error(E("Unknown rule type %s",e.type));return e.type||"string"},getValidationMethod:function(e){if("function"==typeof e.validator)return e.validator;var t=Object.keys(e),r=t.indexOf("message");return-1!==r&&t.splice(r,1),1===t.length&&"required"===t[0]?M.required:M[this.getType(e)]||!1}},H.register=function(e,t){if("function"!=typeof t)throw new Error("Cannot register a validator by type, validator is not a function");M[e]=t},H.warning=j,H.messages=z;H.warning=function(){};const G=w({name:"u-form-item",mixins:[q],inject:{uForm:{default:()=>null}},props:{label:{type:String,default:""},prop:{type:String,default:""},borderBottom:{type:[String,Boolean],default:""},labelPosition:{type:String,default:""},labelWidth:{type:[String,Number],default:""},labelStyle:{type:Object,default:()=>({})},labelAlign:{type:String,default:""},rightIcon:{type:String,default:""},leftIcon:{type:String,default:""},leftIconStyle:{type:Object,default:()=>({})},rightIconStyle:{type:Object,default:()=>({})},required:{type:Boolean,default:!1},inputAlign:{type:String,default:""}},data:()=>({initialValue:"",validateState:"",validateMessage:"",errorType:["message"],fieldValue:"",parentData:{borderBottom:!0,labelWidth:90,labelPosition:"left",labelStyle:{},labelAlign:"left",inputAlign:"left"}}),watch:{validateState(e){this.broadcastInputError()},"uForm.errorType"(e){this.errorType=e,this.broadcastInputError()}},computed:{uLabelWidth(){return"left"==this.elLabelPosition?"true"===this.label||""===this.label?"auto":this.$u.addUnit(this.elLabelWidth):"100%"},showError(){return e=>!(this.errorType.indexOf("none")>=0)&&this.errorType.indexOf(e)>=0},elLabelWidth(){return 0!=this.labelWidth||""!=this.labelWidth?this.labelWidth:this.parentData.labelWidth?this.parentData.labelWidth:90},elLabelStyle(){return Object.keys(this.labelStyle).length?this.labelStyle:this.parentData.labelStyle?this.parentData.labelStyle:{}},elLabelPosition(){return this.labelPosition?this.labelPosition:this.parentData.labelPosition?this.parentData.labelPosition:"left"},elLabelAlign(){return this.labelAlign?this.labelAlign:this.parentData.labelAlign?this.parentData.labelAlign:"left"},elBorderBottom(){return""!==this.borderBottom?this.borderBottom:!this.parentData.borderBottom||this.parentData.borderBottom},elInputAlign(){return this.inputAlign?this.inputAlign:this.parentData.inputAlign?this.parentData.inputAlign:"left"}},methods:{broadcastInputError(){this.broadcast("u-input","onFormItemError","error"===this.validateState&&this.showError("border"))},setRules(){},getRules(){let e=this.parent.rules;return e=e?e[this.prop]:[],[].concat(e||[])},onFieldBlur(){this.validation("blur")},onFieldChange(){this.validation("change")},getFilteredRule(e=""){let t=this.getRules();return e?t.filter((t=>t.trigger&&-1!==t.trigger.indexOf(e))):t},getData(e,t,r){let i;if(e){i=JSON.parse(JSON.stringify(e));let r="",n=".",a="[",s="]";t=t.replace(/\s+/g,r)+n;let l=r;for(let e=0;e<t.length;e++){let o=t.charAt(e);o!=n&&o!=a&&o!=s?l+=o:i&&(l!=r&&(i=i[l]),l=r)}}return void 0===i&&void 0!==r&&(i=r),i},setData(e,t,r){let i;i="object"==typeof r?JSON.parse(JSON.stringify(r)):r;let n=new RegExp("([\\w$]+)|\\[(:\\d)\\]","g");const a=t.match(n);for(let s=0;s<a.length-1;s++){let t=a[s];"object"!=typeof e[t]&&(e[t]={}),e=e[t]}e[a[a.length-1]]=i},validation(e,t=(()=>{})){this.fieldValue=this.getData(this.parent.model,this.prop);let r=this.getFilteredRule(e);if(!r||0===r.length)return t("");this.validateState="validating",new H({[this.prop]:r}).validate({[this.prop]:this.fieldValue},{firstFields:!0},((e,r)=>{this.validateState=e?"error":"success",this.validateMessage=e?e[0].message:"";let i=e?e[0].field:"";t(this.validateMessage,{state:this.validateState,key:i,msg:this.validateMessage})}))},resetField(){this.setData(this.parent.model,this.prop,this.initialValue),this.validateState="success"}},mounted(){this.parent=this.$u.$parent.call(this,"u-form"),this.parent&&(Object.keys(this.parentData).map((e=>{this.parentData[e]=this.parent[e]})),this.prop&&(this.parent.fields.push(this),this.errorType=this.parent.errorType,this.initialValue=this.fieldValue,this.$nextTick((()=>{this.setRules()}))))},beforeUnmount(){this.parent&&this.prop&&this.parent.fields.map(((e,t)=>{e===this&&this.parent.fields.splice(t,1)}))}},[["render",function(u,d,f,y,b,v){const S=g,w=e(t("u-icon"),_),x=c;return r(),i(x,{class:o(["u-form-item",{"u-border-bottom":v.elBorderBottom,"u-form-item__border-bottom--error":"error"===b.validateState&&v.showError("border-bottom")}])},{default:n((()=>[s(x,{class:"u-form-item__body",style:a({flexDirection:"left"==v.elLabelPosition?"row":"column"})},{default:n((()=>[l(' 微信小程序中，将一个参数设置空字符串，结果会变成字符串"true" '),s(x,{class:"u-form-item--left",style:a({width:v.uLabelWidth,flex:`0 0 ${v.uLabelWidth}`,marginBottom:"left"==v.elLabelPosition?0:"10rpx"})},{default:n((()=>[l(" 为了块对齐 "),f.required||f.leftIcon||f.label?(r(),i(x,{key:0,class:"u-form-item--left__content"},{default:n((()=>[l(" nvue不支持伪元素before "),f.required?(r(),i(S,{key:0,class:"u-form-item--left__content--required"},{default:n((()=>[h("*")])),_:1})):l("v-if",!0),f.leftIcon?(r(),i(x,{key:1,class:"u-form-item--left__content__icon"},{default:n((()=>[s(w,{name:f.leftIcon,"custom-style":f.leftIconStyle},null,8,["name","custom-style"])])),_:1})):l("v-if",!0),s(x,{class:"u-form-item--left__content__label",style:a([v.elLabelStyle,{"justify-content":"left"==v.elLabelAlign?"flex-start":"center"==v.elLabelAlign?"center":"flex-end"}])},{default:n((()=>[h(p(f.label),1)])),_:1},8,["style"])])),_:1})):l("v-if",!0)])),_:1},8,["style"]),s(x,{class:"u-form-item--right u-flex"},{default:n((()=>[s(x,{class:"u-form-item--right__content"},{default:n((()=>[s(x,{class:"u-form-item--right__content__slot",style:a("left"==v.elLabelPosition&&"right"==v.elInputAlign?"text-align:right;display: inline-block;line-height:initial;":"")},{default:n((()=>[m(u.$slots,"default",{},void 0,!0)])),_:3},8,["style"]),u.$slots.right||f.rightIcon?(r(),i(x,{key:0,class:"u-form-item--right__content__icon u-flex"},{default:n((()=>[f.rightIcon?(r(),i(w,{key:0,"custom-style":f.rightIconStyle,name:f.rightIcon},null,8,["custom-style","name"])):l("v-if",!0),m(u.$slots,"right",{},void 0,!0)])),_:3})):l("v-if",!0)])),_:3})])),_:3})])),_:3},8,["style"]),"error"===b.validateState&&v.showError("message")?(r(),i(x,{key:0,class:"u-form-item__message",style:a({paddingLeft:"left"==v.elLabelPosition?u.$u.addUnit(v.elLabelWidth):"0",textAlign:"right"==v.elInputAlign?"right":"left"})},{default:n((()=>[h(p(b.validateMessage),1)])),_:1},8,["style"])):l("v-if",!0)])),_:3},8,["class"])}],["__scopeId","data-v-2d3de664"]]);const J=w({name:"u-verification-code",emits:["start","end","change"],props:{seconds:{type:[String,Number],default:60},startText:{type:String,default:"获取验证码"},changeText:{type:String,default:"X秒重新获取"},endText:{type:String,default:"重新获取"},keepRunning:{type:Boolean,default:!1},uniqueKey:{type:String,default:""}},data(){return{secNum:this.seconds,timer:null,canGetCode:!0}},mounted(){this.checkKeepRunning()},watch:{seconds:{immediate:!0,handler(e){this.secNum=e}}},methods:{checkKeepRunning(){let e=Number(y(this.uniqueKey+"_$uCountDownTimestamp"));if(!e)return this.changeEvent(this.startText);let t=Math.floor(+new Date/1e3);this.keepRunning&&e&&e>t?(this.secNum=e-t,b(this.uniqueKey+"_$uCountDownTimestamp"),this.start()):this.changeEvent(this.startText)},start(){this.timer&&(clearInterval(this.timer),this.timer=null),this.$emit("start"),this.canGetCode=!1,this.changeEvent(this.changeText.replace(/x|X/,this.secNum)),this.setTimeToStorage(),this.timer=setInterval((()=>{--this.secNum?this.changeEvent(this.changeText.replace(/x|X/,this.secNum)):(clearInterval(this.timer),this.timer=null,this.changeEvent(this.endText),this.secNum=this.seconds,this.$emit("end"),this.canGetCode=!0)}),1e3)},reset(){this.canGetCode=!0,clearInterval(this.timer),this.secNum=this.seconds,this.changeEvent(this.endText)},changeEvent(e){this.$emit("change",e)},setTimeToStorage(){if(this.keepRunning&&this.timer&&this.secNum>0&&this.secNum<=this.seconds){let e=Math.floor(+new Date/1e3);v({key:this.uniqueKey+"_$uCountDownTimestamp",data:e+Number(this.secNum)})}}},beforeUnmount(){this.setTimeToStorage(),clearTimeout(this.timer),this.timer=null}},[["render",function(e,t,a,s,o,u){const d=c;return r(),i(d,{class:"u-code-wrap"},{default:n((()=>[l(" 此组件功能由js完成，无需写html逻辑 ")])),_:1})}],["__scopeId","data-v-69126394"]]);const K=w({name:"u-form",props:{model:{type:Object,default:()=>({})},errorType:{type:Array,default:()=>["message","toast"]},borderBottom:{type:Boolean,default:!0},labelPosition:{type:String,default:"left"},labelWidth:{type:[String,Number],default:90},labelAlign:{type:String,default:"left"},labelStyle:{type:Object,default:()=>({})},inputAlign:{type:String,default:"left"},clearable:{type:Boolean,default:!0}},provide(){return{uForm:this}},data:()=>({rules:{}}),created(){this.fields=[]},methods:{setRules(e){this.rules=e},resetFields(){this.fields.map((e=>{e.resetField()}))},validate(e){return new Promise((t=>{let r=!0,i=0,n=[],a=[];this.fields.map((s=>{s.validation("",((s,l)=>{s&&(r=!1,n.push(s),a.push(l)),++i===this.fields.length&&(t(r,a[0]),-1===this.errorType.indexOf("none")&&this.errorType.indexOf("toast")>=0&&n.length&&this.$u.toast(n[0]),"function"==typeof e&&e(r,a[0]))}))}))}))}}},[["render",function(e,t,a,s,l,o){const u=c;return r(),i(u,{class:"u-form"},{default:n((()=>[m(e.$slots,"default",{},void 0,!0)])),_:3})}],["__scopeId","data-v-63b8cfba"]]);function U(e,t=0){let r=S(!1);return{loading:r,methodAPI:async i=>{if(r.value)throw new Error("请勿重新请求~");r.value=!0;const n=await e(i).catch((e=>(t?setTimeout((()=>{r.value=!1}),t):r.value=!1,Promise.reject(e))));return t?setTimeout((()=>{r.value=!1}),t):r.value=!1,n}}}const Z={LOGIN:101,REGISTER:102,FORGET_PWD:103,CHANGE_MOBILE:104,BIND_MOBILE:105,BIND_EMAIL:106},X={is:(e,t)=>toString.call(e)===`[object ${t}]`,isDevMode:()=>!1,isProdMode:()=>!0,isMap(e){return this.is(e,"Map")},isDate(e){return this.is(e,"Date")},isNumber(e){return this.is(e,"Number")},isString(e){return this.is(e,"String")},isBoolean(e){return this.is(e,"Boolean")},isRegExp(e){return this.is(e,"RegExp")},isArray:e=>e&&Array.isArray(e),isFunction:e=>"function"==typeof e,isObject(e){return null!==e&&this.is(e,"Object")},isPromise(e){return this.is(e,"Promise")&&this.isObject(e)&&this.isFunction(e.then)&&this.isFunction(e.catch)},isNull:e=>null===e,isEmpty(e){return this.isString(e)||this.isArray(e)?0===e.length:e instanceof Map||e instanceof Set?0===e.size:!!this.isObject(e)&&0===Object.keys(e).length},isUndefined:e=>void 0!==e,isWindow(e){return"undefined"!=typeof window&&this.is(e,"Window")},isMobile:e=>/^1[3-9]\d{9}$/.test(e),isEmail:e=>/^([a-zA-Z0-9_-])+@([a-zA-Z0-9_-])+((.[a-zA-Z0-9_-]{2,3}){1,2})$/.test(e)};export{O as _,G as a,J as b,X as c,K as d,Z as s,U as u};
